<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Setting\SendUserOutboundMessageRequest;
use App\Http\Requests\Backend\Setting\UpdateSettingRequest;
use App\Http\Requests\Backend\TermsCondition\UpdateTermsConditionRequest;
use App\Enums\OutboundMessageChannel;
use App\Mail\SubscriptionEmail;
use App\Models\{Contact, Subscribe, Setting, TermsCondittion, User, VisitorSession};
use App\Services\Messaging\AdminUserOutboundMessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Log, Mail};

class SettingController extends Controller
{
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
        $users = User::collectionForMessagingPicker();

        return view('backend.settings.send-whatsapp', compact('users'));
    }

    /**
     * @return JsonResponse|RedirectResponse
     */
    public function sendWhatsappPost(
        SendUserOutboundMessageRequest $request,
        AdminUserOutboundMessageService $outboundMessageService
    ) {
        $wantsJson = $request->ajax() || $request->wantsJson();

        try {
            $userIds = $request->validated('user_ids');
            $users = User::whereIn('id', $userIds)->orderBy('id')->get();

            if ($users->count() !== count($userIds)) {
                $msg = __('One or more selected users could not be loaded.');
                if ($wantsJson) {
                    return response()->json([
                        'success' => false,
                        'alert_type' => 'error',
                        'message' => $msg,
                    ], 422);
                }
                session()->flash('error', $msg);

                return redirect()->back();
            }

            $users = User::dedupeForMessaging($users);

            $message = $request->validated('message');
            $channel = OutboundMessageChannel::from($request->validated('channel'));

            $summary = $outboundMessageService->sendBulk($users, $message, $channel);
            $label = $channel === OutboundMessageChannel::Sms ? __('SMS') : __('WhatsApp');

            if ($summary['failed'] === 0) {
                $alertType = 'success';
                $userMessage = __(':channel: sent to :count user(s).', ['channel' => $label, 'count' => $summary['sent']]);
                session()->flash('success', $userMessage);
            } elseif ($summary['sent'] > 0) {
                $alertType = 'warning';
                $preview = collect($summary['failures'])->take(3)->map(
                    fn (array $f) => $f['label'] . ': ' . $f['message']
                )->implode(' | ');
                $userMessage = __(':channel: :sent succeeded, :failed failed. :preview', [
                    'channel' => $label,
                    'sent' => $summary['sent'],
                    'failed' => $summary['failed'],
                    'preview' => $preview,
                ]);
                session()->flash('warning', $userMessage);
            } else {
                $alertType = 'error';
                $first = $summary['failures'][0]['message'] ?? __('Something went wrong');
                $userMessage = __(':channel: all sends failed. :detail', ['channel' => $label, 'detail' => $first]);
                session()->flash('error', $userMessage);
            }

            if ($wantsJson) {
                return response()->json([
                    'success' => $summary['failed'] === 0 || $summary['sent'] > 0,
                    'alert_type' => $alertType,
                    'message' => $userMessage,
                    'sent' => $summary['sent'],
                    'failed' => $summary['failed'],
                ]);
            }

            return redirect()->back();
        } catch (\Exception $e) {
            Log::channel('error')->error('Error in SettingController@sendWhatsappPost: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in file ' . $e->getFile());
            $msg = __('Something went wrong');
            if ($wantsJson) {
                return response()->json([
                    'success' => false,
                    'alert_type' => 'error',
                    'message' => $msg,
                ], 500);
            }
            session()->flash('error', $msg);

            return redirect()->back();
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
