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
        Schema::create('social_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Follow us');
            $table->string('instagram_handle')->default('@Beautics lab');
            $table->string('instagram_link')->default('https://www.instagram.com');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_galleries');
    }
};
