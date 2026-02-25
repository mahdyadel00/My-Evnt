<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ConfirmEmail;
use App\Mail\ResetPassword;
use Illuminate\Http\Request;
use App\Http\Requests\Frontend\Auth\{
    ChangePassword,
    CreateUser,
    ForgotPassword,
    Login,
    Profile,
    Register
};
use App\Models\{
    Event,
    EventCategory,
    EventFavourite,
    Setting,
    User,
};
use Illuminate\Support\Facades\{
    Auth,
    Hash,
    Mail,
    DB,
    Log,
};

class AuthController extends Controller
{
    public function login()
    {
        $setting = Setting::first();

        return view('Frontend.auth.login', compact('setting'));
    }

    public function postLogin(Login $request)
    {
        try {
            DB::beginTransaction();
            if (Auth::attempt($request->safe()->only('email', 'password'))) {
                if (!auth()->user()->email_verified_at) {
                    DB::rollback();
                    session()->flash('error', 'Please verify your email');
                    return redirect()->back();
                }
                DB::commit();
                session()->flash('success', 'Login Success');
                return redirect()->route('home');
            } else {
                DB::rollback();
                session()->flash('error', 'Login Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            session()->flash('error', 'Login Failed');
            return redirect()->back();
        }
    }

    public function getSubCategoryList(Request $request)
    {
        $subcategories = EventCategory::where('parent_id', $request->category_id)->get();

        return response()->json($subcategories);
    }

    public function register()
    {
        $categories = EventCategory::where('parent_id', null)->get();
        $subcategories = EventCategory::where('parent_id', '!=', null)->get();
        $setting = Setting::first();

        return view('Frontend.auth.register', compact('setting', 'categories', 'subcategories'));
    }

    public function postRegister(Register $request)
    {
        try {
            DB::beginTransaction();
            $user = User::create($request->safe()->merge([
                'password' => Hash::make($request->password),
                'api_token' => rand(1000, 9999),
                'email_verified_at' => now(),
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
            ])->all());

            if ($user) {

                //login user
                Auth::login($user);
                // send email
//                Mail::to($user->email)->send(new ConfirmEmail($user->api_token));
                DB::commit();
                return redirect()->route('login');

            } else {
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            session()->flash('error', 'Registration Failed');
            return redirect()->back();
        }
    }

    public function createUser(CreateUser $request)
    {
        try {
            DB::beginTransaction();
            $user = User::create([
                'first_name'                => $request->first_name,
                'last_name'                 => $request->last_name,
                'email'                     => $request->email,
                'phone'                     => $request->phone,
                'api_token'                 => rand(1000, 9999),
                'email_verified_at'         => now(),
            ]);
            if ($user) {
                // send email
//            Mail::to($user->email)->send(new ConfirmEmail($user->api_token));
//            DB::commit();
                return response()->json(true);
            } else {
                DB::rollback();
                return response()->json(false);
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            session()->flash('error', 'User Created Failed');
            return response()->json(false);
        }

    }

    public function confirmationEmail($token)
    {
        $user = User::where('api_token', $token)->first();
        $setting = Setting::first();

        return view('Frontend.auth.confirmation_email', compact('user', 'setting'));

    }

    public function postConfirmationEmail(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = User::where('api_token', $request->code)->first();
            if ($user) {
                $user->update([
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);
                DB::commit();
                session()->flash('success', 'Email Confirmation Success');
                return redirect()->route('login');
            } else {
                DB::rollback();
                session()->flash('error', 'Email Confirmation Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            session()->flash('error', 'Email Confirmation Failed');
            return redirect()->back();
        }
    }

    public function forgotPassword()
    {
        $setting = Setting::first();

        return view('Frontend.auth.forgot_password', compact('setting'));
    }

    public function postForgotPassword(ForgotPassword $request)
    {
        try {
            DB::beginTransaction();
            $user = User::where('email', $request->email)->first();
            if ($user) {

                $user->update(['api_token' => rand(1000, 9999)]);
                // send email
                Mail::to($user->email)->send(new ResetPassword($user->api_token));
                DB::commit();
                session()->flash('success', 'Password Reset Success');
                return redirect()->route('login');
            } else {
                DB::rollback();
                session()->flash('error', 'Password Reset Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            session()->flash('error', 'Password Reset Failed');
            return redirect()->back();
        }
    }

    public function resetPassword($token)
    {
        $setting = Setting::first();

        return view('Frontend.auth.reset_password', compact('setting', 'token'));
    }

    public function postResetPassword(\App\Http\Requests\Frontend\Auth\ResetPassword $request)
    {
        try {
            DB::beginTransaction();
            $user = User::where('api_token', $request->code)->first();
            if ($user) {
                $user->update([
                    'password' => Hash::make($request->password),
                    'email_verified_at' => now(),
                ]);
                DB::commit();
                session()->flash('success', 'Password Reset Success');
                return redirect()->route('login');
            } else {
                DB::rollback();
                session()->flash('error', 'Password Reset Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            session()->flash('error', 'Password Reset Failed');
            return redirect()->back();
        }
    }

    public function profile()
    {
        $setting = Setting::first();

        return view('Frontend.auth.profile', compact('setting'));
    }

    public function updateProfile(Profile $request)
    {
        try {
            DB::beginTransaction();
            $user = User::find(auth()->id());
            if ($user) {
                $user->update($request->safe()->all());
                //user company
//                $user->company()->updateOrCreate(
//                    ['user_id' => $user->id],
//                    [
//                        'name'        => $request->company_name,
//                        'email'       => $request->company_email,
//                        'address'     => $request->company_address,
//                        'website'     => $request->company_website,
//                    ]
//                );

                DB::commit();
                session()->flash('success', 'Profile Updated Success');
                return redirect()->route('profile');
            } else {
                DB::rollback();
                session()->flash('error', 'Profile Updated Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            session()->flash('error', 'Profile Updated Failed');
            return redirect()->back();
        }
    }

    public function updatePassword(ChangePassword $request)
    {
        $user = User::find(auth()->id());
        if ($user) {
            if (Hash::check($request->current_password, $user->password)) {
                $user->update(['password' => Hash::make($request->password)]);
                session()->flash('success', 'Password Updated Success');
                return redirect()->route('profile');
            } else {
                session()->flash('error', 'Old Password is incorrect');
                return redirect()->back();
            }
        } else {
            session()->flash('error', 'Password Updated Failed');
            return redirect()->back();
        }
    }

    public function deleteAccount()
    {
        try {
            DB::beginTransaction();
            $user = User::find(auth()->id());
            if ($user) {
                $user->delete();
                DB::commit();
                session()->flash('success', 'Account Deleted Success');
                return redirect()->route('home');
            } else {
                DB::rollback();
                session()->flash('error', 'Account Deleted Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            session()->flash('error', 'Account Deleted Failed');
            return redirect()->back();
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('home');
    }

    public function myTickets()
    {
        return view('Frontend.account.my_tickets');
    }

    public function myWishlist()
    {
        $categories = EventCategory::get();
        $favourites = auth()->user()->favourites;

        return view('Frontend.account.my_wishlist', compact('categories', 'favourites'));
    }

    public function getEventsByCategory(Request $request)
    {
        $events = Event::where('is_active', true)->with(['currency', 'media'])->where('event_category_id', $request->category_id)->get();
        return response()->json($events);
    }


    public function unfavourite(Request $request, $id)
    {
        $favourite = EventFavourite::where('event_id', $id)->where('user_id', auth()->id())->first();
        if ($favourite) {
            $favourite->delete();
            session()->flash('success', 'Event removed from wishlist');
            return back();
        } else {
            session()->flash('error', 'Event not found in wishlist');
            return back();
        }
    }
}
