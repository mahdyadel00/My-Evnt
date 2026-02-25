<?php

namespace App\Http\Controllers\Frontend\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\AuthOrganization\ChangePassword;
use App\Http\Requests\Frontend\AuthOrganization\Profile;
use App\Models\Archive;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyFollower;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    public function sortedBy(Request $request)
    {
        $sortOrder = in_array($request->input('value'), ['asc', 'desc']) ? $request->input('value') : 'asc';

        // Retrieve the authenticated company and its active events, sorted by creation date
        $company = Auth::guard('company')->user();
        $events = $company->events()->active()->orderBy('created_at', $sortOrder)->get();

        return response()->json($events);
    }

    public function profile()
    {
        $cities = City::get();

        return view('Frontend.organization.account.profile', compact('cities'));
    }

    public function updateProfile(Profile $request)
    {
        try {
            DB::beginTransaction();
            $company = auth()->guard('company')->user();

            $company->update($request->safe()->all());

            if ($request->hasFile('logo')) {
                saveMedia($request, $company);
            }
            if ($request->hasFile('identity_image')) {
                saveMedia($request, $company);
            }
            if ($request->hasFile('commercial_register_image')) {
                saveMedia($request, $company);
            }

            DB::commit();
            session()->flash('success', 'Profile Updated Success');
            return back();
        } catch (\Exception $e) {
            DB::rollback();
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            session()->flash('error', 'Profile Updated Failed');
            return redirect()->back();
        }
    }

    public function updatePassword(ChangePassword $request)
    {
        try {
            DB::beginTransaction();

            $company = auth()->guard('company')->user();

            if (Hash::check($request->current_password, $company->password)) {
                $company->update(['password' => Hash::make($request->password)]);
                DB::commit();
                session()->flash('success', 'Password Updated Success');
                return back();
            } else {
                DB::rollback();
                session()->flash('error', 'Password Updated Failed');
                return back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            session()->flash('error', 'Password Updated Failed');
            return redirect()->back();
        }
    }

    public function archivedEvents()
    {
        $archive = Archive::where('company_id', auth()->guard('company')->id())->pluck('event_id');
        $events = Event::whereIn('id', $archive)->get();

        return view('Frontend.organization.account.archived_events', compact('events'));
    }

    public function deleteAccount()
    {
        try {
            DB::beginTransaction();
            $company = auth()->guard('company')->user();
            $company->delete();

            DB::commit();
            session()->flash('success', 'Account Deleted Successfully');
            return redirect()->route('organization_login');
        } catch (\Exception $e) {
            DB::rollback();
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            session()->flash('error', 'Account Deleted Failed');
            return redirect()->back();
        }
    }

    public function unarchiveEvent($id)
    {
        try {
            DB::beginTransaction();
            $archive = Archive::where('event_id', $id)->first();
            if ($archive) {
                $archive->delete();
                DB::commit();
                session()->flash('success', 'Event Unarchived Successfully');
                return redirect()->back();
            } else {
                DB::rollback();
                session()->flash('error', 'Event Unarchived Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            session()->flash('error', 'Event Unarchived Failed');
            return redirect()->back();
        }
    }

    public function addArchivedEvent($id)
    {
        try {
            DB::beginTransaction();
            $archive = Archive::where('event_id', $id)->first();
            if ($archive) {
                $archive->delete();
                DB::commit();
                session()->flash('success', 'Event Archived Successfully');
                return back();
            } else {
                $archive = Archive::create([
                    'event_id' => $id,
                    'company_id' => auth()->guard('company')->id(),
                ]);
                DB::commit();
                session()->flash('success', 'Event Archived Successfully');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::channel('error')->error($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            session()->flash('error', 'Event Archived Failed');
            return redirect()->back();
        }
    }

    public function eventDetails($id)
    {
        $event = Event::where('id', $id)->first();
        return view('Frontend.organization.account.event_details', compact('event'));
    }

    public function myTickets()
    {
        $events = auth()->guard('company')->events()->active()->get();

        return view('Frontend.contacts.my_tickets', compact('events'));
    }
    public function follow($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userId = Auth::id();

        if (!is_numeric($id)) {
            return response()->json(['error' => 'Invalid company ID'], 400);
        }

        $existingFollow = CompanyFollower::where('company_id', $id)->where('user_id', $userId)->first();

        if ($existingFollow) {
            // Already following - don't do anything or could toggle
            return 'success';
        } else {
            CompanyFollower::create([
                'company_id' => $id,
                'user_id' => $userId,
            ]);
            return 'success';
        }
    }

    public function unfollow($id)
    {
        if (!Auth::check()) {
            return 'error';
        }

        $company_follower = CompanyFollower::where('company_id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($company_follower) {
            $company_follower->delete();
            return 'success';
        }

        return 'success'; // Even if not found, return success
    }
}
