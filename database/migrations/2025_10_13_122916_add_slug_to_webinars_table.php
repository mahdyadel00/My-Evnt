<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Webinar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('webinars', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('webinar_name');
        });

        // Generate slugs for existing webinars
        $webinars = DB::table('webinars')->get();
        foreach ($webinars as $webinar) {
            $slug = Str::slug($webinar->webinar_name);
            $count = 1;
            $originalSlug = $slug;
            
            // Check if slug exists and make it unique
            while (DB::table('webinars')->where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            
            DB::table('webinars')
                ->where('id', $webinar->id)
                ->update(['slug' => $slug]);
        }

        // Now make slug unique
        Schema::table('webinars', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webinars', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
