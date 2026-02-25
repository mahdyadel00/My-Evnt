<?php

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
        Schema::create('terms_condittions', function (Blueprint $table) {
            $table->id();
            $table->text('terms_condition');
            $table->text('privacy_policy');
            $table->text('about_us');
            $table->text('shipping_payment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terms_condittions');
    }
};
