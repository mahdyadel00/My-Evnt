<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\SearchEventsRequest;
use App\Http\Requests\GuestSurveyRequest;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventFavourite;
use App\Models\EventInterested;
use App\Models\FormServay;
use App\Models\Setting;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $setting = Setting::first();

        // Start with base query - only show events that haven't ended
        $query = Event::active()
            ->whereHas('eventDates', function ($q) {
                $q->where('end_date', '>', now()->toDateString());
            })
            ->latest();

        $events = $query->with([
            'city',
            'category',
            'tickets',
            'eventDates',
            'currency',
            'media' => function ($mediaQuery) {
                $mediaQuery->where('name', 'poster');
            }
        ])->paginate(12);

        $categories = EventCategory::where('parent_id', null)->get();
        $cities = City::get();
        $seo_title = __('Events Search & Filtration');

        return view('Frontend.events.index', compact('events', 'categories', 'setting', 'cities', 'seo_title'));
    }

    /**
     * Filteration page - display events with advanced filtering UI
     * Initial page load with featured/upcoming events
     */
    public function filteration(Request $request)
    {
        $setting = Setting::first();

        // Start with base query - only show events that haven't ended
        $query = Event::active()
            ->whereHas('eventDates', function ($q) {
                $q->where('end_date', '>', now()->toDateString());
            });

        // Load events with all necessary relationships
        $events = $query->with([
            'city',
            'category',
            'tickets',
            'eventDates',
            'currency',
            'media' => function ($mediaQuery) {
                $mediaQuery->where('name', 'poster');
            }
        ])->latest()->paginate(12);

        // Get filters data
        $categories = EventCategory::where('parent_id', null)->get();
        $cities = City::get();

        return view('Frontend.filteration_events', compact('events', 'categories', 'cities', 'setting'));
    }

    /**
     * AJAX search and filter events - handles all filters from frontend
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxSearch(Request $request)
    {
        try {
            // Build query with eager loading - only show events that haven't ended
            $query = Event::active()
                ->whereHas('eventDates', function ($q) {
                    $q->where('end_date', '>', now()->toDateString());
                })
                ->with([
                    'city',
                    'category',
                    'company',
                    'tickets',
                    'eventDates',
                    'media' => function ($mediaQuery) {
                        $mediaQuery->where('name', 'poster');
                    },
                    'currency'
                ]);

            // Apply search filter - searches in event name, description, summary, location, category, city, organized_by, company
            // Note: If city_id filter is applied, we skip searching in city name to avoid conflicts
            $hasCityFilter = $request->filled('city') || $request->filled('city_id');

            if ($request->filled('search')) {
                $searchTerm = trim($request->input('search'));
                if (!empty($searchTerm)) {
                    // Split search term into words for better matching
                    $searchWords = preg_split('/\s+/', $searchTerm);
                    $searchWords = array_filter($searchWords, function ($word) {
                        return strlen(trim($word)) > 0;
                    });

                    $query->where(function ($q) use ($searchWords, $hasCityFilter) {
                        foreach ($searchWords as $word) {
                            $q->where(function ($subQuery) use ($word, $hasCityFilter) {
                                $subQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereRaw('LOWER(summary) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereRaw('LOWER(location) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereRaw('LOWER(organized_by) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereHas('category', function ($catQuery) use ($word) {
                                        $catQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($word) . '%']);
                                    });

                                // Only search in city name if no city filter is applied
                                if (!$hasCityFilter) {
                                    $subQuery->orWhereHas('city', function ($cityQuery) use ($word) {
                                        $cityQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($word) . '%']);
                                    });
                                }

                                $subQuery->orWhere(function ($companyQuery) use ($word) {
                                    $companyQuery->whereHas('company', function ($q) use ($word) {
                                        $q->whereRaw('LOWER(company_name) LIKE ?', ['%' . strtolower($word) . '%'])
                                            ->orWhereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($word) . '%'])
                                            ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . strtolower($word) . '%']);
                                    });
                                });
                            });
                        }
                    });
                }
            }
            // Apply category filter (single or multiple)
            if ($request->filled('category')) {
                $categoryIds = $request->input('category');
                if (is_array($categoryIds)) {
                    $query->whereIn('category_id', $categoryIds);
                } else {
                    $query->where('category_id', $categoryIds);
                }
            }

            // Apply city filter (single or multiple)
            if ($request->filled('city')) {
                $cityId = $request->input('city');
                if ($cityId && $cityId !== '') {
                    $query->where('city_id', $cityId);
                }
            } elseif ($request->filled('city_id')) {
                $cityIds = $request->input('city_id');
                if (is_array($cityIds)) {
                    $query->whereIn('city_id', $cityIds);
                } else {
                    $query->where('city_id', $cityIds);
                }
            }

            // Filter by location (text search in city name)
            if ($request->filled('location')) {
                $location = $request->input('location');
                $query->whereHas('city', function ($q) use ($location) {
                    $q->where('name', 'like', '%' . $location . '%');
                });
            }

            // Filter by single date
            if ($request->filled('date')) {
                $date = $request->input('date');
                $query->whereHas('eventDates', function ($q) use ($date) {
                    $q->whereDate('start_date', '<=', $date)
                        ->whereDate('end_date', '>=', $date);
                });
            }

            // Filter by date range
            if ($request->filled('start_date')) {
                $startDate = $request->input('start_date');
                $query->whereHas('eventDates', function ($q) use ($startDate) {
                    $q->whereDate('start_date', '>=', $startDate);
                });
            }

            if ($request->filled('end_date')) {
                $endDate = $request->input('end_date');
                $query->whereHas('eventDates', function ($q) use ($endDate) {
                    $q->whereDate('end_date', '<=', $endDate);
                });
            }

            // Filter by format (online/offline) — 1 = online, 0 = offline
            if ($request->filled('format')) {
                $query->where('format', $request->input('format'));
            }

            // Filter by price range
            if ($request->filled('min_price') || $request->filled('max_price')) {
                $minPrice = $request->input('min_price');
                $maxPrice = $request->input('max_price');
                $query->whereHas('tickets', function ($q) use ($minPrice, $maxPrice) {
                    if (!is_null($minPrice) && !is_null($maxPrice)) {
                        $q->whereBetween('price', [$minPrice, $maxPrice]);
                    } elseif (!is_null($minPrice)) {
                        $q->where('price', '>=', $minPrice);
                    } elseif (!is_null($maxPrice)) {
                        $q->where('price', '<=', $maxPrice);
                    }
                });
            }

            // Apply price filter (free/paid)
            if ($request->filled('price')) {
                if ($request->input('price') === 'free') {
                    $query->whereHas('tickets', function ($q) {
                        $q->where('price', 0);
                    });
                } elseif ($request->input('price') === 'paid') {
                    $query->whereHas('tickets', function ($q) {
                        $q->where('price', '>', 0);
                    });
                }
            }

            // Filter by ticket type
            if ($request->filled('type')) {
                $types = $request->input('type');
                if (is_array($types)) {
                    $query->whereHas('tickets', function ($q) use ($types) {
                        $q->whereIn('type', $types);
                    });
                } else {
                    $query->whereHas('tickets', function ($q) use ($types) {
                        $q->where('type', $types);
                    });
                }
            }

            // Order by latest and paginate
            $events = $query->latest()->paginate(12);

            // Debug: Log search results
            if (config('app.debug')) {
                // Count events with search term only (no city filter) - using same logic as main query
                $searchOnlyQuery = Event::active()
                    ->whereHas('eventDates', function ($q) {
                        $q->where('end_date', '>', now()->toDateString());
                    });
                if ($request->filled('search')) {
                    $searchTerm = trim($request->input('search'));
                    if (!empty($searchTerm)) {
                        // Split search term into words for better matching
                        $searchWords = preg_split('/\s+/', $searchTerm);
                        $searchWords = array_filter($searchWords, function ($word) {
                            return strlen(trim($word)) > 0;
                        });

                        $searchOnlyQuery->where(function ($q) use ($searchWords) {
                            foreach ($searchWords as $word) {
                                $q->where(function ($subQuery) use ($word) {
                                    $subQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($word) . '%'])
                                        ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($word) . '%'])
                                        ->orWhereRaw('LOWER(summary) LIKE ?', ['%' . strtolower($word) . '%'])
                                        ->orWhereRaw('LOWER(location) LIKE ?', ['%' . strtolower($word) . '%'])
                                        ->orWhereRaw('LOWER(organized_by) LIKE ?', ['%' . strtolower($word) . '%'])
                                        ->orWhereHas('category', function ($catQuery) use ($word) {
                                            $catQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($word) . '%']);
                                        })
                                        ->orWhereHas('city', function ($cityQuery) use ($word) {
                                            $cityQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($word) . '%']);
                                        })
                                        ->orWhere(function ($companyQuery) use ($word) {
                                            $companyQuery->whereHas('company', function ($q) use ($word) {
                                                $q->whereRaw('LOWER(company_name) LIKE ?', ['%' . strtolower($word) . '%'])
                                                    ->orWhereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($word) . '%'])
                                                    ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . strtolower($word) . '%']);
                                            });
                                        });
                                });
                            }
                        });
                    }
                }
                $searchOnlyCount = $searchOnlyQuery->count();

                // Count events in city_id only (no search)
                $cityOnlyQuery = Event::active()
                    ->whereHas('eventDates', function ($q) {
                        $q->where('end_date', '>', now()->toDateString());
                    });
                if ($request->filled('city_id')) {
                    $cityIds = $request->input('city_id');
                    if (is_array($cityIds)) {
                        $cityOnlyQuery->whereIn('city_id', $cityIds);
                    } else {
                        $cityOnlyQuery->where('city_id', $cityIds);
                    }
                }
                $cityOnlyCount = $cityOnlyQuery->count();

                // Test: Simple search without word splitting
                $simpleSearchCount = 0;
                if ($request->filled('search')) {
                    $searchTerm = trim($request->input('search'));
                    if (!empty($searchTerm)) {
                        $simpleQuery = Event::active()
                            ->whereHas('eventDates', function ($q) {
                                $q->where('end_date', '>', now()->toDateString());
                            })
                            ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
                        $simpleSearchCount = $simpleQuery->count();
                    }
                }

                Log::info('AJAX Search Debug', [
                    'search_term' => $request->input('search'),
                    'city_id' => $request->input('city_id'),
                    'total_results' => $events->total(),
                    'search_only_count' => $searchOnlyCount,
                    'city_only_count' => $cityOnlyCount,
                    'simple_search_count' => $simpleSearchCount,
                    'has_city_filter' => $hasCityFilter,
                    'search_words' => $request->filled('search') ? preg_split('/\s+/', trim($request->input('search'))) : [],
                    'filters' => $request->all()
                ]);
            }

            // Return JSON response (use same cards partial as events index for consistency)
            return response()->json([
                'success' => true,
                'message' => 'Events retrieved successfully',
                'html' => view('Frontend.events.partials.cards', compact('events'))->render(),
                'total' => $events->total(),
                'pagination' => (string) $events->links('pagination::bootstrap-4'),
            ]);

        } catch (\Exception $e) {
            Log::error('AJAX Search error: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());

            return response()->json([
                'success' => false,
                'message' => 'Error loading events. Please try again.',
                'html' => '<div class="col-12 text-center"><div class="alert alert-danger">Error loading events. Please try again.</div></div>',
                'total' => 0,
                'pagination' => ''
            ], 500);
        }
    }

    /**
     * Search and filter events based on multiple criteria
     *
     * @param SearchEventsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(SearchEventsRequest $request)
    {
        try {
            // Get validated data
            $validated = $request->validated();

            // Build query with eager loading - only show events that haven't ended
            $query = Event::active()
                ->whereHas('eventDates', function ($q) {
                    $q->where('end_date', '>', now()->toDateString());
                })
                ->with([
                    'city',
                    'category',
                    'tickets',
                    'eventDates',
                    'media' => function ($mediaQuery) {
                        $mediaQuery->where('name', 'poster');
                    },
                    'currency'
                ]);

            // Apply search filter - searches in event name, description, summary, location, category, city, organized_by, company
            if (!empty($validated['search'])) {
                $searchTerm = trim($validated['search']);
                if (!empty($searchTerm)) {
                    // Split search term into words for better matching
                    $searchWords = preg_split('/\s+/', $searchTerm);
                    $searchWords = array_filter($searchWords, function ($word) {
                        return strlen(trim($word)) > 0;
                    });

                    $query->where(function ($q) use ($searchWords) {
                        foreach ($searchWords as $word) {
                            $q->where(function ($subQuery) use ($word) {
                                $subQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereRaw('LOWER(summary) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereRaw('LOWER(location) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereRaw('LOWER(organized_by) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereHas('category', function ($catQuery) use ($word) {
                                        $catQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($word) . '%']);
                                    })
                                    ->orWhereHas('city', function ($cityQuery) use ($word) {
                                        $cityQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($word) . '%']);
                                    })
                                    ->orWhereHas('company', function ($companyQuery) use ($word) {
                                        $companyQuery->whereRaw('LOWER(company_name) LIKE ?', ['%' . strtolower($word) . '%'])
                                            ->orWhereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($word) . '%'])
                                            ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . strtolower($word) . '%']);
                                    });
                            });
                        }
                    });
                }
            }

            // Apply category filter (support both validated and direct request)
            if (!empty($validated['category'])) {
                $query->where('category_id', $validated['category']);
            } elseif ($request->filled('category')) {
                $categoryIds = $request->input('category');
                if (is_array($categoryIds)) {
                    $query->whereIn('category_id', $categoryIds);
                } else {
                    $query->where('category_id', $categoryIds);
                }
            }

            // Apply city filter (support both validated and direct request)
            if (!empty($validated['city'])) {
                $query->where('city_id', $validated['city']);
            } elseif ($request->filled('city')) {
                $cityId = $request->input('city');
                if ($cityId && $cityId !== '') {
                    $query->where('city_id', $cityId);
                }
            } elseif ($request->filled('city_id')) {
                $cityIds = $request->input('city_id');
                if (is_array($cityIds)) {
                    $query->whereIn('city_id', $cityIds);
                } else {
                    $query->where('city_id', $cityIds);
                }
            }

            // Filter by date (single date)
            if (!empty($validated['date'])) {
                $date = $validated['date'];
                $query->whereHas('eventDates', function ($q) use ($date) {
                    $q->whereDate('start_date', '<=', $date)
                        ->whereDate('end_date', '>=', $date);
                });
            } elseif ($request->filled('date')) {
                $date = $request->input('date');
                $query->whereHas('eventDates', function ($q) use ($date) {
                    $q->whereDate('start_date', '<=', $date)
                        ->whereDate('end_date', '>=', $date);
                });
            }

            // Apply price filter
            if (!empty($validated['price'])) {
                if ($validated['price'] === 'free') {
                    $query->whereHas('tickets', function ($q) {
                        $q->where('price', 0);
                    });
                } elseif ($validated['price'] === 'paid') {
                    $query->whereHas('tickets', function ($q) {
                        $q->where('price', '>', 0);
                    });
                }
            } elseif ($request->filled('price')) {
                $priceValue = $request->input('price');
                if ($priceValue === 'free') {
                    $query->whereHas('tickets', function ($q) {
                        $q->where('price', 0);
                    });
                } elseif ($priceValue === 'paid') {
                    $query->whereHas('tickets', function ($q) {
                        $q->where('price', '>', 0);
                    });
                }
            }

            // Order by latest and paginate
            $events = $query->latest()->paginate(12);

            // Return JSON response
            return response()->json([
                'success' => true,
                'message' => 'Events retrieved successfully',
                'html' => view('Frontend.events.partials.cards_new_style', compact('events'))->render(),
                'total' => $events->total(),
                'pagination' => (string) $events->links('pagination::bootstrap-4'),
                'filters_applied' => array_filter($validated, fn($value) => !is_null($value) && $value !== '')
            ]);

        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());

            return response()->json([
                'success' => false,
                'message' => 'Error loading events. Please try again.',
                'html' => '<div class="col-12 text-center"><div class="alert alert-danger">Error loading events. Please try again.</div></div>',
                'total' => 0,
                'pagination' => ''
            ], 500);
        }
    }

    public function show(Event $event)
    {
        try {
            DB::beginTransaction();
            $event = Event::active()
                ->with(['company.followers', 'media', 'category', 'city', 'eventDates', 'tickets', 'currency'])
                ->withCount('interested')
                ->where('uuid', $event->uuid)
                ->first();
            //check in event table if event is not found
            if (!$event) {
                session()->flash('error', 'Event not found');
                return redirect()->back()->with('error', 'Event not found');
            }
            if ($event) {
                $event->increment('view_count');
            }

            $interestedCount = (int) $event->interested_count;
            $isInterested = auth()->check() && $event->interested()->where('user_id', auth()->id())->exists();

            $setting = Setting::first();
            $event_related = Event::active()->where('category_id', $event->category_id)->where('id', '!=', $event->id)->with(['media', 'category', 'city'])->take(4)->get();
            $new_events = Event::active()->orderBy('created_at', 'desc')->where('id', '!=', $event->id)->limit(4)->get();


            DB::commit();
            return view('Frontend.events.show', compact('event', 'setting', 'event_related', 'new_events', 'interestedCount', 'isInterested'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in EventController@show: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Show QR Code page for event invitation
     *
     * @param Event $event
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showQrCode(Event $event)
    {
        try {
            // Load event with relationships
            $event = Event::with(['eventDates', 'category', 'city'])
                ->where('uuid', $event->uuid)
                ->first();

            if (!$event) {
                return redirect()->route('events')->with('error', 'Event not found');
            }

            // Get setting for layout
            $setting = Setting::first();

            // Generate QR Code URL - use the full URL for QR code
            $qrCodeUrl = route('event.qrcode', ['event' => $event->uuid]);

            return view('Frontend.events.qrcode', compact('event', 'qrCodeUrl'));
        } catch (\Exception $e) {
            Log::error('Error in EventController@showQrCode: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            return redirect()->route('events')->with('error', 'Something went wrong');
        }
    }

    public function category(Request $request)
    {
        $categories = EventCategory::get();
        $setting = Setting::first();
        $events = Event::active()->where(['event_category_id' => $request->input('category'), 'start_date' => date('Y-m-d')])->paginate(9);

        return view('Frontend.events.index', compact('events', 'categories', 'setting'));
    }

    public function type(Request $request)
    {
        $event = Event::active()->with('currency')->where(['type' => $request->input('type'), 'start_date' => date('Y-m-d')])->get();

        return response()->json($event);
    }

    public function format(Request $request)
    {
        $event = Event::active()->with('currency')->whereIn('status', (array) $request->input('format'))
            ->where('start_date', '>', date('Y-m-d'))
            ->get();

        return response()->json($event);
    }

    public function favourite(Request $request)
    {
        DB::beginTransaction();

        try {
            $userId = auth()->user()->id;
            $eventId = $request->event_id;

            // Check if the event is already favorited
            $favourite = EventFavourite::where('event_id', $eventId)
                ->where('user_id', $userId)
                ->first();

            if ($favourite) {
                // If favorited, remove it
                $favourite->delete();
                $message = 'Event removed from favourites';
            } else {
                // If not favorited, add it
                EventFavourite::create([
                    'event_id' => $eventId,
                    'user_id' => $userId
                ]);
                $message = 'Event added to favourites';
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $message
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in EventController@favourite: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong'
            ]);
        }
    }

    /**
     * Toggle "I am interested" for the current user and event.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function interested(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please login to mark as interested',
                'login_required' => true,
            ], 401);
        }

        DB::beginTransaction();

        try {
            $userId = auth()->id();
            $eventId = $request->input('event_id');

            if (!$eventId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Event ID is required',
                ], 422);
            }

            $record = EventInterested::where('event_id', $eventId)
                ->where('user_id', $userId)
                ->first();

            if ($record) {
                $record->delete();
                $message = 'Removed from interested';
                $interested = false;
            } else {
                EventInterested::create([
                    'event_id' => $eventId,
                    'user_id' => $userId,
                ]);
                $message = 'You are interested';
                $interested = true;
            }

            $count = EventInterested::where('event_id', $eventId)->count();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'interested' => $interested,
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in EventController@interested: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    public function eventCategory($id)
    {
        $today = now()->toDateString();

        $events = Event::active()
            ->where(function ($query) use ($id) {
                $query->where('sub_category_id', $id)
                    ->orWhere('category_id', $id);
            })
            ->whereHas('eventDates', function ($query) use ($today) {
                $query->where('end_date', '>=', $today);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        $categories = EventCategory::where('parent_id', null)->get();
        $setting = Setting::first();
        $category = EventCategory::where('id', $id)->first();
        $cities = City::get();

        return view('Frontend.events.index', compact('events', 'categories', 'setting', 'category', 'cities'));
    }

    public function checkout($id)
    {
        $event = Event::where('id', $id)->whereHas('eventDates', function ($query) {
            $query->where('start_date', '>', date('Y-m-d'));
        })->first();
        $setting = Setting::first();

        return view('Frontend.events.checkout', compact('event', 'setting'));
    }

    public function newEvents()
    {
        $events = Event::active()->new()->paginate(9);
        $categories = EventCategory::where('parent_id', null)->get();
        $setting = Setting::first();
        $cities = City::get();

        return view('Frontend.events.index', compact('events', 'categories', 'setting', 'cities'));
    }

    public function topEvents()
    {
        $setting = Setting::first();
        $cities = City::get();
        $events = Event::active()->top()->paginate(9);
        $categories = EventCategory::where('parent_id', null)->get();

        return view('Frontend.events.index', compact('events', 'categories', 'setting', 'cities'));
    }

    public function upcomingEvents()
    {
        $events = Event::active()->upcoming()->paginate(9);
        $categories = EventCategory::where('parent_id', null)->get();
        $setting = Setting::first();
        $cities = City::get();

        return view('Frontend.events.index', compact('events', 'categories', 'setting', 'cities'));
    }

    public function planOfMonth()
    {
        $events = Event::active()->planOfMonth()->with(['media', 'tickets', 'currency', 'category', 'city', 'eventDates'])
            ->free()
            ->paginate(9);
        $categories = EventCategory::where('parent_id', null)->get();
        $setting = Setting::first();
        $cities = City::get();

        return view('Frontend.events.index', compact('events', 'categories', 'setting', 'cities'));
    }

    public function freeEvents()
    {
        $events = Event::active()->free()->with(['media', 'tickets', 'currency', 'category', 'city', 'eventDates'])
            ->paginate(9);
        $categories = EventCategory::where('parent_id', null)->get();
        $setting = Setting::first();
        $cities = City::get();

        return view('Frontend.events.index', compact('events', 'categories', 'setting', 'cities'));
    }

    public function pastEvents()
    {
        $events = Event::active()->past()->with(['media', 'tickets', 'currency', 'category', 'city', 'eventDates'])
            ->paginate(9);
        $categories = EventCategory::where('parent_id', null)->get();
        $setting = Setting::first();
        $cities = City::get();

        return view('Frontend.events.index', compact('events', 'categories', 'setting', 'cities'));
    }

    public function checkoutUser($id)
    {
        try {
            DB::beginTransaction();

            $event = Event::active()
                ->whereHas('eventDates', function ($query) use ($id) {
                    $query->where('id', $id);
                })->first();


            if (!$event) {
                session()->flash('error', 'Event not found');
                return redirect()->back()->with('error', 'Event not found');
            }

            $setting = Setting::first();

            DB::commit();
            return view('Frontend.events.checkout_user_static', compact('event', 'setting'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            Log::error('Error in EventController@checkoutUser: ' . $e->getMessage() . 'on line ' . $e->getLine() . 'in file ' . $e->getFile());
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function filterEvents(Request $request)
    {
        try {
            $query = Event::active()->with(['media', 'tickets', 'currency', 'category', 'city', 'eventDates'])
                ->whereHas('eventDates', function ($query) {
                    $query->where('end_date', '>', now()->toDateString());
                });

            // Search by event name, description, summary, location, category, city, organized_by, company
            if ($request->filled('search')) {
                $search = trim($request->input('search'));
                if (!empty($search)) {
                    // Split search term into words for better matching
                    $searchWords = preg_split('/\s+/', $search);
                    $searchWords = array_filter($searchWords, function ($word) {
                        return strlen(trim($word)) > 0;
                    });

                    $query->where(function ($q) use ($searchWords) {
                        foreach ($searchWords as $word) {
                            $q->where(function ($subQuery) use ($word) {
                                $subQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereRaw('LOWER(summary) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereRaw('LOWER(location) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereRaw('LOWER(organized_by) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereHas('category', function ($catQuery) use ($word) {
                                        $catQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($word) . '%']);
                                    })
                                    ->orWhereHas('city', function ($cityQuery) use ($word) {
                                        $cityQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($word) . '%']);
                                    })
                                    ->orWhereHas('company', function ($companyQuery) use ($word) {
                                        $companyQuery->whereRaw('LOWER(company_name) LIKE ?', ['%' . strtolower($word) . '%'])
                                            ->orWhereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($word) . '%'])
                                            ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . strtolower($word) . '%']);
                                    });
                            });
                        }
                    });
                }
            }

            // Filter by category (single or multiple)
            if ($request->filled('category') && $request->input('category') !== 'all') {
                $categoryIds = $request->input('category');
                if (is_array($categoryIds)) {
                    $query->whereIn('category_id', $categoryIds);
                } else {
                    $query->where('category_id', $categoryIds);
                }
            }

            // Filter by subcategory
            if ($request->filled('subcategory')) {
                $query->where('category_id', $request->input('subcategory'));
            }

            // Filter by city
            if ($request->filled('city_id')) {
                $cityId = $request->input('city_id');
                if (is_array($cityId)) {
                    $query->whereIn('city_id', $cityId);
                } else {
                    $query->where('city_id', $cityId);
                }
            } elseif ($request->filled('city')) {
                // Support both city_id and city for backward compatibility
                $cityId = $request->input('city');
                $query->where('city_id', $cityId);
            }

            // Filter by location (text search in city name)
            if ($request->filled('location')) {
                $location = $request->input('location');
                $query->whereHas('city', function ($q) use ($location) {
                    $q->where('name', 'like', '%' . $location . '%');
                });
            }

            // Filter by single date
            if ($request->filled('date')) {
                $date = $request->input('date');
                $query->whereHas('eventDates', function ($q) use ($date) {
                    $q->whereDate('start_date', '<=', $date)
                        ->whereDate('end_date', '>=', $date);
                });
            }

            // Filter by date range
            if ($request->filled('start_date')) {
                $startDate = $request->input('start_date');
                $query->whereHas('eventDates', function ($q) use ($startDate) {
                    $q->whereDate('start_date', '>=', $startDate);
                });
            }

            if ($request->filled('end_date')) {
                $endDate = $request->input('end_date');
                $query->whereHas('eventDates', function ($q) use ($endDate) {
                    $q->whereDate('end_date', '<=', $endDate);
                });
            }

            // Filter by format (online/offline) — 1 = online, 0 = offline
            if ($request->filled('format')) {
                $query->where('format', $request->input('format'));
            }

            // Filter by price range
            if ($request->filled('min_price') || $request->filled('max_price')) {
                $minPrice = $request->input('min_price');
                $maxPrice = $request->input('max_price');
                $query->whereHas('tickets', function ($q) use ($minPrice, $maxPrice) {
                    if (!is_null($minPrice) && !is_null($maxPrice)) {
                        $q->whereBetween('price', [
                            $minPrice,
                            $maxPrice
                        ]);
                    } elseif (!is_null($minPrice)) {
                        $q->where('price', '>=', $minPrice);
                    } elseif (!is_null($maxPrice)) {
                        $q->where('price', '<=', $maxPrice);
                    }
                });
            }

            // Apply price filter (free/paid)
            if ($request->filled('price')) {
                if ($request->input('price') === 'free') {
                    $query->whereHas('tickets', function ($q) {
                        $q->where('price', 0);
                    });
                } elseif ($request->input('price') === 'paid') {
                    $query->whereHas('tickets', function ($q) {
                        $q->where('price', '>', 0);
                    });
                }
            }

            // Filter by ticket type
            if ($request->filled('type')) {
                $type = $request->input('type');
                $query->whereHas('tickets', function ($q) use ($type) {
                    if (is_array($type)) {
                        $q->whereIn('type', $type);
                    } else {
                        $q->where('type', $type);
                    }
                });
            }

            // Order by creation date (newest first)
            $query->orderBy('created_at', 'desc');

            // Get events with pagination
            $events = $query->paginate(12);

            // Transform data for frontend
            $transformed = collect($events->items())->map(function ($event) {
                return [
                    'id' => $event->id,
                    'uuid' => $event->uuid,
                    'name' => $event->name,
                    'description' => $event->description,
                    'image' => $event->media->first()?->path ? asset('storage/' . $event->media->first()->path) : null,
                    'category' => $event->category?->name,
                    'city' => $event->city?->name,
                    'company' => $event->company?->name,
                    'start_date' => $event->eventDates->first()?->start_date,
                    'end_date' => $event->eventDates->first()?->end_date,
                    'start_time' => $event->eventDates->first()?->start_time,
                    'end_time' => $event->eventDates->first()?->end_time,
                    'price' => $event->tickets->first()?->price ?? 0,
                    'is_free' => optional($event->tickets->first())->price == 0,
                    'ticket_type' => $event->tickets->first()?->type,
                    'view_count' => $event->view_count,
                    'created_at' => $event->created_at->format('Y-m-d H:i:s'),
                ];
            })->values();

            // Replace paginator collection with transformed data for response
            return response()->json([
                'success' => true,
                'data' => $transformed,
                'pagination' => [
                    'current_page' => $events->currentPage(),
                    'last_page' => $events->lastPage(),
                    'per_page' => $events->perPage(),
                    'total' => $events->total(),
                    'has_more_pages' => $events->hasMorePages(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in EventController@filterEvents: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function downloadTicket($id)
    {
        $event = Event::where('id', $id)->first();
        $setting = Setting::first();

        //dwonload ticket with pdf using composer require dompdf/dompdf package
        $pdf = Pdf::loadView('Frontend.events.dwonload_ticket', compact('event', 'setting'));
        return $pdf->download('ticket.pdf');
    }

    public function checkCoupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->coupon_code)->first();
        if ($coupon) {
            $coupon->increment('usage_count');
            return response()->json(['message' => 'success', 'coupon' => $coupon->value]);
        } else {
            return response()->json(['message' => 'error']);
        }
    }

    public function applyCoupon(Request $request, $event_id)
    {
        $event = Event::where('id', $event_id)->first();
        $coupon = Coupon::where('code', $request->coupon_code)->first();
        $price = $event->tickets->sum('price');
        $discount = $coupon->value;
        if ($coupon->type == 'fixed') {
            $totalPrice = $price - $discount;
        } else {
            $totalPrice = $price - ($price * $discount / 100);
        }
        if ($coupon) {
            // $coupon->increment('usage_count');
            return response()->json(['message' => 'success', 'coupon' => $coupon->value, 'totalPrice' => $totalPrice]);
        } else {
            return response()->json(['message' => 'error']);
        }
    }

    public function checkEmail(Request $request)
    {
        $email = User::where('email', $request->email)->first();
        if ($email) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }
    public function eventCompanyGallery(Request $request)
    {
        $event = Event::where('id', $request->event_id)->with('media')->first();
        return response()->json($event);
    }

    public function eventsByCity($id)
    {
        $events = Event::active()->where('city_id', $id)->whereHas('eventDates', function ($query) {
            $query->where('end_date', '>', now()->toDateString());
        })->paginate(config('app.pagination'));
        $categories = EventCategory::where('parent_id', null)->get();
        $setting = Setting::first();
        $city = City::where('id', $id)->first();
        $cities = City::get();
        return view('Frontend.events.index', compact('events', 'categories', 'setting', 'city', 'cities'));

    }


    public function filterSections(Request $request)
    {
        try {
            // Validate request data
            $validated = $request->validate([
                'date' => 'nullable|date',
                'search' => 'nullable|string|max:255',
            ]);

            // Build base query with eager loading for better performance
            $query = Event::with([
                'media',
                'tickets',
                'currency',
                'category',
                'city',
                'eventDates',
                'company'
            ])
                ->where('is_active', 1)
                // Apply date equality only when a date is provided
            ;

            if ($validated['date'] ?? null) {
                $query->whereHas('eventDates', function ($q) use ($validated) {
                    $q->where('start_date', $validated['date']);
                });
            }

            if ($validated['date'] ?? null) {
                $query->whereHas('eventDates', function ($q) use ($validated) {
                    $q->where('start_date', '>=', $validated['date']);
                });
            }


            if ($validated['search'] ?? null) {
                $searchTerm = trim($validated['search']);
                if (!empty($searchTerm)) {
                    // Split search term into words for better matching
                    $searchWords = preg_split('/\s+/', $searchTerm);
                    $searchWords = array_filter($searchWords, function ($word) {
                        return strlen(trim($word)) > 0;
                    });

                    $query->where(function ($q) use ($searchWords) {
                        foreach ($searchWords as $word) {
                            $q->where(function ($subQuery) use ($word) {
                                $subQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereRaw('LOWER(summary) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereRaw('LOWER(location) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereRaw('LOWER(organized_by) LIKE ?', ['%' . strtolower($word) . '%'])
                                    ->orWhereHas('category', function ($catQuery) use ($word) {
                                        $catQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($word) . '%']);
                                    })
                                    ->orWhereHas('city', function ($cityQuery) use ($word) {
                                        $cityQuery->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($word) . '%']);
                                    })
                                    ->orWhereHas('company', function ($companyQuery) use ($word) {
                                        $companyQuery->whereRaw('LOWER(company_name) LIKE ?', ['%' . strtolower($word) . '%'])
                                            ->orWhereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($word) . '%'])
                                            ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . strtolower($word) . '%']);
                                    });
                            });
                        }
                    });
                }
            }

            // Get filtered events with pagination
            $events = $query->orderBy('created_at', 'desc')->paginate(9);

            // Transform data for frontend (map over paginator items only)
            $transformedEvents = collect($events->items())->map(function ($event) {
                return [
                    'id' => $event->id,
                    'uuid' => $event->uuid,
                    'name' => $event->name,
                    'description' => $event->description,
                    'image' => $event->media->first()?->path ? asset('storage/' . $event->media->first()->path) : null,
                    'category' => $event->category?->name,
                    'city' => $event->city?->name,
                    'company' => $event->company?->name,
                    'start_date' => $event->eventDates->first()?->start_date,
                    'end_date' => $event->eventDates->first()?->end_date,
                    'start_time' => $event->eventDates->first()?->start_time,
                    'end_time' => $event->eventDates->first()?->end_time,
                    'price' => $event->tickets->first()?->price ?? 0,
                    'is_free' => $event->tickets->first()?->price == 0,
                    'ticket_type' => $event->tickets->first()?->type,
                    'view_count' => $event->view_count,
                    'created_at' => $event->created_at->format('Y-m-d H:i:s'),
                ];
            })->values();

            return response()->json([
                'success' => true,
                'message' => 'Events filtered successfully',
                'data' => $transformedEvents,
                'total_count' => $events->total(),
                'pagination' => [
                    'current_page' => $events->currentPage(),
                    'last_page' => $events->lastPage(),
                    'per_page' => $events->perPage(),
                    'total' => $events->total(),
                    'has_more_pages' => $events->hasMorePages(),
                ],
                'filters_applied' => array_filter($validated, fn($value) => !is_null($value)),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error in EventController@filterSections: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Store guest survey data before redirecting to external link.
     *
     * @param GuestSurveyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeGuestSurvey(GuestSurveyRequest $request)
    {
        try {
            DB::beginTransaction();

            // Get the event to retrieve external link
            $event = Event::findOrFail($request->input('event_id'));

            // Create guest user record
            $user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'type' => 3,
                'api_token' => rand(1000, 9999),
                'email_verified_at' => now(),
            ]);
            DB::commit();

            Log::info('Guest user stored successfully', [
                'user_id' => $user->id,
                'event_id' => $event->id,
                'email' => $user->email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Your information has been saved successfully.',
                'redirect_url' => $event->external_link,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error in EventController@storeGuestSurvey: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Store Carerha booking information
     *
     * @param GuestSurveyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeCarerha(GuestSurveyRequest $request)
    {
        try {
            DB::beginTransaction();

            // Get the event to retrieve external link
            $event = Event::findOrFail($request->input('event_id'));

            // Create guest user record
            $user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'type' => 3,
                'api_token' => rand(1000, 9999),
                'email_verified_at' => now(),
            ]);
            DB::commit();

            Log::info('Carerha user stored successfully', [
                'user_id' => $user->id,
                'event_id' => $event->id,
                'email' => $user->email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Your information has been saved successfully.',
                'promo_code' => 'ME20', // يمكنك تغيير هذا من الـ backend أو إضافته في الـ database
                'redirect_url' => $event->external_link,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error in EventController@storeCarerha: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
