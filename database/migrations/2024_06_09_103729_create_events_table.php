<?php

use App\Models\AdFee;
use App\Models\City;
use App\Models\Company;
use App\Models\Currency;
use App\Models\EventCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(EventCategory::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(EventCategory::class, 'sub_category_id')->nullable()->constrained('event_categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(City::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Currency::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Company::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(AdFee::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->string('upload_by')->nullable();
            $table->string('organized_by')->nullable();
            $table->text('location')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            $table->json('days')->nullable();
            $table->string('platform')->nullable();
            $table->boolean('format')->default(false);
            $table->boolean('is_active')->default(false);
            $table->text('cancellation_policy')->nullable();
            $table->integer('view_count')->default(0);
            $table->string('external_link')->nullable();
            $table->string('link_meeting')->nullable();
            $table->boolean('is_exclusive')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
