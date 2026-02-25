<?php

declare(strict_types=1);

use App\Models\EventDate;
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
        Schema::table('tickets', function (Blueprint $table) {
            // Add event_date_id column after event_id
            $table->foreignIdFor(EventDate::class)
                ->nullable()
                ->after('event_id')
                ->constrained('event_dates')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['event_date_id']);
            $table->dropColumn('event_date_id');
        });
    }
};
