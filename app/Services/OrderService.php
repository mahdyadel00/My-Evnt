<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\User;
use App\Models\UserTicket;
use App\Models\TicketQr;
use App\Models\Notification;
use App\Mail\OrderMail;
use App\Mail\SurveyBookingMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
/**
 * Order Service
 * 
 * Handles order creation, payment processing, and ticket generation
 */
class OrderService
{
    /**
     * Create a new order with payment processing
     * 
     * @param object $request Order request data
     * @return array Order details with payment URL
     * @throws Exception When order creation or payment processing fails
     */
    public function createOrder($request): array
    {// Determine user ID if logged in
        
        $userId = Auth::check() ? Auth::id() : null;

        // Build validation rules
        $rules = [
            'event_id'       => 'required|exists:events,id',
            'ticket_id'      => 'required|exists:tickets,id',
            'quantity'       => 'required|integer|min:1',
            'total_price'    => 'required|numeric|min:0',
            'payment_method' => 'nullable|string|in:default,value,souhoola,instapay',
        ];

        // Email is required only if user is not logged in
        if (!Auth::check()) {
            $rules['email'] = ['required','email',];
        } else {
            // If logged in, email is optional but must be valid if provided
            $rules['email'] = ['nullable','email',];
        }

        // Validate request
        $request->validate($rules);

        // Get event and ticket
        $event = Event::active()->findOrFail($request->event_id);
        $ticket = Ticket::where('id', $request->ticket_id)
            ->where('event_id', $event->id)
            ->firstOrFail();

        // Get or create user
        $user = Auth::check() ? Auth::user() : $this->getOrCreateUser($request, $event);

        // Ensure user has valid email for payment processing
        if (empty($user->email) || !filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            Log::error('OrderService: User missing valid email', [
                'user_id' => $user->id,
                'email' => $user->email ?? null
            ]);
            throw new Exception('Valid email address is required for payment processing. Please update your profile.');
        }

        // Initialize payment
        $total = (float) $request->total_price;
        $quantity = (int) $request->quantity;
        $paymentMethod = $request->payment_method ?? 'default';
        $paymentId = null;

        // Create payment order with PayMob if not free and not InstaPay manual
        $paymob = null;
        if ($ticket->price > 0 && $paymentMethod !== 'instapay') {
            try {
                $paymob = new PayMobServices($total, $paymentMethod);
                $paymentId = $paymob->get_id();

                if (!$paymentId) {
                    throw new Exception('Failed to create payment order with PayMob');
                }
            } catch (Exception $e) {
                Log::error('Payment gateway error', [
                    'message' => $e->getMessage(),
                    'user_id' => $user->id,
                    'event_id' => $event->id
                ]);
                throw new Exception('Payment gateway error: ' . $e->getMessage());
            }
        }

        // Create order
        $status = $ticket->price == 0 ? 'checked' : 'pending';

        try {
            $order = Order::create([
                'user_id'               => $user->id,
                'event_id'              => $event->id,
                'event_date_id'         => $request->date_id ?? null,
                'ticket_id'             => $ticket->id,
                'total'                 => $total,
                'quantity'              => $quantity,
                'status'                => $status,
                'order_number'          => $paymentId ?? rand(1000, 9999),
                'payment_id'            => $paymentId ?? ($ticket->price == 0 ? 'Free' : 'InstaPay'),
                'payment_method'        => $paymentMethod,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            Log::error('Order creation failed', [
                'message'               => $e->getMessage(),
                'user_id'               => $user->id,
                'event_id'              => $event->id
            ]);
            throw $e;
        }

        // Send confirmation email
        $this->sendConfirmationEmail($event, $user, $order);

        // Handle free tickets
        if ($ticket->price == 0) {
            $this->finalizeOrder($user, $ticket->id, $quantity);
            return [
                'url'                   => null,
                'order_id'              => $order->order_number,
                'payment_method'        => $paymentMethod,
            ];
        }

        // Paid tickets: handle InstaPay manual vs PayMob
        if ($paymentMethod === 'instapay') {
            // For manual InstaPay, generate ticket QR codes but redirect to InstaPay QR page
            $this->generateTicketQrs($ticket->id, $quantity);

            return [
                'url' => route('orders.instapay_qr', ['order' => $order->id]),
                'order_id' => $order->order_number,
                'payment_method' => $paymentMethod,
            ];
        }

        // Paid tickets through PayMob
        try {
            $this->generateTicketQrs($ticket->id, $quantity);
            $paymentUrl = $paymob?->make_order($user);

            return [
                'url' => "https://accept.paymob.com/api/acceptance/iframes/381139?payment_token={$paymentUrl}",
                'order_id' => $order->order_number,
                'payment_method' => $paymentMethod,
            ];
        } catch (Exception $e) {
            Log::error('Post-order processing failed', [
                'message' => $e->getMessage(),
                'order_id' => $order->id
            ]);
            throw $e;
        }
    }

    /**
     * Send confirmation email to user
     * 
     * @param Event $event The event
     * @param User $user The user
     * @param Order $order The order
     * @return void
     */
    private function sendConfirmationEmail(Event $event, User $user, Order $order): void
    {
        try {
            if ($event->format == 1) {
                Mail::to($user->email)->send(new SurveyBookingMail($event, $user));
            } else {
                Mail::to($user->email)->send(new OrderMail($order));
            }
        } catch (Exception $e) {
            Log::warning('Email sending failed', [
                'message' => $e->getMessage(),
                'user_id' => $user->id,
                'order_id' => $order->id
            ]);
        }
    }

    /**
     * Get existing user or create new one
     * 
     * @param object $request Request with user data
     * @return User The user instance
     * @throws Exception When user creation fails
     */
    private function getOrCreateUser($request, $event): User
    {
        try {
            return User::create([
                'first_name'                => $request->first_name,
                'middle_name'               => $request->middle_name,
                'last_name'                 => $request->last_name,
                'email'                     => $request->email,
                'phone'                     => $request->phone,
                'password'                  => Hash::make(Str::random(16)),
                'api_token'                 => rand(1000, 9999),
                'email_verified_at'         => now(),
                'city_id'                   => $request->city_id ?? null,
                'type'                      => $event->name ?? 'null',
            ]);

            // if ($request->hasFile('card_photo')) {
            //     saveMedia($request, $user);
            // }

        } catch (Exception $e) {
            Log::error('User creation failed', [
                'message' => $e->getMessage(),
                'email' => $request->email
            ]);
            throw $e;
        }
    }

    /**
     * Finalize free ticket order
     * 
     * @param User $user The user
     * @param int $ticketId The ticket ID
     * @param int $quantity Number of tickets
     * @return void
     */
    private function finalizeOrder(User $user, int $ticketId, int $quantity): void
    {
        UserTicket::create([
            'ticket_id' => $ticketId,
            'user_id' => $user->id,
            'quantity' => $quantity,
        ]);

        $this->generateTicketQrs($ticketId, $quantity);

        Notification::create([
            'user_id' => $user->id,
            'message' => 'Your order has been successfully created',
            'title' => 'Order Confirmation',
            'status' => 'sent',
        ]);
    }

    /**
     * Generate QR codes for tickets
     * 
     * @param int $ticketId The ticket ID
     * @param int $quantity Number of QR codes to generate
     * @return void
     */
    private function generateTicketQrs(int $ticketId, int $quantity): void
    {
        for ($i = 0; $i < $quantity; $i++) {
            TicketQr::create([
                'ticket_id' => $ticketId,
                'qr_code' => Str::uuid(),
            ]);
        }
    }
}