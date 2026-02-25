<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Services\ManualInstaPayQrService;
use Illuminate\Contracts\View\View;

/**
 * Class InstaPayQrController
 *
 * Display InstaPay manual QR codes for orders.
 */
class InstaPayQrController extends Controller
{
    /**
     * Show InstaPay QR for a specific order.
     *
     * @param Order $order
     * @param ManualInstaPayQrService $qrService
     * @return View
     */
    public function show(Order $order, ManualInstaPayQrService $qrService): View
    {
        $orderReference = $order->order_number ?? (string) $order->id;
        $amount = (float) ($order->payment_amount ?? $order->total ?? 0.0);

        $qrSvg = $qrService->generateQrForOrder($amount, $orderReference);
        $setting = Setting::first();

        return view('Frontend.payments.instapay-qr', [
            'order' => $order,
            'qrSvg' => $qrSvg,
            'setting' => $setting,
        ]);
    }
}


