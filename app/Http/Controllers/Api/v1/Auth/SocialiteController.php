<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\LoginSocialite;
use App\Http\Requests\SocialiteProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Resources\Api\v1\SuccessResource;
use App\Http\Resources\Api\v1\ErrorResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SocialiteController extends Controller
{
    /**
     * Login with socialite
     */
    public function loginSocialite(LoginSocialite $request)
    {
        DB::beginTransaction();
        try {
            $user = User::where(['social_id' => $request->social_id, 'social_type' => $request->social_type])->first();
            

            if ($user) {
                $user->update([
                    'api_token' => Str::random(60),
                ]);

                DB::commit();
                return (new SuccessResource([
                    'message' => __('auth.login_success'),
                    'token' => $user->api_token,
                ]))->withWrappData();
            }

            $newUser = User::create(
                $request->validated() + ['api_token' => Str::random(60)]
            );

            if ($request->hasFile('photo')) {
                saveMedia($request, $newUser);
            }

            DB::commit();
            return (new SuccessResource([
                'message' => __('auth.login_success'),
                'token' => $newUser->api_token,
            ]))->withWrappData();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error in SocialiteController@loginSocialite: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}