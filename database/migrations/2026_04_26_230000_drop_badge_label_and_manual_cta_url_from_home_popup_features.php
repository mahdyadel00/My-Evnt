<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE = 'home_popup_features';

    public function up(): void
    {
        if (! Schema::hasTable(self::TABLE)) {
            return;
        }

        Schema::table(self::TABLE, function (Blueprint $table): void {
            if (Schema::hasColumn(self::TABLE, 'badge_label')) {
                $table->dropColumn('badge_label');
            }
            if (Schema::hasColumn(self::TABLE, 'manual_cta_url')) {
                $table->dropColumn('manual_cta_url');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable(self::TABLE)) {
            return;
        }

        Schema::table(self::TABLE, function (Blueprint $table): void {
            if (! Schema::hasColumn(self::TABLE, 'badge_label')) {
                $table->string('badge_label')->nullable();
            }
            if (! Schema::hasColumn(self::TABLE, 'manual_cta_url')) {
                $table->string('manual_cta_url', 2048)->nullable();
            }
        });
    }
};
