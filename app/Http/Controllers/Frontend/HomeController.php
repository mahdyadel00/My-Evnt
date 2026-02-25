<?php

namespace App\Http\Controllers\Frontend;

use App\Models\FAQ;
use App\Models\Blog;
use App\Models\Artical;
use App\Models\User;
use App\Models\Event;
use App\Models\Slider;
use App\Models\Company;
use App\Models\Partner;
use App\Models\Setting;
use App\Models\Subscribe;
use App\Models\City;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\EventCategory;
use App\Models\TermsCondittion;
use App\Mail\SubscriptionEmail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Display the homepage with all necessary data.
     */
    public function index()
    {
        // Fetch sliders (exclude past events)
        $sliders = Slider::with('event.eventDates')
            ->get()
            ->filter(function ($slider) {
                $firstDate = optional($slider->event?->eventDates->first());
                // Exclude events that have already ended
                return $firstDate->start_date && $firstDate->start_date >= now()->toDateString();
            });

        $partners = Partner::all();
        // Fetch event categories
        $event_category = EventCategory::where('is_parent', true)->take(10)->get();

        // Fetch events based on their status
        $exclusive_events = Event::active()->exclusive()->whereHas('eventDates', function ($query) {
            $query->where('start_date', '>', now()->toDateString());
        })
            ->with('media', 'tickets', 'currency', 'category', 'city', 'eventDates')
            ->whereHas('eventDates', function ($query) {
                $query->where('start_date', '>', now()->toDateString());
            })
            ->active()
            ->get();
        //plane this month
        $plan_month = Event::whereHas('eventDates', function ($query) {
            $query->whereMonth('start_date', Carbon::now()->month)->whereYear('start_date', Carbon::now()->year);
        })
            ->with('media', 'tickets', 'currency', 'category', 'city', 'eventDates')
            ->whereHas('eventDates', function ($query) {
                $query->where('start_date', '>', now()->toDateString());
            })
            ->active()
            ->free()
            ->orderBy('created_at', 'desc')
            ->take(4)->get();
        if(count($plan_month) < 4){
            $nextMonth = Carbon::now()->addMonth();
            $plan_month = Event::whereHas('eventDates', function ($query) use ($nextMonth) {
                $query->whereMonth('start_date', $nextMonth->month)->whereYear('start_date', $nextMonth->year);
            })
                ->with('media', 'tickets', 'currency', 'category', 'city', 'eventDates')
                ->whereHas('eventDates', function ($query) {
                    $query->where('start_date', '>', now()->toDateString());
                })
                ->active()
                ->free()
                ->orderBy('created_at', 'desc')
                ->take(4)->get();
        }
        // dd($plan_month);
        $usedIds = $exclusive_events->pluck('id')->toArray();
        $new_events = Event::active()->whereNotIn('id', $usedIds)->orderBy('created_at', 'desc')->take(4)->get();
        $upcoming_events = Event::active()->upcoming()->whereNotIn('id', $usedIds)->take(4)->get();
        $top_events = Event::active()->top()->whereNotIn('id', $usedIds)->orderByDesc('id')->take(20)->get();
        $past_events = Event::active()->past()->whereNotIn('id', $usedIds)->take(4)->latest()->get();
        // Count events, companies, and users
        $events_count = Event::count();
        $companies = Company::count();
        $public_user = User::count();

        // Fetch the latest event
        $latest_events = Event::active()
            ->orderBy('id', 'desc')
            ->first();

        // Fetch settings
        $setting = Setting::first();
        $cities = City::where('is_available', true)->get();

        // Return the view with all data
        return view('Frontend.layouts.index', get_defined_vars());
        // return view('Frontend.layouts.index', compact('sliders', 'event_category', 'new_events', 'upcoming_events', 'top_events', 'events_count', 'setting', 'companies', 'public_user', 'past_events', 'latest_events', 'exclusive_events', 'partners'));
    }

    /**
     * Handle subscription requests.
     */
    protected function subscribe(Request $request)
    {
        $request->validate(
            [
                'email'                     => ['required', 'email', 'unique:subscribes,email'],
            ],
            [
                'email.required'            => 'Please enter your email address',
                'email.email'               => 'Please enter a valid email address',
                'email.unique'              => 'This email is already subscribed',
            ]
        );

        try {
            $subscriber = Subscribe::create(['email' => $request->email]);

            // Send welcome email
            $subject = 'Welcome to Our Newsletter!';
            $messageContent = 'Thank you for subscribing to our newsletter. You will now receive updates about our latest events and news.';

            Mail::to($subscriber->email)->send(new SubscriptionEmail($subject, $messageContent));

            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            \Log::error('Subscription error: ' . $e->getMessage());
            return response()->json(['message' => 'failed'], 500);
        }
    }


    /**
     * Display the FAQ page.
     */
    public function faq()
    {
        $faqs = FAQ::get();
        $setting = Setting::first();

        return view(
            'Frontend.organization.faqs.faq',
            compact('faqs', 'setting')
        );
    }

    /**
     * Display the terms and conditions page.
     */
    public function terms()
    {
        $faqs = FAQ::get();
        $setting = Setting::first();
        $terms_conditions = TermsCondittion::first();

        return view(
            'Frontend.terms',
            compact('faqs', 'setting', 'terms_conditions')
        );
    }

    /**
     * Display the privacy policy page.
     */
    public function privacy()
    {
        $faqs = FAQ::get();
        $setting = Setting::first();
        $terms_conditions = TermsCondittion::first();

        return view(
            'Frontend.privacy',
            compact('faqs', 'setting', 'terms_conditions')
        );
    }

    /**
     * Display the blogs page.
     */
    public function blogs()
    {
        $setting = Setting::first();
        $blogs = Blog::paginate(6);

        return view(
            'Frontend.organization.blogs.blogs',
            compact('setting', 'blogs')
        );
    }

    /**
     * Display the details of a specific blog.
     */
    public function blogDetails($id)
    {
        $setting = Setting::first();
        $article = Artical::where('blog_id', $id)->orderBy('created_at', 'desc')->first();

        return view(
            'Frontend.organization.blogs.blog_details',
            compact('setting', 'article')
        );
    }

    /**
     * Display the company profile page.
     */
    public function companyProfile($id)
    {
        $setting = Setting::first();
        $company = Company::find($id);

        return view(
            'Frontend.events.company_profile',
            compact('setting', 'company')
        );
    }
}
