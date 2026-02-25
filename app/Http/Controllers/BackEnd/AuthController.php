<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Auth\Login;
use App\Models\Admin;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $setting    = Setting::first();
        $admins     = Admin::all();

        return view('backend.login', compact('admins' , 'setting'));
    }

    public function doLogin(Login $request)
    {
        try {
            DB::beginTransaction();

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                session()->flash('error', 'Email is incorrect');
                return redirect()->back();
            }

            if (Hash::check($request->password, $user->password)) {
                Auth::login($user);
                DB::commit();
                return redirect()->route('admin.home');
            }

            DB::commit();
            return redirect()->route('admin.home');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            session()->flash('error', 'Something went wrong');
            return redirect()->back();
        }
    }

    protected function profile(Request $request)
    {
        try {
            DB::beginTransaction();

            $admin  = Admin::where('id', Auth::id())->first();
            $admins = Admin::get();

            if (!$admin) {
                session()->flash('error', ('Admin Not Found'));
                return redirect()->back();
            }

            DB::commit();
            return view('backend.auth.profile' , compact('admin' , 'admins'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            session()->flash('error', __('messages.error'));
            return redirect()->back();
        }

    }

    protected function logout(){

        Auth::logout();

        return redirect()->route('admin.login');
    }

}
