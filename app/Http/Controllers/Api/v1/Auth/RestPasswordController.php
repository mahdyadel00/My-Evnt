<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\CheckPhone;
use App\Http\Requests\Api\v1\Auth\CheckResetCode;
use App\Http\Requests\Api\v1\Auth\ResetPassword;
use App\Http\Resources\Api\v1\ErrorResource;
use App\Http\Resources\Api\v1\SuccessResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RestPasswordController extends Controller
{
    protected function forgetPassword(CheckPhone $request)
    {
        try{
            DB::beginTransaction();
            $user = User::wherePhone($request->phone)->first();
            if(!$user) {
                return (new ErrorResource('Check Phone Failed'));
            }
            $code = rand(1000, 9999);
            $user->update([
                'code' => $code
            ]);
            DB::commit();
            return SuccessResource::make([
                'message'       => 'Check Phone Success',
                'code'          => $user->code,
            ])->withWrappData();
        }catch (\Exception $e) {
            DB::rollBack();
            Log::channel('auth')->error("Error in RestPasswordController@checkPhone: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return (new ErrorResource('Check Phone Failed'));
        }
    }

    protected function checkResetCode(CheckResetCode $request)
    {
        try{
            $user = User::wherePhone($request->phone)->first();
            if(!$user) {
                return (new ErrorResource('Check Code Failed'));
            }
            if($user->code != $request->code) {
                return (new ErrorResource('Check Code Failed'));
            }
            return SuccessResource::make([
                'message'       => 'Check Code Success',
            ])->withWrappData();
        }catch (\Exception $e) {
            DB::rollBack();
            Log::channel('auth')->error("Error in RestPasswordController@checkCode: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return (new ErrorResource('Check Code Failed'));
        }
    }

    protected function resetPassword(ResetPassword $request)
    {
        try{
            $user = User::wherePhone($request->phone)->first();
            if(!$user) {
                return (new ErrorResource('Reset Password Failed'));
            }
            $user->update([
                'password'  => Hash::make($request->password),
                'code'      => null
            ]);
            return SuccessResource::make([
                'message'       => 'Reset Password Success',
            ])->withWrappData();
        }catch (\Exception $e) {
            DB::rollBack();
            Log::channel('auth')->error("Error in RestPasswordController@resetPassword: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return (new ErrorResource('Reset Password Failed'));
        }
    }
}
