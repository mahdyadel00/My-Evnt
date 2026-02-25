<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Setting\UpdateSettingRequest;
use App\Http\Requests\Backend\TermsCondition\UpdateTermsConditionRequest;
use App\Mail\SubscriptionEmail;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\Subscribe;
use App\Models\TermsCondittion;
use App\Models\User;
use App\Models\VisitorSession;
use App\Repositories\SettingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;


class SettingController extends Controller
{
    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function edit()
    {
        $setting = Setting::first();

        return view('backend.settings.edit', compact('setting'));
    }


    public function update(UpdateSettingRequest $request)
    {
        try {
            DB::beginTransaction();
            $settings = Setting::first();

            if (!$settings) {
                session()->flash('error', __('Settings not found'));
                return redirect()->back();
            }

            $settings->update($request->safe()->all());

            if (count($request->files) > 0) {
                saveMedia($request, $settings);
            }

            DB::commit();
            session()->flash('success', __('Settings updated successfully'));
            return redirect()->route('admin.settings.edit');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            Log::channel('error')->error('Error in SettingController@update: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            session()->flash('error', __('Something went wrong'));
            return redirect()->back();
        }
    }

    protected function termsCondition()
    {
        $terms_conditions = TermsCondittion::first();

        return view('backend.terms_conditions.edit', compact('terms_conditions'));
    }

    protected function termsConditionUpdate(UpdateTermsConditionRequest $request)
    {
        try {
            DB::beginTransaction();
            $termsCondition = TermsCondittion::first();

            if (!$termsCondition) {
                session()->flash('error', __('Terms & Condition not found'));
                return redirect()->back();
            }

            $termsCondition->update($request->safe()->all());

            DB::commit();
            session()->flash('success', __('Terms & Condition updated successfully'));
            return redirect()->route('admin.terms-condition.edit');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in SettingController@termsConditionUpdate: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            session()->flash('error', __('Something went wrong'));
            return redirect()->back();
        }
    }

    public function subscribers()
    {
        $subscribers = Subscribe::latest()->cursorPaginate(10);

        return view('backend.subscribers.index', compact('subscribers'));
    }

    public function unsubscribe($id)
    {
        try {
            $subscribe = Subscribe::find($id);

            if (!$subscribe) {
                session()->flash('error', __('Subscriber not found'));
                return redirect()->back();
            }

            $subscribe->delete();

            session()->flash('success', __('Subscriber unsubscribed successfully'));
            return redirect()->back();
        } catch (\Exception $e) {
            Log::channel('error')->error('Error in SettingController@unsubscribe: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            session()->flash('error', __('Something went wrong'));
            return redirect()->back();
        }
    }

    public function contacts()
    {
        $contacts = Contact::latest()->cursorPaginate(10);

        return view('backend.contacts.index', compact('contacts'));
    }

    public function destroyContact($id)
    {
        try {
            $contact = Contact::find($id);

            if (!$contact) {
                session()->flash('error', __('Contact not found'));
                return redirect()->back();
            }

            $contact->delete();

            session()->flash('success', __('Contact deleted successfully'));
            return redirect()->back();
        } catch (\Exception $e) {
            Log::channel('error')->error('Error in SettingController@destroyContact: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            session()->flash('error', __('Something went wrong'));
            return redirect()->back();
        }
    }

    public function createEmail()
    {
        $subscribers = Subscribe::latest()->cursorPaginate(10);

        return view('backend.subscribers.create', compact('subscribers'));
    }

    public function sendEmail(Request $request)
    {
        try {
            //            $request->validate([
//                'subject'       => 'required',
//                'message'       => 'required',
//                'subscribers'   => 'required|array',
//            ]);

            $subject = $request->subject;
            $messageContent = $request->message;

            foreach ($request->subscribers as $subscriber) {
                $email = new SubscriptionEmail($subject, $messageContent);
                Mail::to($subscriber)->send($email);
            }

            session()->flash('success', __('Email sent successfully'));
            return redirect()->back();
        } catch (\Exception $e) {
            Log::channel('error')->error('Error in SettingController@sendEmail: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            session()->flash('error', __('Something went wrong'));
            return redirect()->back();
        }
    }

    public function sendWhatsapp()
    {
        $users = User::all();
        return view('backend.settings.send-whatsapp', compact('users'));
    }

    public function sendWhatsappPost(Request $request)
    {
        try {
            $user = User::find($request->user_id);
            $message = $request->message;
            $receiver_number =  $user->phone;
            $whatsapp_response = $this->sendWhatsAppMessage($receiver_number, $message);
            session()->flash('success', __('Whatsapp sent successfully'));
            return redirect()->back();
        } catch (\Exception $e) {
            Log::channel('error')->error('Error in SettingController@sendWhatsappPost: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            session()->flash('error', __('Something went wrong'));
            return redirect()->back();
        }
    }

    private function sendWhatsAppMessage($phone, $message)
    {
        // dd($phone, $message);
        try {
            $sid = config('services.twilio.sid');
            $token = config('services.twilio.token');
            $from = config('services.twilio.sim_from');

            $client = new Client($sid, $token);

            $message = $client->messages->create(
                "whatsapp:$phone",
                [
                    "from" => $from,
                    "body" => $message
                ]
            );
            dd($message);

            return ['success' => true, 'response' => $message->sid];
        } catch (\Exception $e) {
            Log::error("Twilio WhatsApp Error: {$e->getMessage()}");
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function sessionManagement()
    {
        $sessions = VisitorSession::with('user', 'event')->latest()->get();
        return view('backend.sessions.index', compact('sessions'));
    }

    public function destroySession($id)
    {
        $session = VisitorSession::find($id);
        $session->delete();
        return redirect()->route('admin.session-management')->with('success', 'Session deleted successfully');
    }
}
