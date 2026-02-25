<?php

namespace App\Http\Controllers\Api\v1\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Company\Login;
use App\Http\Resources\Api\v1\ErrorResource;
use App\Http\Resources\Api\v1\SuccessResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Login $request)
    {
        try {
            $company = Company::whereEmail($request->email)->first();
            if ($company) {
                if (Hash::check($request->password, $company->password)) {
                    $company->update([
                        'api_token' => Str::random(60),
                    ]);
                    DB::commit();
                    return (new SuccessResource([
                        'message'   => __('auth.login_success'),
                        'token'     => $company->api_token,
                    ]))->withWrappData();
                }
                return (new ErrorResource(__('auth.login_failed')));
            }
            return (new ErrorResource(__('auth.login_failed')));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('error in AuthController@login: ' . $e->getMessage() . ' at Line: ' . $e->getLine() . ' in File: ' . $e->getFile());
            return (new ErrorResource(__('auth.login_failed')));
        }
    }

    public function logout(Request $request)
    {
        try {
            $company = Auth::guard('company')->user();
            if ($company) {
                $company->update([
                    'api_token' => null,
                ]);
                DB::commit();
                return (new SuccessResource([
                    'message'   => "Logout Successfully",
                ]))->withWrappData();
            }
            return (new ErrorResource("Logout Failed"));
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::channel('error')->error('error in AuthController@logout: ' . $e->getMessage() . ' at Line: ' . $e->getLine() . ' in File: ' . $e->getFile());
            return (new ErrorResource("Logout Failed"));
        }
    }


}
