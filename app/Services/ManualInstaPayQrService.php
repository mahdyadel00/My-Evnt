<?php

declare(strict_types=1);

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Class ManualInstaPayQrService
 *
 * Generate manual QR codes for InstaPay transfers (no direct InstaPay API integration).
 * This service only encodes payment information in a human-readable format that
 * can be scanned by the customer and used to complete a manual transfer.
 */
class ManualInstaPayQrService
{
    private string $accountName;
    private string $mobileNumber;
    private ?string $instantPaymentAddress;
    private ?string $paymentUrl;

    public function __construct()
    {
        $this->accountName = (string) config('services.instapay.account_name', 'My Store');
        $this->mobileNumber = (string) config('services.instapay.mobile_number', '01000000000');
        $this->instantPaymentAddress = config('services.instapay.ipa');
        $this->paymentUrl = config('services.instapay.payment_url');
    }

    /**
     * Generate QR code SVG string for a specific order.
     *
     * @param float $amount
     * @param string $orderReference
     * @return string
     */
    public function generateQrForOrder(float $amount, string $orderReference): string
    {
        $payload = $this->buildPayload($amount, $orderReference);

        return (string) QrCode::format('svg')
            ->size(250)
            ->generate($payload);
    }

    /**
     * Build the text payload encoded into the QR code.
     *
     * @param float $amount
     * @param string $orderReference
     * @return string
     */
    private function buildPayload(float $amount, string $orderReference): string
    {
        // If a direct InstaPay payment link is configured, use it as a URL in the QR
        if (is_string($this->paymentUrl) && $this->paymentUrl !== '') {
            $separator = str_contains($this->paymentUrl, '?') ? '&' : '?';

            return $this->paymentUrl . $separator . http_build_query([
                'amount' => number_format($amount, 2, '.', ''),
                'order' => $orderReference,
            ]);
        }

        // Fallback: show human-readable payment info
        $identifier = $this->instantPaymentAddress !== null && $this->instantPaymentAddress !== ''
            ? $this->instantPaymentAddress
            : $this->mobileNumber;

        return sprintf(
            "InstaPay Payment\nAccount: %s\nIdentifier: %s\nAmount: %.2f EGP\nOrder: %s",
            $this->accountName,
            $identifier,
            $amount,
            $orderReference
        );
    }
}


