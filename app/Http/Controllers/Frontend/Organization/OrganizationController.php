<?php

namespace App\Http\Controllers\Frontend\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\AuthOrganization\ForgotPassword;
use App\Http\Requests\Frontend\AuthOrganization\Login;
use App\Http\Requests\Frontend\AuthOrganization\Register;
use App\Mail\OrganizeResetPassword;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\{Company, OrganizationSlider, Package, Setting, EventCategory, Blog, Customer, Event, Ticket, User};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    public function index()
    {
        $setting                = Setting::first();
        $customers              = Customer::take(4)->get();
        $categories             = EventCategory::take(4)->get();
        $blogs                  = Blog::take(3)->get();
        $events                 = Event::active()->get();
        $package_basic          = Package::first();
        $package_pro            = Package::skip(1)->first();
        $package_ultimate       = Package::skip(2)->first();
        $organization_slider    = OrganizationSlider::first();
        $tickets                = Ticket::get();

        return view('Frontend.organization.layouts.index', compact('setting', 'customers', 'categories', 'blogs', 'events'
            , 'package_basic', 'package_pro', 'package_ultimate', 'organization_slider', 'tickets'));
    }

    public function logout()
    {
        auth()->guard('company')->logout();
        return redirect()->route('organization');
    }
    public function login(){

        $setting   = Setting::first();

        return view('Frontend.organization.auth.login' , compact('setting'));
    }

    public function postLogin(Login $request)
    {
        try {
            // Attempt to log in with the 'company' guard
            $credentials = $request->only('email', 'password');

            if (Auth::guard('company')->attempt($credentials)) {
                // Login successful
                session()->flash('success', 'Login Successful');
                return redirect()->route('organization');
            } else {
                // Login failed
                session()->flash('error', 'Login Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Log::channel('error')->error($e->getMessage() . ' at line ' . $e->getLine() . ' in ' . $e->getFile());
            session()->flash('error', 'An error occurred during login');
            return redirect()->back();
        }
    }

    public function register()
    {
        $setting   = Setting::first();

        return view('Frontend.organization.auth.register' , compact('setting'));
    }

    public function postRegister(Register $request)
    {
        try{
            DB::beginTransaction();

            $company = Company::create($request->safe()->merge([
                'password'  => Hash::make($request->password),
                'api_token' => Str::random(60),
            ])->all());

            if ($company) {
                DB::commit();
                session()->flash('success', 'Registration Success');
                return redirect()->route('organization_login');
            } else {
                DB::rollback();
                session()->flash('error', 'Registration Failed');
                return redirect()->back();
            }
        }catch(\Exception $e){
            DB::rollback();
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            session()->flash('error', 'Registration Failed');
            return redirect()->back();
        }
    }

    public function forgotPassword()
    {
        $setting   = Setting::first();

        return view('Frontend.organization.auth.forgot_password' , compact('setting'));
    }

    public function postForgotPassword(ForgotPassword $request)
    {
        try{
            DB::beginTransaction();
          $company = auth()->guard('company')->attempt(['email' => $request->email]);
            if ($company) {
                $user = User::where('email', $request->email)->first();
                $user->update(['api_token' => rand(1000, 9999)]);
                Mail::to($request->email)->send(new OrganizeResetPassword($user));
                DB::commit();
                session()->flash('success', 'Password Reset Success');
                return redirect()->route('organization_login');
            } else {
                DB::rollback();
                session()->flash('error', 'Password Reset Failed');
                return redirect()->back();
            }
        }catch(\Exception $e){
            DB::rollback();
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            session()->flash('error', 'Password Reset Failed');
            return redirect()->back();
        }
    }

    public function resetPassword($token)
    {
        $setting  = Setting::first();

        return view('Frontend.organization.auth.reset_password', compact('setting', 'token'));
    }

    public function postResetPassword(\App\Http\Requests\Frontend\AuthOrganization\ResetPassword $request)
    {
        try{
            DB::beginTransaction();
            $user = User::where('api_token', $request->code)->first();
            if ($user) {
                $user->update(['password' => Hash::make($request->password)]);
                DB::commit();
                session()->flash('success', 'Password Reset Success');
                return redirect()->route('organization_login');
            } else {
                DB::rollback();
                session()->flash('error', 'Password Reset Failed');
                return redirect()->back();
            }
        }catch(\Exception $e){
            DB::rollback();
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            session()->flash('error', 'Password Reset Failed');
            return redirect()->back();
        }
    }

    public function deleteAccount()
    {
        try{
            DB::beginTransaction();
            $company = Company::find(auth()->guard('company')->user()->id);
            if ($company) {
                $company->delete();
                DB::commit();
                session()->flash('success', 'Account Deleted');
                return redirect()->route('organization_login');
            } else {
                DB::rollback();
                session()->flash('error', 'Account Delete Failed');
                return redirect()->back();
            }
        }catch(\Exception $e){
            DB::rollback();
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            session()->flash('error', 'Account Delete Failed');
            return redirect()->back();
        }
    }
}
