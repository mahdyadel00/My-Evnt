<?php

namespace App\Http\Controllers\Frontend\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Sponsor\StoreSponsorRequest;
use App\Mail\OrderMail;
use App\Models\AdFee;
use App\Models\Event;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Ticket;
use App\Services\PayMobServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SponserController extends Controller
{
    public function sponsor()
    {
        $ad_fees = AdFee::get();

        return view('Frontend.organization.events.sponsor', compact('ad_fees'));
    }


    public function storeSponsor(StoreSponsorRequest $request)
    {
        DB::beginTransaction();
        try {
            $event = Event::find(auth()->guard('company')->user()->events->last()->id);

            $event->update([
                'ad_fee_id' => $request->ad_fee_id,
            ]);

            $price = $event->adFee->price ?? 0;

            $paymentMethod = $request->payment_method ?? 'default';
            $paymobService = new PayMobServices($price, $paymentMethod);
            $paymobService->get_id();
            $iframeToken = $paymobService->make_order(auth()->guard('company')->user());
            DB::commit();
            session()->flash('success', 'Event setup 4 created successfully');
            return redirect()->away("https://accept.paymob.com/api/acceptance/iframes/381139?payment_token=$iframeToken");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('EventOrganizeController@storeSponsor Error: '. $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return back();
        }

    }



}
