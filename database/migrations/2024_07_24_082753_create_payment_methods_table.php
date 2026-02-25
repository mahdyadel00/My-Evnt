<?php

use App\Models\Company;
use App\Models\Country;
use App\Models\Event;
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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['bank_transfer', 'pay_online', 'cache']);
            $table->string('bank_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->string('branch')->nullable();
            $table->string('iban')->nullable();
            $table->string('postal_code')->nullable();
            $table->foreignIdFor(Country::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            //credit card
            $table->string('card_number')->nullable();
            $table->string('card_name')->nullable();
            $table->string('card_expiry')->nullable();
            $table->string('card_cvc')->nullable();
            //cash
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('note')->nullable();
            $table->foreignIdFor(Event::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Company::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
