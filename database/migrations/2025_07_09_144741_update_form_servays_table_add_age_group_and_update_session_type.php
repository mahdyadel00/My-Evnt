<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('form_servays', function (Blueprint $table) {
            // Add age_group column
            $table->string('age_group')->nullable()->after('session_type');

            // Drop the old enum constraint and recreate with new values
            $table->dropColumn('session_type');
        });

        // Add the new session_type column with updated enum values
        Schema::table('form_servays', function (Blueprint $table) {
            $table->enum('session_type', ['practice', 'beginner', 'advanced'])->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_servays', function (Blueprint $table) {
            // Remove age_group column
            $table->dropColumn('age_group');

            // Drop the new enum constraint and recreate with old values
            $table->dropColumn('session_type');
        });

        // Restore the old session_type column with original enum values
        Schema::table('form_servays', function (Blueprint $table) {
            $table->enum('session_type', ['kids', 'adult', 'beginner'])->after('phone');
        });
    }
};
