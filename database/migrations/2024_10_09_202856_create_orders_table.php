<?php

use App\Models\Event;
use App\Models\EventDate;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete()->cascadeOnDelete();
            $table->foreignIdFor(Event::class)->constrained()->cascadeOnDelete()->cascadeOnDelete();;
            $table->foreignIdFor(Ticket::class)->constrained()->cascadeOnDelete()->cascadeOnDelete();
            $table->foreignIdFor(EventDate::class)->constrained()->cascadeOnDelete()->cascadeOnDelete();
            $table->string('order_number')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('payment_amount')->nullable();
            $table->string('payment_currency')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_response')->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->enum('status', ['pending', 'checked', 'exited', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
