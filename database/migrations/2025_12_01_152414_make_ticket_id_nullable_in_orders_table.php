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
        Schema::table('orders', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['ticket_id']);
            
            // Make ticket_id nullable
            $table->foreignId('ticket_id')->nullable()->change();
            
            // Re-add the foreign key constraint with nullable
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['ticket_id']);
            
            // Make ticket_id required again
            $table->foreignId('ticket_id')->nullable(false)->change();
            
            // Re-add the foreign key constraint
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
        });
    }
};
