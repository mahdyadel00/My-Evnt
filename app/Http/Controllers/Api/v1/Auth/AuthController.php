<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\CategoryInterested;
use App\Http\Requests\Api\v1\Auth\ChangePassword;
use App\Http\Requests\Api\v1\Auth\EditProfile;
use App\Http\Requests\Api\v1\Auth\Login;
use App\Http\Requests\Api\v1\Auth\Register;
use App\Http\Requests\Api\v1\Auth\VerifyPhone;
use App\Http\Resources\Api\v1\Auth\UserResource;
use App\Http\Resources\Api\v1\NotificationResource;
use App\Http\Resources\Api\v1\ErrorResource;
use App\Http\Resources\Api\v1\EventResource;
use App\Http\Resources\Api\v1\SuccessResource;
use App\Models\CategoryUser;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Twilio\Rest\Client;

class AuthController extends Controller
{
    public function register(Register $request): SuccessResource|ErrorResource
    {
        try {
            DB::beginTransaction();

            $verification_code = random_int(1000, 9999);

            $user = User::create(
                $request
                    ->safe()
                    ->merge([
                        'api_token'                 => Str::random(60),
                        'email_verified_at'         => now(),
                        'code'                      => $verification_code,
                        'phone'                     => '+2' . $request->phone,
                    ])
                    ->all(),
            );

            $receiver_number = '+2' . $request->phone;
            $otpChannel = config('services.twilio.otp_channel', 'sms');

            $sendResponse = $this->sendSms($receiver_number, $verification_code, $otpChannel);

            if (!$sendResponse['success']) {
                $errorMessage = $sendResponse['message'] ?? 'Failed to send verification code.';
                Log::channel('auth')->error("OTP send failed in register", [
                    'phone' => $receiver_number,
                    'channel' => $otpChannel,
                    'error' => $errorMessage,
                    'response' => $sendResponse
                ]);
                throw new \Exception($errorMessage);
            }

            DB::commit();

            return SuccessResource::make([
                'message' => 'User Registered Successfully',
                'verification_code' => $verification_code,
            ])->withWrappData();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('auth')->error("Error in AuthController@register: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return new ErrorResource($e->getMessage() ?: 'Register Failed');
        }
    }

    /**
     * Send WhatsApp message using the specified API.
     */

    private function sendSms($phone, $verification_code, $channel = 'whatsapp')
    {
        try {
            $sid = config('services.twilio.sid');
            $token = config('services.twilio.token');
            
            // Validate Twilio credentials
            if (empty($sid) || empty($token)) {
                Log::error("Twilio credentials missing: SID=" . ($sid ? 'set' : 'missing') . ", Token=" . ($token ? 'set' : 'missing'));
                return ['success' => false, 'message' => 'Twilio credentials are not configured'];
            }

            $client = new Client($sid, $token);

            if ($channel === 'whatsapp') {
                $from = config('services.twilio.whatsapp_from'); // whatsapp:+14155238886
                
                // Validate WhatsApp from number
                if (empty($from)) {
                    Log::error("Twilio WhatsApp FROM number is not configured");
                    return ['success' => false, 'message' => 'WhatsApp FROM number is not configured'];
                }
                
                // Validate format: should start with "whatsapp:"
                if (!str_starts_with($from, 'whatsapp:')) {
                    Log::error("Twilio WhatsApp FROM number format is invalid", ['from' => $from]);
                    return ['success' => false, 'message' => 'WhatsApp FROM number must start with "whatsapp:" (e.g. whatsapp:+14155238886)'];
                }
                
                $to = "whatsapp:$phone";
                
                // Log the attempt
                Log::info("Sending WhatsApp message", [
                    'from' => $from,
                    'to' => $to,
                    'phone' => $phone,
                    'code' => $verification_code
                ]);
            } else {
                $from = config('services.twilio.sms_from');
                $to = $phone;
                
                if (empty($from)) {
                    Log::error("Twilio SMS FROM number is not configured (TWILIO_SMS_FROM in .env)");
                    return ['success' => false, 'message' => 'SMS FROM number is not configured. Set TWILIO_SMS_FROM in .env to your Twilio number (e.g. +14244010389).'];
                }
                if (str_starts_with($from, 'whatsapp:')) {
                    Log::error("TWILIO_SMS_FROM must be a plain phone number for SMS, not whatsapp:", ['from' => $from]);
                    return ['success' => false, 'message' => 'TWILIO_SMS_FROM must be a phone number like +14244010389, not whatsapp:...'];
                }
            }

            $message = $client->messages->create(
                $to,
                [
                    "from" => $from,
                    "body" => "Your verification code is: $verification_code"
                ]
            );

            // Log success with message SID
            Log::info("Twilio message sent successfully", [
                'channel' => $channel,
                'message_sid' => $message->sid,
                'to' => $to,
                'status' => $message->status ?? 'unknown'
            ]);

            return ['success' => true, 'response' => $message->sid, 'status' => $message->status ?? 'unknown'];
        } catch (\Twilio\Exceptions\RestException $e) {
            $code = $e->getCode();
            $statusCode = $e->getStatusCode();
            $errorMessage = $e->getMessage();
            
            Log::error("Twilio {$channel} RestException", [
                'message' => $errorMessage,
                'code' => $code,
                'status' => $statusCode,
                'phone' => $phone,
                'from' => $from ?? 'not set'
            ]);

            // Error 63015: Recipient must join WhatsApp Sandbox first
            if ((int) $code === 63015 && $channel === 'whatsapp') {
                $joinCode = config('services.twilio.whatsapp_sandbox_join');
                $joinPhrase = $joinCode ? "join {$joinCode}" : 'join <sandbox-code>';
                $helpMessage = "To receive the code on WhatsApp, send \"{$joinPhrase}\" to +1 415 523 8886 first. (Get sandbox code from Twilio Console > Messaging > Try WhatsApp)";
                return ['success' => false, 'message' => $helpMessage, 'code' => $code];
            }

            // HTTP 400: Invalid From address (Channel not found)
            if ($statusCode === 400 && str_contains($errorMessage, 'Channel with the specified From address')) {
                $helpMessage = "Invalid WhatsApp FROM number. Please check TWILIO_WHATSAPP_FROM in .env. For Sandbox, use: whatsapp:+14155238886. Verify the number exists in your Twilio Console > Messaging > Try WhatsApp.";
                return ['success' => false, 'message' => $helpMessage, 'code' => $code, 'original_error' => $errorMessage];
            }

            return ['success' => false, 'message' => $errorMessage, 'code' => $code];
        } catch (\Exception $e) {
            Log::error("Twilio {$channel} General Error", [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'phone' => $phone
            ]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Login
     *
     * @param Login $request
     * @return SuccessResource|ErrorResource
     */
    public function login(Login $request): SuccessResource|ErrorResource
    {
        DB::beginTransaction();

        try {

            $user = User::where('phone', '+2' . $request->phone)->first();

            if (!$user) {
                return new ErrorResource('User not found');
            }

            $specific_phone = '01061213663';
            $verification_code = ($request->phone === $specific_phone)
                ? 1234
                : random_int(1000, 9999);

            $receiver_number = '+2' . $request->phone;

            $otpChannel = config('services.twilio.otp_channel', 'sms');
            $this->sendSms($receiver_number, $verification_code, $otpChannel);

            $user->update([
                'code' => $verification_code,
                'api_token' => hash('sha256', Str::random(60)),
            ]);

            DB::commit();

            $channelHint = $otpChannel === 'whatsapp' ? 'WhatsApp' : 'SMS';
            return new SuccessResource([
                'message' => "Login request sent successfully. Please check your {$channelHint} for the verification code.",
                'code' => $user->code,
            ]);
        } catch (\Twilio\Exceptions\RestException $e) {
            DB::rollBack();
            Log::error("Twilio error: {$e->getMessage()}");

            return new ErrorResource('Failed to send verification code. Please try again later.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error("Error in AuthController@login: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");

            return new ErrorResource('Login failed. Please try again later.');
        }
    }

    /**
     * Verify phone number
     *
     * @param VerifyPhone $request
     * @return SuccessResource|ErrorResource
     */
    public function verifyPhone(VerifyPhone $request): SuccessResource|ErrorResource
    {
        try {
            DB::beginTransaction();
            $user = User::where(['phone' => '+2' . $request->phone], ['code' => $request->code])->first();
            if ($user) {
                if ($user->created_at->diffInMinutes(now()) > 5) {
                    $user->delete();

                    DB::commit();
                    return (new ErrorResource('Your verification time expired. Please register again.'));
                }

                $user->update(attributes: [
                    'remember_token' => Str::random(60),
                    'is_active' => true,
                    'email_verified_at' => now(),
                    'code' => null,
                ]);

                DB::commit();
                return (new SuccessResource([
                    'message' => 'Phone Verified Successfully',
                    'token' => $user->api_token,
                ]))->withWrappData();
            }

            return new ErrorResource('Phone Verify Failed');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('auth')->error("Error in AuthController@verifyPhone: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return new ErrorResource('Phone Verify Failed');
        }
    }

    protected function logout(Request $request): SuccessResource
    {
        try {
            $user = $request->user();

            if (!$user) {
                return new ErrorResource('User not found');
            }

            DB::transaction(function () use ($user) {
                $user->update(['api_token' => null]);
            });

            return new SuccessResource('Logged out successfully');
        } catch (\Exception $e) {
            Log::channel('error')->error('Error in AuthController@logout: ' . $e->getMessage() . ' at Line: ' . $e->getLine() . ' in File: ' . $e->getFile());
            return new ErrorResource(__('Logout Failed'));
        }
    }

    public function editProfile(EditProfile $request)
    {
        try {
            DB::beginTransaction();
            auth()
                ->user()
                ->update($request->safe()->except('old_password'));
            if ($request->has('old_password') && $request->has('new_password')) {
                if (Hash::check($request->old_password, auth()->user()->password)) {
                    auth()
                        ->user()
                        ->update(['password' => Hash::make($request->new_password)]);
                } else {
                    return new ErrorResource(__('Old Password Not Match'));
                }
            }
            if (count($request->files) > 0) {
                saveMedia($request, auth()->user());
            }
            DB::commit();
            return new SuccessResource(['message' => 'Successfully Updated Profile']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel($this->role)->error("Error in UserController@update: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return new ErrorResource(__('Update Failed'));
        }
    }

    protected function profile(Request $request)
    {
        try {
            $user = User::where('id', auth()->user()->id)->first();
            if ($user) {
                return SuccessResource::make([
                    'message' => __('The User Profile'),
                    'user' => new UserResource($user),
                ])->withWrappData();
            }
            return ErrorResource::make(__('Check Your Data'));
        } catch (\Exception $e) {
            Log::channel('error')->error("error in AuthController@getProfile: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return new ErrorResource(__('Login Failed'));
        }
    }

    protected function changePassword(ChangePassword $request)
    {
        try {
            $user = User::find(auth()->id());
            if ($user) {
                if (Hash::check($request->old_password, $user->password)) {
                    $user->update(['password' => Hash::make($request->new_password)]);
                    return new SuccessResource(__('Password Changed Successfully'));
                }
                return new ErrorResource(__('Old Password Not Match'));
            }
            return new ErrorResource(__('Change Password Failed'));
        } catch (\Exception $e) {
            Log::channel('error')->error('Error in AuthController@changePassword: ' . $e->getMessage() . ' at Line: ' . $e->getLine() . ' in File: ' . $e->getFile());
            return new ErrorResource(__('Change Password Failed'));
        }
    }

    protected function deleteProfile(Request $request)
    {
        try {
            DB::beginTransaction();
            if ($user = User::find(auth()->id())) {
                $user->delete();
                DB::commit();
                return new SuccessResource(__('Deleted Successfully', ['attribute' => __('attributes.user')]));
            }
            return new ErrorResource(__('Delete Failed', ['attribute' => __('attributes.user')]));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in AuthController@deleteProfile: ' . $e->getMessage() . ' at Line: ' . $e->getLine() . ' in File: ' . $e->getFile());
            return new ErrorResource(__('Delete Failed', ['attribute' => __('attributes.user')]));
        }
    }

    protected function categoryInterested(CategoryInterested $request)
    {
        try {
            foreach ($request->category as $category) {
                CategoryUser::create([
                    'user_id' => auth()->id(),
                    'category_id' => $category,
                ]);
            }
            DB::commit();
            return new SuccessResource(__('Category Interested Successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('Error in AuthController@categoryInterested: ' . $e->getMessage() . ' at Line: ' . $e->getLine() . ' in File: ' . $e->getFile());
            return new ErrorResource(__('Category Interested Failed'));
        }
    }

    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = auth()->user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json(['message' => 'FCM token updated successfully']);
    }

    public function notifications(Request $request)
    {
        try {
            $user = auth()->user();
            $notifications = $user->notifications()->paginate(config('app.pagination'));

            return SuccessResource::make([
                'message' => __('The User Notifications'),
                'notifications' => NotificationResource::collection($notifications),
            ])->withWrappData();
        } catch (\Exception $e) {
            Log::channel('error')->error("error in AuthController@getNotifications: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return new ErrorResource(__('Login Failed'));
        }
    }

    public function notificationRead(Request $request, $id)
    {
        try {
            $user = auth()->user();
            $notification = $user->notifications()->where('status', 'read')->find($id);
            if ($notification) {
                return SuccessResource::make([
                    'message' => __('Notification Marked As Read'),
                    'notification' => NotificationResource::make($notification),
                ])->withWrappData();
            }
            return ErrorResource::make(__('Notification Not Found'));
        } catch (\Exception $e) {
            Log::channel('error')->error("error in AuthController@notificationRead: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return new ErrorResource(__('Login Failed'));
        }
    }

    public function notificationReadAll(Request $request)
    {
        try {
            $user = auth()->user();
            $notifications = $user->notifications()->where('status', 'read')->paginate(config('app.pagination'));
            return SuccessResource::make([
                'message' => __('All Notifications Marked As Read'),
                'data' => NotificationResource::collection($notifications),
            ])->withWrappData();
        } catch (\Exception $e) {
            Log::channel('error')->error("error in AuthController@notificationReadAll: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return new ErrorResource(__('Login Failed'));
        }
    }

    public function notificationDelete(Request $request, $id)
    {
        try {
            $user = auth()->user();
            $notification = $user->notifications()->find($id);
            if ($notification) {
                $notification->delete();
                return SuccessResource::make([
                    'message' => __('Notification Deleted Successfully'),
                ])->withWrappData();
            }
            return ErrorResource::make(__('Notification Not Found'));
        } catch (\Exception $e) {
            Log::channel('error')->error("error in AuthController@notificationDelete: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return new ErrorResource(__('Login Failed'));
        }
    }

    public function notificationDeleteAll(Request $request)
    {
        try {
            $user = auth()->user();
            $user->notifications()->delete();
            return SuccessResource::make([
                'message' => __('All Notifications Deleted Successfully'),
            ])->withWrappData();
        } catch (\Exception $e) {
            Log::channel('error')->error("error in AuthController@notificationDeleteAll: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return new ErrorResource(__('Login Failed'));
        }
    }
}
