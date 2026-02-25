@extends('backend.partials.master')

@section('title', 'Dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                            <h2 class="mb-2 text-white">
                                <i class="ti ti-chart-line me-2"></i>
                                Welcome Back, {{ auth()->user()->first_name ?? 'Admin' }}!
                            </h2>
                            <p class="mb-0 text-white-50">
                                <i class="ti ti-calendar me-1"></i>
                                {{ now()->format('l, F j, Y') }} • Last updated: {{ now()->format('H:i') }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                            <button class="btn btn-light" onclick="window.location.reload()">
                                <i class="ti ti-refresh me-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                    </div>

    <!-- Key Performance Indicators -->
    <div class="row mb-4">
        <!-- Total Revenue -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="avatar flex-shrink-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="ti ti-currency-dollar text-white"></i>
                </div>
                        <div class="text-end">
                            <span class="badge {{ $revenueGrowth >= 0 ? 'bg-success' : 'bg-danger' }}">
                                <i class="ti ti-trending-{{ $revenueGrowth >= 0 ? 'up' : 'down' }}"></i>
                                {{ number_format(abs($revenueGrowth), 1) }}%
                            </span>
                    </div>
                </div>
                    <h3 class="mb-2 fw-bold">${{ number_format($totalRevenue / 1000, 1) }}K</h3>
                    <p class="mb-0 text-muted small">Total Revenue</p>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar" style="width: {{ min(($totalRevenue / 1000000) * 100, 100) }}%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
            </div>
        </div>
                </div>
            </div>

        <!-- Total Events -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="avatar flex-shrink-0" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                            <i class="ti ti-calendar-event text-white"></i>
                </div>
                        <div class="text-end">
                            <span class="badge bg-success">
                                <i class="ti ti-trending-up"></i>
                                {{ number_format($conversionRate, 1) }}%
                            </span>
                </div>
            </div>
                    <h3 class="mb-2 fw-bold">{{ number_format($totalEvents) }}</h3>
                    <p class="mb-0 text-muted small">Total Events</p>
                    <div class="mt-2 small">
                        <span class="text-success me-2">● {{ $activeEvents }} Active</span>
                        <span class="text-primary">● {{ $upcomingEvents }} Upcoming</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="avatar flex-shrink-0" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);">
                            <i class="ti ti-users text-white"></i>
                                </div>
                        <div class="text-end">
                            <span class="badge bg-warning text-dark">
                                <i class="ti ti-trending-up"></i>
                                {{ number_format($userGrowthRate, 1) }}%
                            </span>
                                            </div>
                                            </div>
                    <h3 class="mb-2 fw-bold">{{ number_format($totalUsers) }}</h3>
                    <p class="mb-0 text-muted small">Total Users</p>
                    <div class="mt-2 small">
                        <span class="text-success">● {{ number_format($activeUsers) }} Active</span>
                                        </div>
                                    </div>
                                    </div>
                                </div>

        <!-- Total Companies -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="avatar flex-shrink-0" style="background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);">
                            <i class="ti ti-building text-white"></i>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-danger">
                                <i class="ti ti-trending-up"></i>
                                +3.1%
                            </span>
                        </div>
                    </div>
                    <h3 class="mb-2 fw-bold">{{ number_format($totalCompanies) }}</h3>
                    <p class="mb-0 text-muted small">Total Companies</p>
                    <div class="mt-2 small">
                        <span class="text-muted">{{ number_format($totalEvents / max($totalCompanies, 1), 1) }} Events/Company</span>
                    </div>
                </div>
                            </div>
                        </div>
                                </div>

    <!-- Secondary Metrics -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <i class="ti ti-ticket" style="font-size: 2rem; color: #667eea;"></i>
                    <h4 class="mt-2 mb-1">{{ number_format($totalTicketsSold) }}</h4>
                    <small class="text-muted">Tickets Sold</small>
                        </div>
                    </div>
                                </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <i class="ti ti-shopping-cart" style="font-size: 2rem; color: #28a745;"></i>
                    <h4 class="mt-2 mb-1">{{ number_format($totalOrders) }}</h4>
                    <small class="text-muted">Total Orders</small>
                            </div>
                                    </div>
                                </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <i class="ti ti-credit-card" style="font-size: 2rem; color: #ffc107;"></i>
                    <h4 class="mt-2 mb-1">${{ number_format($averageOrderValue, 0) }}</h4>
                    <small class="text-muted">Avg Order Value</small>
                            </div>
                                </div>
                            </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <i class="ti ti-chart-bar" style="font-size: 2rem; color: #dc3545;"></i>
                    <h4 class="mt-2 mb-1">{{ number_format($conversionRate, 1) }}%</h4>
                    <small class="text-muted">Conversion Rate</small>
                        </div>
                            </div>
                        </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <i class="ti ti-trending-up" style="font-size: 2rem; color: #6f42c1;"></i>
                    <h4 class="mt-2 mb-1">{{ number_format($ticketUtilizationRate, 1) }}%</h4>
                    <small class="text-muted">Utilization Rate</small>
                    </div>
                </div>
            </div>
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-3">
                    <i class="ti ti-hourglass-high" style="font-size: 2rem; color: #17a2b8;"></i>
                    <h4 class="mt-2 mb-1">{{ number_format($pendingOrders) }}</h4>
                    <small class="text-muted">Pending Orders</small>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <!-- Revenue Trend -->
                <div class="col-lg-8 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                            <h5 class="card-title mb-1">Revenue & Orders Trend</h5>
                            <p class="text-muted small mb-0">Monthly performance over the last 12 months</p>
                                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary active" onclick="changeChartPeriod('monthly')">Monthly</button>
                            <button type="button" class="btn btn-outline-primary" onclick="changeChartPeriod('weekly')">Weekly</button>
                                    </div>
                                    </div>
                </div>
                <div class="card-body">
                    <div id="revenueTrendChart" style="height: 350px;"></div>
                </div>
                                </div>
                            </div>

        <!-- Event Status Distribution -->
                <div class="col-lg-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header border-0 pb-0">
                    <h5 class="card-title mb-1">Event Status</h5>
                    <p class="text-muted small mb-0">Current distribution</p>
                        </div>
                <div class="card-body">
                    <div id="eventStatusChart" style="height: 350px;"></div>
                </div>
                    </div>
                                        </div>
                                        </div>

    <!-- Top Events & Cities -->
    <div class="row mb-4">
        <!-- Top Performing Events -->
                <div class="col-lg-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header border-0 pb-0">
                    <h5 class="card-title mb-1">Top Performing Events</h5>
                    <p class="text-muted small mb-0">By revenue and ticket sales</p>
                        </div>
                <div class="card-body">
                    <div id="topEventsChart" style="height: 350px;"></div>
                </div>
                    </div>
                                    </div>

        <!-- Cities Distribution -->
                <div class="col-lg-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header border-0 pb-0">
                    <h5 class="card-title mb-1">Top Cities</h5>
                    <p class="text-muted small mb-0">Events and revenue by location</p>
                        </div>
                <div class="card-body">
                    <div id="citiesChart" style="height: 350px;"></div>
                    </div>
                                                        </div>
                                                    </div>
        </div>

    <!-- Categories & Recent Activity -->
    <div class="row mb-4">
        <!-- Event Categories -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header border-0 pb-0">
                    <h5 class="card-title mb-1">Event Categories</h5>
                    <p class="text-muted small mb-0">Distribution by category</p>
    </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th class="text-center">Events</th>
                                    <th class="text-end">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categoryStats ?? [] as $category)
                                @php
                                    $categoryName = is_object($category) ? ($category->name ?? 'N/A') : ($category['name'] ?? 'N/A');
                                    $eventsCount = is_object($category) ? ($category->events_count ?? 0) : ($category['events_count'] ?? 0);
                                    $revenue = is_object($category) ? ($category->revenue ?? 0) : ($category['revenue'] ?? 0);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-primary me-2">{{ substr($categoryName, 0, 1) }}</span>
                                            {{ $categoryName }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-label-primary">{{ $eventsCount }}</span>
                                    </td>
                                    <td class="text-end fw-bold">${{ number_format($revenue, 0) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">Recent Orders</h5>
                            <p class="text-muted small mb-0">Latest transactions</p>
                        </div>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Event</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(isset($recentOrders) ? $recentOrders : [] as $order)
                                @php
                                    $customer = optional($order)->customer;
                                    $event = optional($order)->event;
                                    $customerName = optional($customer)->first_name ?? 'Guest';
                                    $eventName = optional($event)->name ?? 'N/A';
                                    $paymentStatus = optional($order)->payment_status ?? 'pending';
                                    $statusColor = $paymentStatus === 'completed' ? 'success' : ($paymentStatus === 'pending' ? 'warning' : 'danger');
                                @endphp
                                <tr>
                                    <td><small class="text-muted">#{{ optional($order)->id }}</small></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ substr($customerName, 0, 1) }}
                                                </span>
                                            </div>
                                            <small>{{ $customerName }}</small>
                                        </div>
                                    </td>
                                    <td><small>{{ \Str::limit($eventName, 20) }}</small></td>
                                    <td class="text-end"><small class="fw-bold">${{ number_format(optional($order)->total ?? 0, 0) }}</small></td>
                                    <td class="text-center">
                                        <span class="badge bg-label-{{ $statusColor }} badge-sm">
                                            {{ ucfirst($paymentStatus) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No recent orders</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@latest/dist/apexcharts.min.js"></script>
    <script>
    // Analytics Data
        const analyticsData = {
            monthlyTrends: @json($monthlyTrends ?? []),
            weeklyTrends: @json($weeklyTrends ?? []),
            topEvents: @json($topEvents ?? []),
            categoryStats: @json($categoryStats ?? []),
            cityStats: @json($cityStats ?? []),
            eventStatusDistribution: @json($eventStatusDistribution ?? []),
    };

    // Chart instances
        let chartInstances = {};
    let currentPeriod = 'monthly';

        // Initialize all charts
    document.addEventListener('DOMContentLoaded', function () {
            createRevenueTrendChart();
            createEventStatusChart();
        createTopEventsChart();
        createCitiesChart();
    });

        // Revenue Trend Chart
        function createRevenueTrendChart() {
        const data = analyticsData.monthlyTrends;
        
            const options = {
                series: [{
                    name: 'Revenue',
                type: 'area',
                data: data.map(item => item.revenue || item['revenue'] || 0)
                }, {
                    name: 'Orders',
                    type: 'line',
                data: data.map(item => item.orders || item['orders'] || 0)
                }],
                chart: {
                height: 350,
                    type: 'line',
                toolbar: { show: true },
                zoom: { enabled: false }
            },
            colors: ['#667eea', '#28a745'],
                stroke: {
                width: [0, 3],
                    curve: 'smooth'
                },
                fill: {
                type: ['gradient', 'solid'],
                    gradient: {
                        shade: 'light',
                        type: 'vertical',
                        shadeIntensity: 0.5,
                    gradientToColors: ['#764ba2', '#20c997'],
                        opacityFrom: 0.8,
                    opacityTo: 0.2,
                    }
                },
                xaxis: {
                categories: data.map(item => item.month || item['month'] || 'N/A')
                },
                yaxis: [{
                title: { text: 'Revenue ($)' },
                    labels: {
                        formatter: function(value) {
                            return '$' + (value / 1000).toFixed(0) + 'K';
                        }
                    }
                }, {
                    opposite: true,
                title: { text: 'Orders' }
                }],
                legend: {
                    position: 'top',
                horizontalAlign: 'right'
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function (y, { seriesIndex }) {
                            if (seriesIndex === 0) {
                                return '$' + y.toLocaleString();
                            }
                        return y + ' orders';
                        }
                    }
                }
            };

            chartInstances.revenueTrend = new ApexCharts(document.querySelector("#revenueTrendChart"), options);
            chartInstances.revenueTrend.render();
        }

    // Event Status Chart
    function createEventStatusChart() {
        const data = analyticsData.eventStatusDistribution;
        
            const options = {
            series: Object.values(data),
                chart: {
                type: 'donut',
                height: 350
            },
            labels: Object.keys(data).map(key => key.charAt(0).toUpperCase() + key.slice(1)),
            colors: ['#28a745', '#ffc107', '#dc3545', '#17a2b8'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Events',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            },
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val.toFixed(0) + '%';
                }
            }
        };

        chartInstances.eventStatus = new ApexCharts(document.querySelector("#eventStatusChart"), options);
        chartInstances.eventStatus.render();
    }

    // Top Events Chart
    function createTopEventsChart() {
        const data = analyticsData.topEvents.slice(0, 8);
        
            const options = {
                series: [{
                    name: 'Revenue',
                data: data.map(event => event.total_revenue || event['total_revenue'] || 0)
                }, {
                name: 'Tickets',
                data: data.map(event => event.total_sales || event['total_sales'] || 0)
                }],
                chart: {
                    type: 'bar',
                height: 350
                },
                colors: ['#667eea', '#28a745'],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                    borderRadius: 5
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                categories: data.map(event => {
                    const name = event.name || event['name'] || 'N/A';
                    return name.length > 15 ? name.substring(0, 15) + '...' : name;
                }),
                labels: {
                    rotate: -45,
                        style: {
                        fontSize: '11px'
                    }
                        }
                    },
            yaxis: [{
                title: { text: 'Revenue ($)' },
                    labels: {
                        formatter: function(value) {
                            return '$' + (value / 1000).toFixed(0) + 'K';
                        }
                    }
                }, {
                    opposite: true,
                title: { text: 'Tickets' }
            }],
                tooltip: {
                    y: {
                        formatter: function (y, { seriesIndex }) {
                            if (seriesIndex === 0) {
                                return '$' + y.toLocaleString();
                            }
                        return y + ' tickets';
                        }
                    }
                }
            };

        chartInstances.topEvents = new ApexCharts(document.querySelector("#topEventsChart"), options);
        chartInstances.topEvents.render();
    }

    // Cities Chart
    function createCitiesChart() {
        const data = analyticsData.cityStats.slice(0, 10);
        
            const options = {
                series: [{
                    name: 'Events',
                data: data.map(city => city.events_count || city['events_count'] || 0)
                }, {
                    name: 'Revenue',
                data: data.map(city => (city.revenue || city['revenue'] || 0) / 1000)
                }],
                chart: {
                    type: 'bar',
                height: 350
                },
                colors: ['#667eea', '#28a745'],
                plotOptions: {
                    bar: {
                        horizontal: true,
                    borderRadius: 5
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                categories: data.map(city => city.name || city['name'] || 'N/A')
                },
                yaxis: [{
                title: { text: 'Events Count' }
                }, {
                    opposite: true,
                title: { text: 'Revenue (K$)' },
                    labels: {
                        formatter: function(value) {
                            return '$' + value.toFixed(0) + 'K';
                        }
                    }
                }],
                tooltip: {
                    y: {
                        formatter: function (y, { seriesIndex }) {
                            if (seriesIndex === 0) {
                                return y + ' events';
                            }
                        return '$' + y.toFixed(0) + 'K';
                        }
                    }
                }
            };

        chartInstances.cities = new ApexCharts(document.querySelector("#citiesChart"), options);
        chartInstances.cities.render();
    }

    // Change Chart Period
        function changeChartPeriod(period) {
        // Update button states
            document.querySelectorAll('.btn-group .btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
        currentPeriod = period;
        const data = period === 'monthly' ? analyticsData.monthlyTrends : analyticsData.weeklyTrends;
        const categories = period === 'monthly' 
            ? data.map(item => item.month || item['month'] || 'N/A') 
            : data.map(item => item.week || item['week'] || 'N/A');
        
        // Update chart
            if (chartInstances.revenueTrend) {
                chartInstances.revenueTrend.updateOptions({
                    series: [{
                        name: 'Revenue',
                    type: 'area',
                    data: data.map(item => item.revenue || item['revenue'] || 0)
                    }, {
                        name: 'Orders',
                        type: 'line',
                    data: data.map(item => item.orders || item['orders'] || 0)
                    }],
                    xaxis: {
                        categories: categories
                    }
                });
            }
        }
</script>
@endpush

@push('css')
<style>
    .avatar {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.5rem;
    }
    
    .avatar-sm {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }
    
    .avatar-initial {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        font-weight: 600;
    }
    
        .card {
        transition: all 0.3s ease;
        }

        .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
        font-size: 0.75rem;
            letter-spacing: 0.5px;
        color: #6c757d;
    }
    
    .badge-sm {
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
    }
    
    .bg-label-primary {
        background-color: rgba(102, 126, 234, 0.1) !important;
        color: #667eea !important;
    }
    
        .bg-label-success {
            background-color: rgba(40, 167, 69, 0.1) !important;
            color: #28a745 !important;
        }

        .bg-label-warning {
            background-color: rgba(255, 193, 7, 0.1) !important;
            color: #ffc107 !important;
        }

        .bg-label-danger {
            background-color: rgba(220, 53, 69, 0.1) !important;
            color: #dc3545 !important;
        }

    .progress {
        border-radius: 10px;
        overflow: hidden;
    }
    
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }
        }
    </style>
@endpush
