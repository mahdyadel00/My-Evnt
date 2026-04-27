<?php

declare(strict_types=1);

use App\Models\Event;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Homepage promotional popup (separate from `features` which is tied to packages).
     */
    public function up(): void
    {
        if (Schema::hasTable('home_popup_features')) {
            return;
        }

        Schema::create('home_popup_features', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Event::class)->nullable()->constrained()->nullOnDelete();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('badge_label')->nullable();
            $table->string('image_path')->nullable();
            $table->string('manual_location')->nullable();
            $table->string('manual_datetime_label')->nullable();
            $table->string('manual_cta_url')->nullable();
            $table->string('cta_label')->default('Get Ticket');
            $table->string('dismiss_label')->default('Maybe Later');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_popup_features');
    }
};
