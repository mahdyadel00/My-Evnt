<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\User\StoreUserRequest;
use App\Http\Requests\Backend\User\UpadteUserRequest; // Fixed typo
use App\Http\Requests\Backend\User\ImportUserRequest;
use App\Imports\UserImport;
use App\Models\City;
use App\Models\Country;
use App\Models\User;
use App\Models\Role;
use App\Services\SmsService;
use App\Exports\UserExport;
use App\Mail\UserEmail;
use App\Jobs\SendUserEmailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('backend.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::select('id', 'name')->get();
        $roles = Role::pluck('name', 'id'); // Simplified for dropdown
        return view('backend.users.create', compact('countries', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->safe()->except('roles_name', 'password');
            $data['password'] = Hash::make($request->password);
            $data['email_verified_at'] = now();

            $user = User::create($data);

            if ($request->filled('roles_name')) {
                $user->syncRoles($request->roles_name);
                $user->roles_name = implode(', ', $request->roles_name);
                $user->save();
            }

            if ($request->hasFile('image')) {
                // Assuming 'image' is the field name
                $this->saveMedia($request, $user);
            }

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', __('User created successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('store', $e);
            return redirect()->back()->with('error', __('Something went wrong'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::with(['roles', 'media'])->findOrFail($id);
            $countries = Country::select('id', 'name')->get();
            $cities = City::select('id', 'name', 'country_id')->get();
            return view('backend.users.show', compact('user', 'countries', 'cities'));
        } catch (\Exception $e) {
            $this->logError('show', $e);
            return redirect()->back()->with('error', __('User not found or something went wrong'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $user = User::with(['roles', 'media'])->findOrFail($id);
            $countries = Country::select('id', 'name')->get();
            $cities = City::select('id', 'name', 'country_id')->get();
            $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();
            return view('backend.users.edit', compact('user', 'countries', 'cities', 'roles'));
        } catch (\Exception $e) {
            $this->logError('edit', $e);
            return redirect()->back()->with('error', __('User not found or something went wrong'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpadteUserRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $data = $request->safe()->except(['role', 'roles_name']);
            $user->update($data);

            // Handle role assignment
            if ($request->filled('role')) {
                $user->syncRoles([$request->role]);
                $user->roles_name = $request->role;
            } else {
                $user->syncRoles([]);
                $user->roles_name = null;
            }
            $user->save();

            if ($request->hasFile('image')) {
                $this->saveMedia($request, $user);
            }

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', __('User updated successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating user', [
                'message' => $e->getMessage(),
                'user_id' => $id
            ]);
            return redirect()->back()->withInput()->with('error', __('Something went wrong'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $user->delete();

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', __('User deleted successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('destroy', $e);
            return redirect()->back()->with('error', __('Something went wrong'));
        }
    }

    /**
     * Get cities by country for dynamic dropdowns.
     */
    public function getCitiesByCountry(Request $request)
    {
        $cities = City::where('country_id', $request->country_id)->select('id', 'name')->get();
        return response()->json($cities);
    }

    /**
     * Helper method to save media for a user.
     */
    private function saveMedia(Request $request, User $user)
    {
        // Assuming saveMedia is a global helper; replace with your logic if different
        saveMedia($request, $user);
    }

    /**
     * Helper method to log errors consistently.
     */
    private function logError(string $method, \Exception $e)
    {
        Log::channel('error')->error("Error in UserController@{$method}: {$e->getMessage()} on line {$e->getLine()} in file {$e->getFile()}");
    }

    /**
     * Show the form for importing users from Excel.
     */
    public function import()
    {
        return view('backend.users.import');
    }

    /**
     * Store imported users from Excel file.
     */
    public function importStore(ImportUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $file = $request->file('file');
            $import = new UserImport();

            Excel::import($import, $file);

            $failures = $import->failures();
            $errors = $import->getErrors();

            DB::commit();

            $message = __('Users imported successfully');
            $hasErrors = !empty($errors) || $failures->isNotEmpty();

            if ($hasErrors) {
                $errorMessages = [];
                
                foreach ($failures as $failure) {
                    $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
                }

                foreach ($errors as $error) {
                    $errorMessages[] = $error;
                }

                $message .= '. ' . __('Some rows failed to import:') . ' ' . implode('; ', array_slice($errorMessages, 0, 10));
                
                if (count($errorMessages) > 10) {
                    $message .= ' ' . __('and :count more errors', ['count' => count($errorMessages) - 10]);
                }

                return redirect()->route('admin.users.index')
                    ->with('warning', $message)
                    ->with('import_errors', $errorMessages);
            }

            return redirect()->route('admin.users.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('importStore', $e);
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('Failed to import users: :message', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Show the form for sending messages to selected users.
     */
    public function showSendMessageForm(Request $request)
    {
        $userIds = $request->input('user_ids', []);
        
        if (empty($userIds)) {
            return redirect()->route('admin.users.index')
                ->with('error', __('Please select at least one user'));
        }

        // Convert to array if it's a single value
        if (!is_array($userIds)) {
            $userIds = [$userIds];
        }

        // Filter out empty values
        $userIds = array_filter($userIds, function($id) {
            return !empty($id) && is_numeric($id);
        });

        if (empty($userIds)) {
            return redirect()->route('admin.users.index')
                ->with('error', __('Please select at least one user'));
        }

        $users = User::whereIn('id', $userIds)
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->get();

        if ($users->isEmpty()) {
            return redirect()->route('admin.users.index')
                ->with('error', __('No users with phone numbers found. Please select users who have phone numbers.'));
        }

        return view('backend.users.send-message', compact('users'));
    }

    /**
     * Send messages to selected users.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'user_ids'          => ['required', 'array', 'min:1'],
            'user_ids.*'        => ['required', 'integer', 'exists:users,id'],
            'message'           => ['required', 'string', 'max:1000'],
            'type'              => ['required', 'in:whatsapp,sms'],
        ]);

        try {
            $userIds = $request->input('user_ids');
            $message = $request->input('message');
            $type = $request->input('type');

            $users = User::whereIn('id', $userIds)
                ->whereNotNull('phone')
                ->get();

            if ($users->isEmpty()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', __('No users with phone numbers found'));
            }

            $smsService = new SmsService();
            $successCount = 0;
            $failedCount = 0;
            $errors = [];
            $successDetails = [];

            foreach ($users as $user) {
                Log::info("Attempting to send message", [
                    'user_id' => $user->id,
                    'user_name' => $user->user_name,
                    'phone' => $user->phone,
                    'type' => $type,
                    'message_length' => strlen($message)
                ]);

                $result = $smsService->sendCustomMessage($user->phone, $message, $type);
                
                // Log full result for debugging
                Log::info("Message send result", [
                    'user_id' => $user->id,
                    'phone' => $user->phone,
                    'success' => $result['success'] ?? false,
                    'result' => $result
                ]);
                
                if ($result['success']) {
                    $successCount++;
                    $responseId = $result['response'] ?? 'N/A';
                    $successDetails[] = [
                        'user' => $user->user_name,
                        'phone' => $user->phone,
                        'response_id' => $responseId,
                        'message' => $result['message'] ?? 'Sent successfully'
                    ];
                    
                    Log::info("Message sent successfully to user", [
                        'user_id' => $user->id,
                        'user_name' => $user->user_name,
                        'phone' => $user->phone,
                        'type' => $type,
                        'response_id' => $responseId,
                        'api_message' => $result['message'] ?? null
                    ]);
                } else {
                    $failedCount++;
                    $errorMsg = $result['message'] ?? 'Unknown error';
                    $errors[] = "{$user->user_name} ({$user->phone}): {$errorMsg}";
                    
                    Log::error("Failed to send message to user", [
                        'user_id' => $user->id,
                        'user_name' => $user->user_name,
                        'phone' => $user->phone,
                        'type' => $type,
                        'error' => $errorMsg,
                        'full_result' => $result
                    ]);
                }
            }

            $message = __('Messages sent: :success successful, :failed failed', [
                'success' => $successCount,
                'failed' => $failedCount
            ]);

            if ($failedCount > 0) {
                return redirect()->route('admin.users.index')
                    ->with('warning', $message)
                    ->with('send_errors', $errors)
                    ->with('send_details', $successDetails);
            }

            return redirect()->route('admin.users.index')
                ->with('success', $message)
                ->with('send_details', $successDetails);
        } catch (\Exception $e) {
            $this->logError('sendMessage', $e);
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('Failed to send messages: :message', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Export users to Excel.
     */
    public function export()
    {
        try {
            $fileName = 'users_' . date('Y-m-d_His') . '.xlsx';
            return Excel::download(new UserExport(), $fileName);
        } catch (\Exception $e) {
            $this->logError('export', $e);
            return redirect()->back()
                ->with('error', __('Failed to export users: :message', ['message' => $e->getMessage()]));
        }
    }

    /**
     * Show the form for sending emails to selected users.
     */
    public function showSendEmailForm(Request $request)
    {
        $userIds = $request->input('user_ids', []);
        
        if (empty($userIds)) {
            return redirect()->route('admin.users.index')
                ->with('error', __('Please select at least one user'));
        }

        // Convert to array if it's a single value
        if (!is_array($userIds)) {
            $userIds = [$userIds];
        }

        // Filter out empty values
        $userIds = array_filter($userIds, function($id) {
            return !empty($id) && is_numeric($id);
        });

        if (empty($userIds)) {
            return redirect()->route('admin.users.index')
                ->with('error', __('Please select at least one user'));
        }

        $users = User::whereIn('id', $userIds)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->get();

        if ($users->isEmpty()) {
            return redirect()->route('admin.users.index')
                ->with('error', __('No users with email addresses found. Please select users who have email addresses.'));
        }

        return view('backend.users.send-email', compact('users'));
    }

    /**
     * Send emails to selected users.
     */
    public function sendEmail(Request $request)
    {
        $request->validate([
            'user_ids'          => ['required', 'array', 'min:1'],
            'user_ids.*'        => ['required', 'integer', 'exists:users,id'],
            'subject'           => ['required', 'string', 'max:255'],
            'message'           => ['required', 'string'],
        ]);

        try {
            $userIds = $request->input('user_ids');
            $subject = $request->input('subject');
            $message = $request->input('message');

            $users = User::whereIn('id', $userIds)
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->get();

            if ($users->isEmpty()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', __('No users with email addresses found'));
            }

            $totalUsers = $users->count();

            // Dispatch one job per user with a small delay between each
            $delaySeconds = 0;
            $delayStep    = 5; // 5 seconds between each email

            foreach ($users as $user) {
                SendUserEmailJob::dispatch($user->id, $subject, $message)
                    ->delay(now()->addSeconds($delaySeconds));

                $delaySeconds += $delayStep;
            }

            Log::info('User emails queued for sending', [
                'total_users' => $totalUsers,
                'delay_step_seconds' => $delayStep,
            ]);

            $message = __('Emails queued successfully! :count email(s) will be sent in the background.', [
                'count' => $totalUsers
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', $message)
                ->with('info', __('The emails are being processed in the background. Please check the logs for detailed results.'));
        } catch (\Exception $e) {
            $this->logError('sendEmail', $e);
            
            return redirect()->back()
                ->withInput()
                ->with('error', __('Failed to send emails: :message', ['message' => $e->getMessage()]));
        }
    }
}