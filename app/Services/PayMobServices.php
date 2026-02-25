<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * PayMob Payment Gateway Service
 * 
 * Handles payment processing through PayMob payment gateway
 */
class PayMobServices
{
    private string $PAYMOB_API_KEY;
    private ?string $token = null;
    private ?int $id = null;
    private int $integration_id;
    private float $price;
    private ?string $iframe_token = null;

    /**
     * Initialize PayMob service
     * 
     * @param float $price Payment amount
     * @param string $paymentMethod Payment method (default, value, souhoola)
     */
    public function __construct(float $price, string $paymentMethod = 'default')
    {
        $this->PAYMOB_API_KEY = config('paymob.api_key', 'ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2ljSEp2Wm1sc1pWOXdheUk2TVRjek56Z3lMQ0p1WVcxbElqb2lhVzVwZEdsaGJDSjkuZUhKSXU3LXpJNlctczZuejI2Qmw1MXNVbnRqYmxBSWhoVWNCV084YWJENVk0TDZqVlNoUkczZUlSSXZfeEJaX1NoOVYtWFFnYllxcGJEcDFIMGdRYkE=');
        
        // Ensure integration_id is always an integer
        $this->integration_id = (int) match ($paymentMethod) {
            'value'     => 4897598,
            'souhoola'  => 4897549,
            default     => 5076191,
        };

        $this->price = $price;
    }

    /**
     * Get authentication token from PayMob
     * 
     * @return string|false Authentication token or false on failure
     */
    public function getToken()
    {
        try {
            $response = Http::withHeaders(['content-type' => 'application/json'])
                ->post('https://accept.paymobsolutions.com/api/auth/tokens', [
                    "api_key" => $this->PAYMOB_API_KEY
                ]);

            if ($response->successful() && isset($response->json()['token'])) {
                $this->token = $response->json()['token'];
                return $this->token;
            }

            Log::error('PayMob: Failed to get authentication token', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            return false;
        } catch (Exception $e) {
            dd($e->getMessage());
            Log::error('PayMob: Exception while getting token', [
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Create order and get order ID from PayMob
     * 
     * @return int|null Order ID or null on failure
     */
    public function get_id(): ?int
    {
        try {
            $this->token = $this->getToken();
            
            if (!$this->token) {
                Log::error('PayMob: Failed to get authentication token');
                return null;
            }
            
            // Generate a temporary order number for the API call
            $tempOrderNumber = 'TEMP_' . time() . '_' . rand(1000, 9999);
            
            $response = Http::withHeaders(['content-type' => 'application/json'])
                ->post('https://accept.paymobsolutions.com/api/ecommerce/orders', [
                    "auth_token" => $this->token,
                    "delivery_needed" => "false",
                    "amount_cents" => $this->price * 100,
                    "items" => [
                        [
                            "name" => "Order #" . $tempOrderNumber,
                            "amount_cents" => $this->price * 100,
                            "description" => "Order #" . $tempOrderNumber,
                            "quantity" => "1"
                        ]
                    ]
                ]);
                
            if ($response->successful() && isset($response->json()['id'])) {
                $this->id = $response->json()['id'];
                return $this->id;
            }
            
            Log::error('PayMob: Failed to create order', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);
            
            return null;
        } catch (Exception $e) {
            Log::error('PayMob: Exception while creating order', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }
    /**
     * Create payment key and get iframe token
     * 
     * @param object $user User object with billing information
     * @return string Payment token
     * @throws Exception When payment key creation fails
     */
    public function make_order($user): string
    {
        if (!$this->id) {
            throw new Exception('Order ID is required to create payment key');
        }
        
        if (!$this->token) {
            throw new Exception('Authentication token is required');
        }
        
        try {
            // Safely get city and country information
            $cityName = "NA";
            $countryName = "NA";
            
            if (isset($user->city)) {
                $cityName = $user->city->name ?? "NA";
                if (isset($user->city->country)) {
                    $countryName = $user->city->country->name ?? "NA";
                }
            }
            // dd($user->email,$this->integration_id,$this->token,$this->id,$this->price);
            
            $response = Http::withHeaders(['content-type' => 'application/json'])
                ->post('https://accept.paymobsolutions.com/api/acceptance/payment_keys', [
                    "auth_token"                => $this->token,
                    "expiration"                => 36000,
                    "amount_cents"              => $this->price * 100,
                    "order_id"                  => $this->id,
                    "billing_data"              => [
                        "apartment"             => "NA",
                        "email"                 => $user->email ?? "NA",
                        "floor"                 => "NA",
                        "first_name"            => $user->first_name ?? $user->user_name ?? "Guest",
                        "street"                => "NA",
                        "building"              => "NA",
                        "phone_number"          => $user->phone ?? 'NA',
                        "shipping_method"       => "NA",
                        "postal_code"           => "NA",
                        "city"                  => $cityName ?? "NA",
                        "country"               => $countryName ?? "NA",
                        "last_name"             => $user->last_name ?? $user->user_name ?? "User",
                        "state"                 => "NA"
                    ],
                    "currency"                  => "EGP",
                    "integration_id"            => $this->integration_id,
                ]);

            if ($response->successful() && isset($response->json()['token'])) {
                $this->iframe_token = $response->json()['token'];
                return $this->iframe_token;
            }
            
            Log::error('PayMob: Failed to create payment key', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);
            
            throw new Exception('Failed to create payment key with PayMob');
        } catch (Exception $e) {
            Log::error('PayMob: Exception while creating payment key', [
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}