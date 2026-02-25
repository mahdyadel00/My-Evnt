<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\EventDate;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            // Get some existing data
            $users = User::where('is_active', true)->inRandomOrder()->limit(10)->get();
            $events = Event::where('is_active', true)->with(['tickets', 'eventDates'])->inRandomOrder()->limit(5)->get();

            if ($users->isEmpty() || $events->isEmpty()) {
                $this->command->info('No users or events found. Please seed users and events first.');
                return;
            }

            $orderStatuses = ['pending', 'checked', 'exited', 'cancelled'];
            $paymentStatuses = ['pending', 'completed', 'failed', 'refunded'];
            $paymentMethods = ['cash', 'credit_card', 'bank_transfer', 'paypal'];

            $orderNumber = 1;

            foreach ($events as $event) {
                // Skip if event has no tickets or dates
                if ($event->tickets->isEmpty() || $event->eventDates->isEmpty()) {
                    continue;
                }

                // Create 2-5 orders per event
                $ordersCount = rand(2, 5);

                for ($i = 0; $i < $ordersCount; $i++) {
                    $user = $users->random();
                    $ticket = $event->tickets->random();
                    $eventDate = $event->eventDates->random();
                    $quantity = rand(1, 3);
                    $total = $ticket->price * $quantity;

                    $status = $orderStatuses[array_rand($orderStatuses)];
                    $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
                    
                    // Ensure logical consistency
                    if ($status === 'exited') {
                        $paymentStatus = 'completed';
                    } elseif ($status === 'cancelled') {
                        $paymentStatus = in_array($paymentStatus, ['pending', 'failed']) ? $paymentStatus : 'failed';
                    }

                    $order = Order::create([
                        'user_id' => $user->id,
                        'event_id' => $event->id,
                        'ticket_id' => $ticket->id,
                        'event_date_id' => $eventDate->id,
                        'order_number' => 'ORD-' . date('Ymd') . '-' . str_pad((string)$orderNumber, 6, '0', STR_PAD_LEFT),
                        'quantity' => $quantity,
                        'total' => $total,
                        'payment_amount' => $total,
                        'payment_currency' => 'EGP',
                        'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                        'status' => $status,
                        'payment_status' => $paymentStatus,
                        'payment_reference' => 'SEED-' . time() . '-' . $orderNumber,
                        'payment_response' => json_encode([
                            'created_by' => 'seeder',
                            'created_at' => now(),
                            'notes' => $this->getRandomNote($status, $paymentStatus),
                            'test_data' => true
                        ]),
                        'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
                    ]);

                    $orderNumber++;

                    $this->command->info("Created order: {$order->order_number} for user: {$user->email}");
                }
            }

            DB::commit();
            $this->command->info('Orders seeded successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding orders: ' . $e->getMessage());
        }
    }

    /**
     * Get random note based on status
     */
    private function getRandomNote(string $status, string $paymentStatus): string
    {
        $notes = [
            'pending' => [
                'New order awaiting confirmation',
                'New customer - needs follow up',
                'Order placed via phone',
            ],
            'checked' => [
                'Customer checked in successfully',
                'Order confirmed and customer verified',
                'Check-in completed at event',
            ],
            'exited' => [
                'Order completed successfully',
                'Customer attended the event',
                'Tickets delivered to customer',
            ],
            'cancelled' => [
                'Order cancelled upon customer request',
                'Payment not completed on time',
                'Cancelled due to emergency circumstances',
            ],
        ];

        if ($paymentStatus === 'failed') {
            return 'Payment failed - ' . ($notes[$status][array_rand($notes[$status])] ?? 'No details available');
        }

        if ($paymentStatus === 'refunded') {
            return 'Amount refunded - ' . ($notes[$status][array_rand($notes[$status])] ?? 'No details available');
        }

        return $notes[$status][array_rand($notes[$status])] ?? 'No additional notes';
    }
}