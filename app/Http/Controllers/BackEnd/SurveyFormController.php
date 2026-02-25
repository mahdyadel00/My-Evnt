<?php

declare(strict_types=1);

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\FormServay;
use App\Services\PayMobServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SurveyExport;

/**
 * Controller for managing survey form submissions in the dashboard.
 *
 * @package App\Http\Controllers\BackEnd
 */
class SurveyFormController extends Controller
{
    /**
     * Display a listing of the survey forms with search, filter, and pagination.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = FormServay::with('event');

        // Search and filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhereHas('event', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%$search%")
                            ->orWhere('id', $search);
                    });
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('session_type')) {
            $query->where('session_type', $request->input('session_type'));
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
        $sessionTypes = FormServay::getSessionPricing();

        return view('backend.surveys.index', compact('surveys', 'statuses', 'sessionTypes'));
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
            return Excel::download(new SurveyExport($request->all()), $fileName);
        } catch (\Throwable $e) {
            Log::error('Survey export failed', ['error' => $e->getMessage()]);
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
            'id' => 'required|exists:form_servays,id',
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);
        try {
            $survey = FormServay::findOrFail($request->id);
            $survey->status = $request->status;
            $survey->save();
            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        } catch (\Throwable $e) {
            Log::error('Survey status update failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to update status.'], 500);
        }
    }

    /**
     * Show payment page with iframe for a specific survey.
     *
     * @param int $id Survey ID
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showPayment($id)
    {
        try {
            $survey = FormServay::with('event')->findOrFail($id);

            // Check if survey is already confirmed
            if ($survey->status === 'confirmed') {
                return view('backend.surveys.payment_completed', compact('survey'))
                    ->with('message', 'This payment has already been completed.');
            }

            // Generate payment token using PayMobServices
            $paymentToken = $this->generatePaymentToken($survey);

            if (!$paymentToken) {
                return redirect()->back()->with('error', 'Failed to generate payment token. Please try again.');
            }

            return redirect()->to("https://accept.paymob.com/api/acceptance/iframes/381139?payment_token={$paymentToken}");

        } catch (\Throwable $e) {
            Log::error('Survey payment page error', ['error' => $e->getMessage(), 'survey_id' => $id]);
            return redirect()->back()->with('error', 'Survey not found or invalid.');
        }
    }

    /**
     * Generate payment token using PayMobServices.
     *
     * @param FormServay $survey
     * @return string|null
     */
    private function generatePaymentToken(FormServay $survey): ?string
    {
        try {
            // Create PayMobServices instance with default integration
            $payMobService = new PayMobServices($survey->total_amount, $survey->payment_method);

            // Get auth token
            if (!$payMobService->getToken()) {
                Log::error('Failed to get PayMob auth token', ['survey_id' => $survey->id]);
                return null;
            }

            // Get order ID
            if (!$payMobService->get_id()) {
                Log::error('Failed to create PayMob order', ['survey_id' => $survey->id]);
                return null;
            }

            // Create fake user object for PayMob (since survey doesn't have full user object)
            $user = (object) [
                'email'                     => $survey->email,
                'first_name'                => $survey->first_name,
                'last_name'                 => $survey->last_name,
                'phone'                     => $survey->phone,
                'user_name'                 => $survey->full_name,
                'city'                      => null,
                'payment_method'            => $survey->payment_method,
            ];

            // Generate payment token
            $iframeToken = $payMobService->make_order($user);

            if (!$iframeToken) {
                Log::error('Failed to generate PayMob iframe token', ['survey_id' => $survey->id]);
                return null;
            }

            return $iframeToken;

        } catch (\Throwable $e) {
            Log::error('PayMob token generation failed', [
                'error' => $e->getMessage(),
                'survey_id' => $survey->id,
                'amount' => $survey->total_amount
            ]);
            return null;
        }
    }

    /**
     * Handle PayMob payment callback.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function paymentCallback(Request $request)
    {
        try {
            Log::info('PayMob callback received', $request->all());

            // Get transaction data from callback
            $transactionId = $request->input('id');
            $success = $request->input('success') === 'true';
            $orderId = $request->input('order.id');
            $amountCents = $request->input('amount_cents');

            if ($success && $transactionId) {
                // Find survey by order amount (this is a simple approach)
                // You might want to store order_id in survey table for better tracking
                $amount = $amountCents / 100; // Convert cents to EGP

                $survey = FormServay::where('total_amount', $amount)
                    ->where('status', 'pending')
                    ->first();

                if ($survey) {
                    $survey->status = 'confirmed';
                    $survey->save();

                    Log::info('Payment confirmed for survey', [
                        'survey_id' => $survey->id,
                        'transaction_id' => $transactionId,
                        'amount' => $amount
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Payment confirmed successfully'
                    ]);
                }
            }

            Log::warning('Payment callback processed but no survey updated', [
                'success' => $success,
                'transaction_id' => $transactionId,
                'amount' => $amountCents / 100
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment not confirmed'
            ]);

        } catch (\Throwable $e) {
            Log::error('Payment callback error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Callback processing failed'
            ], 500);
        }
    }

    /**
     * Check payment status for a survey.
     *
     * @param int $id Survey ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPaymentStatus($id)
    {
        try {
            $survey = FormServay::findOrFail($id);

            return response()->json([
                'success' => true,
                'status' => $survey->status,
                'is_confirmed' => $survey->status === 'confirmed',
                'message' => $survey->status === 'confirmed' ? 'Payment confirmed' : 'Payment pending'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Survey not found'
            ], 404);
        }
    }

    /**
     * Show payment iframe directly (for WhatsApp link).
     *
     * @param int $id Survey ID
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showPaymentIframe($id)
    {
        try {
            $survey = FormServay::with('event')->findOrFail($id);

            // Check if survey is already confirmed
            if ($survey->status === 'confirmed') {
                return view('backend.surveys.payment_iframe_completed', compact('survey'))
                    ->with('message', 'This payment has already been completed.');
            }

            // Generate payment token using PayMobServices
            $paymentToken = $this->generatePaymentToken($survey);

            if (!$paymentToken) {
                return view('backend.surveys.payment_iframe_error', compact('survey'))
                    ->with('error', 'Failed to generate payment token. Please contact support.');
            }

            return view('backend.surveys.payment_iframe', compact('survey', 'paymentToken'));
        } catch (\Throwable $e) {
            Log::error('Payment iframe page error', ['error' => $e->getMessage(), 'survey_id' => $id]);
            return view('backend.surveys.payment_iframe_error')->with('error', 'Survey not found or invalid.');
        }
    }

    /**
     * Show payment completed page.
     *
     * @param int $id Survey ID
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showPaymentCompleted($id)
    {
        try {
            $survey = FormServay::with('event')->findOrFail($id);

            return view('backend.surveys.payment_completed', compact('survey'));
        } catch (\Throwable $e) {
            Log::error('Payment completed page error', ['error' => $e->getMessage(), 'survey_id' => $id]);
            return redirect()->back()->with('error', 'Survey not found or invalid.');
        }
    }
}
