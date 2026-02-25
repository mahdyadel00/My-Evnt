<?php

use App\Models\City;
use App\Models\Country;
use App\Models\EventCategory;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(City::class)->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(EventCategory::class)->nullable()->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(EventCategory::class, 'sub_category_id')->nullable()->constrained('event_categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('user_name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone')->nullable();
            $table->string('api_token')->nullable();
            $table->string('about')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->integer('login_count')->default(0)->nullable();
            $table->string('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('social_id')->nullable();
            $table->string('social_type')->nullable();
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(0);
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
