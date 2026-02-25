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
        Schema::table('from_servay_h_r_s', function (Blueprint $table) {
            $table->string('job_title')->nullable()->after('phone');
            $table->string('organization')->nullable()->after('job_title');
            $table->enum('ticket_type', ['attendee', 'startups'])->nullable()->after('organization');
            $table->enum('attendee_type', ['attendee', 'mentorship'])->nullable()->after('ticket_type');
            $table->string('mentorship_track')->nullable()->after('attendee_type');
            $table->string('startup_file')->nullable()->after('mentorship_track');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('from_servay_h_r_s', function (Blueprint $table) {
            $table->dropColumn([
                'job_title',
                'organization',
                'ticket_type',
                'attendee_type',
                'mentorship_track',
                'startup_file'
            ]);
        });
    }
};
