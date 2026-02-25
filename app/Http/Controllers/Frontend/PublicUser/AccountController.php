<?php

namespace App\Http\Controllers\Frontend\PublicUser;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventFavourite;
use App\Models\Setting;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function myTickets()
    {
        return view('frontend.account.my_tickets');
    }

    public function myWishlist()
    {
        $categories     = EventCategory::get();
        $event_user     = EventFavourite::where('user_id', auth()->id())->pluck('event_id')->toArray();
        $events         = Event::whereIn('id', $event_user)->get();

        return view('frontend.account.my_wish_list', compact('categories', 'events'));
    }

    public function getEventsByCategory(Request $request)
    {
        $categories     = EventCategory::get();
        $setting        = Setting::first();
        $events         = Event::with(['currency' , 'media'])->where('event_category_id', $request->category_id)->get();

        return response()->json($events);
    }

}
