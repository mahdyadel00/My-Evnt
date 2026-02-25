<?php

declare(strict_types=1);

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SurveyHRExport;
use App\Models\FromServayHR;
use App\Mail\SurveyHRNotificationMail;
use Illuminate\Support\Facades\DB;

/**
 * Controller for managing survey form submissions in the dashboard.
 *
 * @package App\Http\Controllers\BackEnd
 */
class SurveyFormHRController extends Controller
{
    /**
     * Display a listing of the survey forms with search, filter, and pagination.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = FromServayHR::with('event');

        // Search and filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('company_name', 'like', "%$search%")
                    ->orWhere('position', 'like', "%$search%")
                    ->orWhereHas('event', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%$search%")
                            ->orWhere('id', $search);
                    });
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->input('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->input('to'));
        }

        $surveys = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $statuses = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'cancelled' => 'Cancelled',
        ];

        return view('backend.surveys.hr.index', compact('surveys', 'statuses'));
    }

    /**
     * Export survey forms to Excel or CSV.
     *
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function export(Request $request)
    {
        try {
            $fileType = $request->input('type', 'xlsx');
            $fileName = 'survey_forms_' . now()->format('Ymd_His') . ".{$fileType}";
            return Excel::download(new SurveyHRExport($request->all()), $fileName);
        } catch (\Throwable $e) {
            // Log error (simplified to avoid Monolog issues)
            error_log('Survey export failed: ' . $e->getMessage());
            return back()->with('error', 'Export failed. Please try again.');
        }
    }

    /**
     * Update the status of a survey form via AJAX.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:from_servay_hrs,id',
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);
        try {
            $survey = FromServayHR::findOrFail($request->id);
            $survey->status = $request->status;
            $survey->save();
            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        } catch (\Throwable $e) {
            // Log error (simplified to avoid Monolog issues)
            error_log('Survey status update failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update status.'], 500);
        }
    }

    /**
     * Send email to selected survey respondents.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmail(Request $request)
    {
        // $request->validate([
        //     'subject' => 'required|string|max:255',
        //     'message' => 'required|string',
        //     'survey_ids' => 'required|array|min:1',
        //     'survey_ids.*' => 'exists:from_servay_hrs,id',
        // ]);
        try {
            $surveys = FromServayHR::with('event')->whereIn('id', $request->survey_ids)->get();

            if ($surveys->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No surveys found to send emails to.'
                ], 400);
            }

            $emailsSent = 0;
            $errors = [];

            // Process emails in chunks for better performance
            $surveys->chunk(50)->each(function ($chunk) use (&$emailsSent, &$errors, $request) {
                DB::beginTransaction();
                try {
                    foreach ($chunk as $survey) {
                        try {
                            // Validate email address
                            if (!filter_var($survey->email, FILTER_VALIDATE_EMAIL)) {
                                $errors[] = "Invalid email address for {$survey->first_name} {$survey->last_name}: {$survey->email}";
                                continue;
                            }

                            Mail::to($survey->email)->send(new SurveyHRNotificationMail(
                                $request->subject,
                                $request->message,
                                $survey
                            ));
                            $emailsSent++;
                            // Log success (simplified to avoid Monolog issues)
                            error_log("Survey HR email sent successfully - ID: {$survey->id}, Email: {$survey->email}");

                        } catch (\Exception $e) {
                            $errors[] = "Failed to send email to {$survey->first_name} {$survey->last_name} ({$survey->email}): " . $e->getMessage();
                            // Log error (simplified to avoid Monolog issues)
                            error_log("Failed to send survey HR email - ID: {$survey->id}, Email: {$survey->email}, Error: " . $e->getMessage());
                        }
                    }
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    error_log("Chunk processing failed: " . $e->getMessage());
                    throw $e;
                }
            });

            $message = "Successfully sent {$emailsSent} email(s).";
            if (!empty($errors)) {
                $message .= " " . count($errors) . " email(s) failed to send.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'details' => [
                    'sent' => $emailsSent,
                    'failed' => count($errors),
                    'errors' => $errors
                ]
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            // Log error (simplified to avoid Monolog issues)
            error_log("Bulk email sending failed: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send emails. Please try again.'
            ], 500);
        }
    }
}
