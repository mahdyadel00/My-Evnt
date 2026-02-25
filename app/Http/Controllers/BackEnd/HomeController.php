<?php

declare(strict_types=1);

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use App\Models\Company;
use App\Models\Ticket;
use App\Models\EventCategory;
use App\Models\City;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource with advanced analytics.
     */
    public function index()
    {
        try {
            // Cache key for dashboard data (5 minutes cache)
            $cacheKey = 'dashboard_analytics_' . now()->format('Y-m-d-H-i');

            $analytics = Cache::remember($cacheKey, 300, function () {
                return $this->getAdvancedAnalytics();
            });

            return view('backend.home', $analytics);

        } catch (\Exception $e) {
            Log::error('Dashboard Analytics Error: ' . $e->getMessage());

            // Return basic data if analytics fail
            return view('backend.home', [
                'totalEvents' => 0,
                'totalUsers' => 0,
                'totalCompanies' => 0,
                'totalOrders' => 0,
                'totalRevenue' => 0,
                'monthlyRevenue' => 0,
                'revenueGrowth' => 0,
                'totalTicketsSold' => 0,
                'totalTicketsAvailable' => 0,
                'pendingOrders' => 0,
                'activeEvents' => 0,
                'upcomingEvents' => 0,
                'pastEvents' => 0,
                'monthlyTrends' => [],
                'weeklyTrends' => [],
                'dailyStats' => [],
                'hourlyStats' => [],
                'topEvents' => [],
                'recentOrders' => [],
                'categoryStats' => [],
                'cityStats' => [],
                'countryStats' => [],
                'paymentStatusDistribution' => [],
                'eventStatusDistribution' => [],
                'revenueByPaymentMethod' => [],
                'userRegistrationTrends' => [],
                'conversionRate' => 0,
                'averageOrderValue' => 0,
                'customerLifetimeValue' => 0,
                'revenuePerEvent' => 0,
                'ticketUtilizationRate' => 0,
                'userGrowthRate' => 0,
                'activeUsers' => 0,
                'topUsers' => [],
                'eventPerformanceByStatus' => [],
            ]);
        }
    }

    /**
     * Get comprehensive analytics data for dashboard.
     */
    private function getAdvancedAnalytics(): array
    {
        try {
            $now = now();
            $startOfMonth = $now->copy()->startOfMonth();
            $startOfYear = $now->copy()->startOfYear();
            $lastMonth = $now->copy()->subMonth();
            $lastYear = $now->copy()->subYear();

            // === CORE METRICS ===
            $coreMetrics = $this->getCoreMetrics($now, $startOfMonth, $lastMonth);

            // === REVENUE ANALYTICS ===
            $revenueAnalytics = $this->getRevenueAnalytics($now, $startOfMonth, $lastMonth, $startOfYear, $lastYear);

            // === EVENT ANALYTICS ===
            $eventAnalytics = $this->getEventAnalytics($now);

            // === USER ANALYTICS ===
            $userAnalytics = $this->getUserAnalytics($now, $startOfMonth, $lastMonth);

            // === PERFORMANCE METRICS ===
            $performanceMetrics = $this->getPerformanceMetrics($now);

            // === TREND ANALYSIS ===
            $trendAnalysis = $this->getTrendAnalysis($now);

            // === GEOGRAPHIC ANALYTICS ===
            $geographicAnalytics = $this->getGeographicAnalytics();

            // === TIME-BASED ANALYTICS ===
            $timeBasedAnalytics = $this->getTimeBasedAnalytics($now);

            // === ADVANCED CHARTS DATA ===
            $advancedCharts = $this->getAdvancedChartsData($now);

            return array_merge(
                $coreMetrics,
                $revenueAnalytics,
                $eventAnalytics,
                $userAnalytics,
                $performanceMetrics,
                $trendAnalysis,
                $geographicAnalytics,
                $timeBasedAnalytics,
                $advancedCharts
            );

        } catch (\Exception $e) {
            Log::error('Advanced Analytics Error: ' . $e->getMessage());
            throw $e; // Re-throw to be caught by the main try-catch
        }
    }

    /**
     * Get core platform metrics.
     */
    private function getCoreMetrics(Carbon $now, Carbon $startOfMonth, Carbon $lastMonth): array
    {
        try {
            return [
                'totalEvents' => Event::count(),
                'totalUsers' => User::count(),
                'totalCompanies' => Company::count(),
                'totalOrders' => Order::count(),
                'totalNotifications' => Notification::count(),
                'activeEvents' => Event::where('is_active', true)->count(),
                'upcomingEvents' => Event::whereHas('eventDates', function ($query) use ($now) {
                    $query->where('start_date', '>', $now);
                })->count(),
                'pastEvents' => Event::whereHas('eventDates', function ($query) use ($now) {
                    $query->where('end_date', '<', $now);
                })->count(),
                'pendingOrders' => Order::where('payment_status', 'pending')->count(),
                'totalTicketsSold' => Order::where('payment_status', 'paid')->sum('quantity'),
                'totalTicketsAvailable' => Ticket::sum('quantity'),
            ];
        } catch (\Exception $e) {
            Log::error('Core Metrics Error: ' . $e->getMessage());
            return [
                'totalEvents' => 0,
                'totalUsers' => 0,
                'totalCompanies' => 0,
                'totalOrders' => 0,
                'totalNotifications' => 0,
                'activeEvents' => 0,
                'upcomingEvents' => 0,
                'pastEvents' => 0,
                'pendingOrders' => 0,
                'totalTicketsSold' => 0,
                'totalTicketsAvailable' => 0,
            ];
        }
    }

    /**
     * Get comprehensive revenue analytics.
     */
    private function getRevenueAnalytics(Carbon $now, Carbon $startOfMonth, Carbon $lastMonth, Carbon $startOfYear, Carbon $lastYear): array
    {
        try {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $monthlyRevenue = Order::where('payment_status', 'paid')
                ->where('created_at', '>=', $startOfMonth)
            ->sum('total');
        $lastMonthRevenue = Order::where('payment_status', 'paid')
                ->whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->sum('total');
            $yearlyRevenue = Order::where('payment_status', 'paid')
                ->where('created_at', '>=', $startOfYear)
                ->sum('total');
            $lastYearRevenue = Order::where('payment_status', 'paid')
                ->whereYear('created_at', $lastYear->year)
            ->sum('total');

            // Calculate growth rates
            $revenueGrowth = $this->calculateGrowthRate($monthlyRevenue, $lastMonthRevenue);
            $yearlyGrowth = $this->calculateGrowthRate($yearlyRevenue, $lastYearRevenue);

            // Daily revenue for last 30 days
            $dailyRevenue = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = $now->copy()->subDays($i);
                try {
                    $dailyRevenue[] = [
                        'date' => $date->format('Y-m-d'),
                        'revenue' => Order::where('payment_status', 'paid')
                            ->whereDate('created_at', $date)
                            ->sum('total'),
                        'orders' => Order::whereDate('created_at', $date)->count(),
                    ];
                } catch (\Exception $e) {
                    Log::warning('Daily revenue error for date ' . $date->format('Y-m-d') . ': ' . $e->getMessage());
                    $dailyRevenue[] = [
                        'date' => $date->format('Y-m-d'),
                        'revenue' => 0,
                        'orders' => 0,
                    ];
                }
            }

            // Revenue by payment method
            $revenueByPaymentMethod = Order::where('payment_status', 'paid')
                ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(total) as total'))
                ->groupBy('payment_method')
                ->get()
                ->map(function ($item) {
                    return [
                        'method' => $item->payment_method ?? 'Unknown',
                        'count' => $item->count,
                        'total' => $item->total,
                        'percentage' => 0 // Will be calculated in frontend
                    ];
                });

            return [
                'totalRevenue' => $totalRevenue,
                'monthlyRevenue' => $monthlyRevenue,
                'yearlyRevenue' => $yearlyRevenue,
                'revenueGrowth' => $revenueGrowth,
                'yearlyGrowth' => $yearlyGrowth,
                'dailyRevenue' => $dailyRevenue,
                'revenueByPaymentMethod' => $revenueByPaymentMethod,
            ];

        } catch (\Exception $e) {
            Log::error('Revenue Analytics Error: ' . $e->getMessage());
            return [
                'totalRevenue' => 0,
                'monthlyRevenue' => 0,
                'yearlyRevenue' => 0,
                'revenueGrowth' => 0,
                'yearlyGrowth' => 0,
                'dailyRevenue' => [],
                'revenueByPaymentMethod' => [],
            ];
        }
    }

    /**
     * Get event analytics and performance metrics.
     */
    private function getEventAnalytics(Carbon $now): array
    {
        try {
            // Top performing events with detailed metrics
            $topEvents = Event::withCount([
                'orders as total_sales' => function ($query) {
                    $query->where('payment_status', 'paid');
                },
                'orders as total_views' => function ($query) {
                    $query->where('payment_status', 'paid');
                }
            ])
                ->with(['company', 'category', 'city', 'tickets'])
                ->orderBy('total_sales', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($event) {
                    try {
                        $revenue = $event->orders()->where('payment_status', 'paid')->sum('total');
                        $ticketsSold = $event->orders()->where('payment_status', 'paid')->sum('quantity');
                        $ticketsAvailable = $event->tickets->sum('quantity');
                        $conversionRate = $ticketsAvailable > 0 ? ($ticketsSold / $ticketsAvailable) * 100 : 0;

                        return [
                            'id' => $event->id,
                            'name' => $event->name,
                            'company' => $event->company->company_name ?? $event->company->first_name ?? 'Unknown',
                            'category' => $event->category->name ?? 'Unknown',
                            'city' => $event->city->name ?? 'Unknown',
                            'total_sales' => $ticketsSold,
                            'total_revenue' => $revenue,
                            'conversion_rate' => round($conversionRate, 2),
                            'view_count' => $event->view_count ?? 0,
                            'status' => $event->is_active ? 'Active' : 'Inactive',
                            'created_at' => $event->created_at->format('M d, Y'),
                        ];
                    } catch (\Exception $e) {
                        Log::warning('Event analytics error for event ' . $event->id . ': ' . $e->getMessage());
                        return [
                            'id' => $event->id,
                            'name' => $event->name ?? 'Unknown',
                            'company' => 'Unknown',
                            'category' => 'Unknown',
                            'city' => 'Unknown',
                            'total_sales' => 0,
                            'total_revenue' => 0,
                            'conversion_rate' => 0,
                            'view_count' => 0,
                            'status' => 'Unknown',
                            'created_at' => 'Unknown',
                        ];
                    }
                });

            // Event categories with performance metrics
            $categoryStats = EventCategory::withCount('events')
                ->with(['events' => function ($query) {
                    $query->withCount(['orders as paid_orders' => function ($q) {
                        $q->where('payment_status', 'paid');
                    }]);
                }])
                ->having('events_count', '>', 0)
                ->orderBy('events_count', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($category) {
                    try {
                        $totalRevenue = 0;
                        $totalTicketsSold = 0;

                        foreach ($category->events as $event) {
                            $totalRevenue += $event->orders()->where('payment_status', 'paid')->sum('total');
                            $totalTicketsSold += $event->orders()->where('payment_status', 'paid')->sum('quantity');
                        }

                        return [
                            'id' => $category->id,
                            'name' => $category->name,
                            'events_count' => $category->events_count,
                            'total_revenue' => $totalRevenue,
                            'total_tickets_sold' => $totalTicketsSold,
                            'average_revenue_per_event' => $category->events_count > 0 ? $totalRevenue / $category->events_count : 0,
                        ];
                    } catch (\Exception $e) {
                        Log::warning('Category analytics error for category ' . $category->id . ': ' . $e->getMessage());
                        return [
                            'id' => $category->id,
                            'name' => $category->name ?? 'Unknown',
                            'events_count' => 0,
                            'total_revenue' => 0,
                            'total_tickets_sold' => 0,
                            'average_revenue_per_event' => 0,
                        ];
                    }
                });

            // Event performance by status
            $eventPerformanceByStatus = [
                'active' => Event::where('is_active', true)->count(),
                'inactive' => Event::where('is_active', false)->count(),
                'upcoming' => Event::whereHas('eventDates', function ($query) use ($now) {
                    $query->where('start_date', '>', $now);
                })->count(),
                'past' => Event::whereHas('eventDates', function ($query) use ($now) {
                    $query->where('end_date', '<', $now);
                })->count(),
            ];

            return [
                'topEvents' => $topEvents,
                'categoryStats' => $categoryStats,
                'eventPerformanceByStatus' => $eventPerformanceByStatus,
            ];

        } catch (\Exception $e) {
            Log::error('Event Analytics Error: ' . $e->getMessage());
            return [
                'topEvents' => [],
                'categoryStats' => [],
                'eventPerformanceByStatus' => [],
            ];
        }
    }

    /**
     * Get user analytics and engagement metrics.
     */
    private function getUserAnalytics(Carbon $now, Carbon $startOfMonth, Carbon $lastMonth): array
    {
        try {
            // User growth metrics
            $totalUsers = User::count();
            $monthlyNewUsers = User::where('created_at', '>=', $startOfMonth)->count();
            $lastMonthNewUsers = User::whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->count();
            $userGrowthRate = $this->calculateGrowthRate($monthlyNewUsers, $lastMonthNewUsers);

            // User engagement metrics
            $activeUsers = User::whereHas('orders', function ($query) use ($startOfMonth) {
                $query->where('created_at', '>=', $startOfMonth);
        })->count();

            $topUsers = User::withCount(['orders as total_orders' => function ($query) {
                $query->where('payment_status', 'paid');
            }])
                ->with(['city', 'country'])
                ->orderBy('total_orders', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($user) {
                    try {
                        $totalSpent = $user->orders()->where('payment_status', 'paid')->sum('total');
                        return [
                            'id' => $user->id,
                            'name' => trim($user->first_name . ' ' . $user->last_name),
                            'email' => $user->email,
                            'city' => $user->city->name ?? 'Unknown',
                            'country' => $user->country->name ?? 'Unknown',
                            'total_orders' => $user->total_orders,
                            'total_spent' => $totalSpent,
                            'joined_at' => $user->created_at->format('M d, Y'),
                        ];
                    } catch (\Exception $e) {
                        Log::warning('User analytics error for user ' . $user->id . ': ' . $e->getMessage());
                        return [
                            'id' => $user->id,
                            'name' => 'Unknown',
                            'email' => 'Unknown',
                            'city' => 'Unknown',
                            'country' => 'Unknown',
                            'total_orders' => 0,
                            'total_spent' => 0,
                            'joined_at' => 'Unknown',
                        ];
                    }
                });

            // User registration trends (last 12 months)
            $userRegistrationTrends = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = $now->copy()->subMonths($i);
                try {
                    $userRegistrationTrends[] = [
                        'month' => $date->format('M Y'),
                        'users' => User::whereMonth('created_at', $date->month)
                            ->whereYear('created_at', $date->year)
                            ->count(),
                    ];
                } catch (\Exception $e) {
                    Log::warning('User registration trends error for month ' . $date->format('M Y') . ': ' . $e->getMessage());
                    $userRegistrationTrends[] = [
                        'month' => $date->format('M Y'),
                        'users' => 0,
                    ];
                }
            }

            return [
                'totalUsers' => $totalUsers,
                'monthlyNewUsers' => $monthlyNewUsers,
                'userGrowthRate' => $userGrowthRate,
                'activeUsers' => $activeUsers,
                'topUsers' => $topUsers,
                'userRegistrationTrends' => $userRegistrationTrends,
            ];

        } catch (\Exception $e) {
            Log::error('User Analytics Error: ' . $e->getMessage());
            return [
                'totalUsers' => 0,
                'monthlyNewUsers' => 0,
                'userGrowthRate' => 0,
                'activeUsers' => 0,
                'topUsers' => [],
                'userRegistrationTrends' => [],
            ];
        }
    }

    /**
     * Get performance metrics and KPIs.
     */
    private function getPerformanceMetrics(Carbon $now): array
    {
        try {
            // Conversion rates
            $totalVisitors = Event::sum('view_count') ?: 1;
            $totalTicketsSold = Order::where('payment_status', 'paid')->sum('quantity');
            $conversionRate = ($totalTicketsSold / $totalVisitors) * 100;

            // Average order value
            $totalOrders = Order::where('payment_status', 'paid')->count();
            $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
            $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

            // Customer lifetime value
            $totalCustomers = User::whereHas('orders', function ($query) {
                $query->where('payment_status', 'paid');
        })->count();
            $customerLifetimeValue = $totalCustomers > 0 ? $totalRevenue / $totalCustomers : 0;

            // Revenue per event
            $totalEvents = Event::count();
            $revenuePerEvent = $totalEvents > 0 ? $totalRevenue / $totalEvents : 0;

            // Ticket utilization rate
            $totalTicketsAvailable = Ticket::sum('quantity');
            $ticketUtilizationRate = $totalTicketsAvailable > 0 ? ($totalTicketsSold / $totalTicketsAvailable) * 100 : 0;

            return [
                'conversionRate' => round($conversionRate, 2),
                'averageOrderValue' => round($averageOrderValue, 2),
                'customerLifetimeValue' => round($customerLifetimeValue, 2),
                'revenuePerEvent' => round($revenuePerEvent, 2),
                'ticketUtilizationRate' => round($ticketUtilizationRate, 2),
            ];

        } catch (\Exception $e) {
            Log::error('Performance Metrics Error: ' . $e->getMessage());
            return [
                'conversionRate' => 0,
                'averageOrderValue' => 0,
                'customerLifetimeValue' => 0,
                'revenuePerEvent' => 0,
                'ticketUtilizationRate' => 0,
            ];
        }
    }

    /**
     * Get trend analysis data.
     */
    private function getTrendAnalysis(Carbon $now): array
    {
        try {
            // Monthly trends for last 12 months
            $monthlyTrends = [];
        for ($i = 11; $i >= 0; $i--) {
                $date = $now->copy()->subMonths($i);
                try {
                    $monthlyTrends[] = [
                'month' => $date->format('M'),
                        'year' => $date->format('Y'),
                'revenue' => Order::where('payment_status', 'paid')
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('total'),
                'orders' => Order::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                'events' => Event::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                        'users' => User::whereMonth('created_at', $date->month)
                            ->whereYear('created_at', $date->year)
                            ->count(),
                    ];
                } catch (\Exception $e) {
                    Log::warning('Monthly trends error for month ' . $date->format('M Y') . ': ' . $e->getMessage());
                    $monthlyTrends[] = [
                        'month' => $date->format('M'),
                        'year' => $date->format('Y'),
                        'revenue' => 0,
                        'orders' => 0,
                        'events' => 0,
                        'users' => 0,
                    ];
                }
            }

            // Weekly trends for last 8 weeks
            $weeklyTrends = [];
            for ($i = 7; $i >= 0; $i--) {
                $startOfWeek = $now->copy()->subWeeks($i)->startOfWeek();
                $endOfWeek = $now->copy()->subWeeks($i)->endOfWeek();

                try {
                    $weeklyTrends[] = [
                        'week' => $startOfWeek->format('M d') . ' - ' . $endOfWeek->format('M d'),
                        'revenue' => Order::where('payment_status', 'paid')
                            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                            ->sum('total'),
                        'orders' => Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count(),
                        'events' => Event::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count(),
                    ];
                } catch (\Exception $e) {
                    Log::warning('Weekly trends error for week ' . $startOfWeek->format('M d') . ': ' . $e->getMessage());
                    $weeklyTrends[] = [
                        'week' => $startOfWeek->format('M d') . ' - ' . $endOfWeek->format('M d'),
                        'revenue' => 0,
                        'orders' => 0,
                        'events' => 0,
                    ];
                }
            }

            return [
                'monthlyTrends' => $monthlyTrends,
                'weeklyTrends' => $weeklyTrends,
            ];

        } catch (\Exception $e) {
                Log::error('Trend Analysis Error: ' . $e->getMessage());
            return [
                'monthlyTrends' => [],
                'weeklyTrends' => [],
            ];
        }
    }

    /**
     * Get geographic analytics.
     */
    private function getGeographicAnalytics(): array
    {
        try {
            // City statistics with revenue
            $cityStats = City::withCount(['events', 'users'])
                ->with(['country'])
                ->having('events_count', '>', 0)
                ->orderBy('events_count', 'desc')
                ->limit(15)
            ->get()
                ->map(function ($city) {
                    try {
                        $revenue = Event::where('city_id', $city->id)
                            ->join('orders', 'events.id', '=', 'orders.event_id')
                            ->where('orders.payment_status', 'paid')
                            ->sum('orders.total');

                        return [
                            'id' => $city->id,
                            'name' => $city->name,
                            'country' => $city->country->name ?? 'Unknown',
                            'events_count' => $city->events_count,
                            'users_count' => $city->users_count,
                            'revenue' => $revenue,
                        ];
                    } catch (\Exception $e) {
                        Log::warning('City analytics error for city ' . $city->id . ': ' . $e->getMessage());
                        return [
                            'id' => $city->id,
                            'name' => $city->name ?? 'Unknown',
                            'country' => 'Unknown',
                            'events_count' => 0,
                            'users_count' => 0,
                            'revenue' => 0,
                        ];
                    }
                });

            // Country statistics
            $countryStats = DB::table('countries')
                ->join('cities', 'countries.id', '=', 'cities.country_id')
                ->join('events', 'cities.id', '=', 'events.city_id')
                ->select('countries.name', DB::raw('count(events.id) as events_count'))
                ->groupBy('countries.id', 'countries.name')
                ->orderBy('events_count', 'desc')
            ->limit(10)
            ->get();

            return [
                'cityStats' => $cityStats,
                'countryStats' => $countryStats,
            ];

        } catch (\Exception $e) {
            Log::error('Geographic Analytics Error: ' . $e->getMessage());
            return [
                'cityStats' => [],
                'countryStats' => [],
            ];
        }
    }

    /**
     * Get time-based analytics.
     */
    private function getTimeBasedAnalytics(Carbon $now): array
    {
        try {
            // Daily statistics for last 30 days
            $dailyStats = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = $now->copy()->subDays($i);
                try {
                    $dailyStats[] = [
                'day' => $date->format('D'),
                'date' => $date->format('d'),
                        'full_date' => $date->format('Y-m-d'),
                'orders' => Order::whereDate('created_at', $date)->count(),
                'revenue' => Order::where('payment_status', 'paid')
                    ->whereDate('created_at', $date)
                    ->sum('total'),
                'users' => User::whereDate('created_at', $date)->count(),
                        'events' => Event::whereDate('created_at', $date)->count(),
                    ];
                } catch (\Exception $e) {
                    Log::warning('Daily stats error for date ' . $date->format('Y-m-d') . ': ' . $e->getMessage());
                    $dailyStats[] = [
                        'day' => $date->format('D'),
                        'date' => $date->format('d'),
                        'full_date' => $date->format('Y-m-d'),
                        'orders' => 0,
                        'revenue' => 0,
                        'users' => 0,
                        'events' => 0,
                    ];
                }
            }

            // Hourly statistics for today
            $hourlyStats = [];
            for ($i = 23; $i >= 0; $i--) {
                $hour = $now->copy()->subHours($i);
                $startOfHour = $hour->copy()->startOfHour();
                $endOfHour = $hour->copy()->endOfHour();

                try {
                    $hourlyStats[] = [
                        'hour' => $hour->format('H:00'),
                        'orders' => Order::whereBetween('created_at', [$startOfHour, $endOfHour])
                            ->count(),
                        'revenue' => Order::where('payment_status', 'paid')
                            ->whereBetween('created_at', [$startOfHour, $endOfHour])
                            ->sum('total'),
                    ];
                } catch (\Exception $e) {
                    Log::warning('Hourly stats error for hour ' . $hour->format('H:00') . ': ' . $e->getMessage());
                    $hourlyStats[] = [
                        'hour' => $hour->format('H:00'),
                        'orders' => 0,
                        'revenue' => 0,
                    ];
                }
            }

            return [
                'dailyStats' => $dailyStats,
                'hourlyStats' => $hourlyStats,
            ];

        } catch (\Exception $e) {
            Log::error('Time-based Analytics Error: ' . $e->getMessage());
            return [
                'dailyStats' => [],
                'hourlyStats' => [],
            ];
        }
    }

    /**
     * Get advanced charts data.
     */
    private function getAdvancedChartsData(Carbon $now): array
    {
        try {
            // Recent orders with full details
            $recentOrders = Order::with(['user', 'event', 'ticket'])
                ->orderBy('created_at', 'desc')
                ->limit(15)
                ->get()
                ->map(function ($order) {
                    try {
                        return [
                            'id' => $order->id,
                            'order_number' => $order->order_number ?? '#' . $order->id,
                            'user_name' => trim(($order->user->first_name ?? '') . ' ' . ($order->user->last_name ?? '')),
                            'user_email' => $order->user->email ?? 'Unknown',
                            'event_name' => $order->event->name ?? 'Unknown Event',
                            'quantity' => $order->quantity ?? 0,
                            'total' => $order->total ?? 0,
                            'payment_status' => $order->payment_status ?? 'unknown',
                            'payment_method' => $order->payment_method ?? 'Unknown',
                            'created_at' => $order->created_at->format('M d, Y H:i'),
                        ];
                    } catch (\Exception $e) {
                        Log::warning('Recent orders error for order ' . $order->id . ': ' . $e->getMessage());
                        return [
                            'id' => $order->id,
                            'order_number' => '#' . $order->id,
                            'user_name' => 'Unknown',
                            'user_email' => 'Unknown',
                            'event_name' => 'Unknown Event',
                            'quantity' => 0,
                            'total' => 0,
                            'payment_status' => 'unknown',
                            'payment_method' => 'Unknown',
                            'created_at' => 'Unknown',
                        ];
                    }
                });

            // Payment status distribution
            $paymentStatusDistribution = Order::select('payment_status', DB::raw('count(*) as count'))
                ->groupBy('payment_status')
                ->get()
                ->map(function ($item) {
                    $status = $item->payment_status ?? 'unknown';
                    return [
                        'status' => ucfirst($status),
                        'count' => $item->count ?? 0,
                    ];
                });

            // Event status distribution
            $eventStatusDistribution = [
                'active' => Event::where('is_active', true)->count(),
                'inactive' => Event::where('is_active', false)->count(),
            ];

            return [
                'recentOrders' => $recentOrders,
                'paymentStatusDistribution' => $paymentStatusDistribution,
                'eventStatusDistribution' => $eventStatusDistribution,
            ];

        } catch (\Exception $e) {
            Log::error('Advanced Charts Data Error: ' . $e->getMessage());
            return [
                'recentOrders' => [],
                'paymentStatusDistribution' => [],
                'eventStatusDistribution' => [],
            ];
        }
    }

    /**
     * Calculate growth rate percentage.
     */
    private function calculateGrowthRate(float $current, float $previous): float
    {
        try {
            if ($previous == 0) {
                return $current > 0 ? 100 : 0;
            }

            return round((($current - $previous) / $previous) * 100, 2);
        } catch (\Exception $e) {
            Log::warning('Growth rate calculation error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Advanced search functionality for dashboard
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Search query is required'
            ]);
        }

        $results = [
            'events' => [],
            'users' => [],
            'companies' => [],
            'orders' => [],
            'categories' => [],
            'cities' => [],
            'statistics' => []
        ];

            // Search Events
            try {
            $events = Event::with(['company', 'category', 'city'])
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                        ->orWhere('description', 'LIKE', "%{$query}%");
                })
                ->limit(10)
                ->get()
                ->map(function ($event) {
                        try {
                    $soldTickets = $event->orders()->where('payment_status', 'paid')->sum('quantity') ?? 0;
                    $revenue = $event->orders()->where('payment_status', 'paid')->sum('total') ?? 0;

                    return [
                        'id' => $event->id,
                        'name' => $event->name,
                        'company' => $event->company->company_name ?? $event->company->first_name ?? 'Unknown',
                        'category' => $event->category->name ?? 'Unknown',
                        'city' => $event->city->name ?? 'Unknown',
                        'status' => $event->is_active ? 'Active' : 'Inactive',
                        'sold_tickets' => $soldTickets,
                        'revenue' => $revenue,
                        'views' => $event->view_count ?? 0,
                        'created_at' => $event->created_at->format('M d, Y'),
                        'url' => route('admin.events.show', $event->id)
                    ];
                        } catch (\Exception $e) {
                            Log::warning('Event search mapping error for event ' . $event->id . ': ' . $e->getMessage());
                            return [
                                'id' => $event->id,
                                'name' => $event->name ?? 'Unknown',
                                'company' => 'Unknown',
                                'category' => 'Unknown',
                                'city' => 'Unknown',
                                'status' => 'Unknown',
                                'sold_tickets' => 0,
                                'revenue' => 0,
                                'views' => 0,
                                'created_at' => 'Unknown',
                                'url' => '#'
                            ];
                        }
                });

            // Search Users
            try {
            $users = User::where(function ($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                    ->orWhere('last_name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%");
            })
                ->with(['city', 'country'])
                ->limit(10)
                ->get()
                ->map(function ($user) {
                        try {
                    $totalOrders = $user->orders()->count() ?? 0;
                    $totalSpent = $user->orders()->where('payment_status', 'paid')->sum('total') ?? 0;

                    return [
                        'id' => $user->id,
                        'name' => trim($user->first_name . ' ' . $user->last_name),
                        'email' => $user->email,
                        'city' => $user->city->name ?? 'Unknown',
                        'country' => $user->country->name ?? 'Unknown',
                        'total_orders' => $totalOrders,
                        'total_spent' => $totalSpent,
                        'joined_at' => $user->created_at->format('M d, Y'),
                        'url' => route('admin.users.show', $user->id)
                    ];
                        } catch (\Exception $e) {
                            Log::warning('User search mapping error for user ' . $user->id . ': ' . $e->getMessage());
                            return [
                                'id' => $user->id,
                                'name' => 'Unknown',
                                'email' => 'Unknown',
                                'city' => 'Unknown',
                                'country' => 'Unknown',
                                'total_orders' => 0,
                                'total_spent' => 0,
                                'joined_at' => 'Unknown',
                                'url' => '#'
                            ];
                        }
                    });
            } catch (\Exception $e) {
                Log::warning('User search error: ' . $e->getMessage());
                $users = collect();
            }

            // Search Companies
            try {
            $companies = Company::where(function ($q) use ($query) {
                $q->where('company_name', 'LIKE', "%{$query}%")
                    ->orWhere('first_name', 'LIKE', "%{$query}%")
                    ->orWhere('last_name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%");
            })
                ->with(['city', 'country'])
                ->limit(10)
                ->get()
                ->map(function ($company) {
                        try {
                    $totalEvents = $company->events()->count() ?? 0;
                    $activeEvents = $company->events()->where('is_active', true)->count() ?? 0;

                    // Calculate revenue more safely
                    $totalRevenue = 0;
                    try {
                        $totalRevenue = $company->events()
                            ->join('orders', 'events.id', '=', 'orders.event_id')
                            ->where('orders.payment_status', 'paid')
                            ->sum('orders.total') ?? 0;
                    } catch (\Exception $e) {
                                Log::warning('Company revenue calculation error: ' . $e->getMessage());
                        $totalRevenue = 0;
                    }

                    return [
                        'id' => $company->id,
                        'name' => $company->company_name ?? trim($company->first_name . ' ' . $company->last_name),
                        'email' => $company->email,
                        'city' => $company->city->name ?? 'Unknown',
                        'country' => $company->country->name ?? 'Unknown',
                        'total_events' => $totalEvents,
                        'active_events' => $activeEvents,
                        'total_revenue' => $totalRevenue,
                        'joined_at' => $company->created_at->format('M d, Y'),
                        'url' => route('admin.companies.show', $company->id)
                    ];
                        } catch (\Exception $e) {
                            Log::warning('Company search mapping error for company ' . $company->id . ': ' . $e->getMessage());
                            return [
                                'id' => $company->id,
                                'name' => 'Unknown',
                                'email' => 'Unknown',
                                'city' => 'Unknown',
                                'country' => 'Unknown',
                                'total_events' => 0,
                                'active_events' => 0,
                                'total_revenue' => 0,
                                'joined_at' => 'Unknown',
                                'url' => '#'
                            ];
                        }
                    });
            } catch (\Exception $e) {
                Log::warning('Company search error: ' . $e->getMessage());
                $companies = collect();
            }

            // Search Orders
            try {
            $orders = Order::with(['user', 'event'])
                ->where(function ($q) use ($query) {
                    $q->whereHas('user', function ($subQ) use ($query) {
                        $subQ->where('first_name', 'LIKE', "%{$query}%")
                            ->orWhere('last_name', 'LIKE', "%{$query}%")
                            ->orWhere('email', 'LIKE', "%{$query}%");
                    })
                        ->orWhereHas('event', function ($subQ) use ($query) {
                            $subQ->where('name', 'LIKE', "%{$query}%");
                        })
                        ->orWhere('order_number', 'LIKE', "%{$query}%")
                        ->orWhere('id', 'LIKE', "%{$query}%");
                })
                ->limit(10)
                ->get()
                ->map(function ($order) {
                        try {
                    return [
                        'id' => $order->id,
                        'order_number' => $order->order_number ?? '#' . $order->id,
                        'user_name' => trim(($order->user->first_name ?? '') . ' ' . ($order->user->last_name ?? '')),
                        'user_email' => $order->user->email ?? 'Unknown',
                        'event_name' => $order->event->name ?? 'Unknown Event',
                        'quantity' => $order->quantity ?? 0,
                        'total' => $order->total ?? 0,
                        'payment_status' => $order->payment_status ?? 'unknown',
                        'payment_method' => $order->payment_method ?? 'Unknown',
                        'created_at' => $order->created_at->format('M d, Y H:i'),
                        'url' => '#'
                    ];
                        } catch (\Exception $e) {
                            Log::warning('Order search mapping error for order ' . $order->id . ': ' . $e->getMessage());
                            return [
                                'id' => $order->id,
                                'order_number' => '#' . $order->id,
                                'user_name' => 'Unknown',
                                'user_email' => 'Unknown',
                                'event_name' => 'Unknown Event',
                                'quantity' => 0,
                                'total' => 0,
                                'payment_status' => 'unknown',
                                'payment_method' => 'Unknown',
                                'created_at' => 'Unknown',
                                'url' => '#'
                            ];
                        }
                    });
            } catch (\Exception $e) {
                Log::warning('Order search error: ' . $e->getMessage());
                $orders = collect();
            }

            // Search Categories
            try {
            $categories = EventCategory::where('name', 'LIKE', "%{$query}%")
                ->withCount('events')
                ->limit(10)
                ->get()
                ->map(function ($category) {
                        try {
                    $totalRevenue = 0;
                    try {
                        $totalRevenue = Event::where('category_id', $category->id)
                            ->join('orders', 'events.id', '=', 'orders.event_id')
                            ->where('orders.payment_status', 'paid')
                            ->sum('orders.total') ?? 0;
                    } catch (\Exception $e) {
                                Log::warning('Category revenue calculation error: ' . $e->getMessage());
                        $totalRevenue = 0;
                    }

                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'events_count' => $category->events_count ?? 0,
                        'total_revenue' => $totalRevenue,
                        'url' => route('admin.event_categories.show', $category->id)
                    ];
                        } catch (\Exception $e) {
                            Log::warning('Category search mapping error for category ' . $category->id . ': ' . $e->getMessage());
                            return [
                                'id' => $category->id,
                                'name' => 'Unknown',
                                'events_count' => 0,
                                'total_revenue' => 0,
                                'url' => '#'
                            ];
                        }
                    });
            } catch (\Exception $e) {
                Log::warning('Category search error: ' . $e->getMessage());
                $categories = collect();
            }

            // Search Cities
            try {
            $cities = City::where('name', 'LIKE', "%{$query}%")
                ->withCount(['events', 'users'])
                ->with('country')
                ->limit(10)
                ->get()
                ->map(function ($city) {
                        try {
                    return [
                        'id' => $city->id,
                        'name' => $city->name,
                        'country' => $city->country->name ?? 'Unknown',
                        'events_count' => $city->events_count ?? 0,
                        'users_count' => $city->users_count ?? 0,
                        'url' => route('admin.cities.show', $city->id)
                    ];
                        } catch (\Exception $e) {
                            Log::warning('City search mapping error for city ' . $city->id . ': ' . $e->getMessage());
                            return [
                                'id' => $city->id,
                                'name' => 'Unknown',
                                'country' => 'Unknown',
                                'events_count' => 0,
                                'users_count' => 0,
                                'url' => '#'
                            ];
                        }
                    });
            } catch (\Exception $e) {
                Log::warning('City search error: ' . $e->getMessage());
                $cities = collect();
            }

            // Generate Statistics based on search
            try {
            $statistics = [
                'total_events_found' => $events->count(),
                'total_users_found' => $users->count(),
                'total_companies_found' => $companies->count(),
                'total_orders_found' => $orders->count(),
                'total_revenue_from_search' => $events->sum('revenue') + $companies->sum('total_revenue'),
                'search_query' => $query,
                'search_time' => now()->format('Y-m-d H:i:s')
            ];

            $results = [
                'events' => $events,
                'users' => $users,
                'companies' => $companies,
                'orders' => $orders,
                'categories' => $categories,
                'cities' => $cities,
                'statistics' => $statistics
            ];

            return response()->json([
                'success' => true,
                'data' => $results,
                'message' => 'Search completed successfully'
            ]);

            } catch (\Exception $e) {
                Log::error('Search statistics generation error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Search failed. Please try again.',
                    'data' => []
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Search Error: ' . $e->getMessage());
            Log::error('Search Query: ' . $query);
            Log::error('Stack Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Search failed. Please try again.',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
