<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try{
            // Check if this is a popup request with JWT token
            if ($request->has('credential')) {
                return $this->handlePopupCallback($request);
            }

            // Original redirect flow
            $googleUser = Socialite::driver('google')->user();

            $findUser = User::where('social_id', $googleUser->id)->first();
            if($findUser){
                Auth::login($findUser);
                return redirect()->route('home');
            }else{
                $newUser = new User();
                $newUser->user_name     = $googleUser->name;
                $newUser->email         = $googleUser->email;
                $newUser->social_id     = $googleUser->id;
                $newUser->password      = bcrypt('my-google-password');
                $newUser->social_type   = 'google';
                $newUser->save();
                Auth::login($newUser);
                return redirect()->route('home');
            }
        }catch(\Exception $e){
            DB::rollback();
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            session()->flash('error', 'Login Failed');
            return redirect()->back();
        }
    }

    private function handlePopupCallback(Request $request)
    {
        try {
            $credential = $request->input('credential');

            // Decode the JWT token
            $payload = $this->decodeJWT($credential);

            if (!$payload) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Login Failed'
                ], 400);
            }

            $googleId = $payload['sub'];
            $email = $payload['email'];
            $name = $payload['name'];

            $findUser = User::where('social_id', $googleId)->first();

            if($findUser){
                Auth::login($findUser);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login Success'
                ]);
            } else {
                $newUser = new User();
                $newUser->user_name     = $name;
                $newUser->email         = $email;
                $newUser->social_id     = $googleId;
                $newUser->password      = bcrypt('my-google-password');
                $newUser->social_type   = 'google';
                $newUser->save();
                Auth::login($newUser);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Login Success'
                ]);
            }
        } catch(\Exception $e) {
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            return response()->json([
                'status' => 'error',
                'message' => 'Login Failed'
            ], 500);
        }
    }

    private function decodeJWT($jwt)
    {
        try {
            // Split the JWT
            $parts = explode('.', $jwt);
            if (count($parts) !== 3) {
                return false;
            }

            // Decode the payload (middle part)
            $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);

            return $payload;
        } catch (\Exception $e) {
            Log::channel('error')->error('JWT Decode Error: ' . $e->getMessage());
            return false;
        }
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try{
            $facebookUser = Socialite::driver('facebook')->user();

            $findUser = User::where('social_id', $facebookUser->id)->first();
            if($findUser){
                Auth::login($findUser);
                return redirect()->route('home');
            }else{
                $newUser = new User();
                $newUser->user_name     = $facebookUser->name;
                $newUser->social_id     = $facebookUser->id;
                $newUser->password      = bcrypt('my-facebook-password');
                $newUser->social_type   = 'facebook';
                $newUser->save();
                Auth::login($newUser);
                return redirect()->route('home');
            }
        }catch(\Exception $e){
            DB::rollback();
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            session()->flash('error', 'Login Failed');
            return redirect()->back();
        }
    }
}
