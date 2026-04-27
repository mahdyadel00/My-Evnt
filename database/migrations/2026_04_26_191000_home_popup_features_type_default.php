<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Legacy `home_popup_features.type` is NOT NULL without a default, which breaks Eloquent inserts.
 */
return new class extends Migration
{
    private const TABLE = 'home_popup_features';

    public function up(): void
    {
        if (!Schema::hasTable(self::TABLE) || !Schema::hasColumn(self::TABLE, 'type')) {
            return;
        }

        DB::statement("ALTER TABLE `home_popup_features` MODIFY `type` VARCHAR(32) NOT NULL DEFAULT 'popup'");
    }

    public function down(): void
    {
        if (!Schema::hasTable(self::TABLE) || !Schema::hasColumn(self::TABLE, 'type')) {
            return;
        }

        DB::statement("ALTER TABLE `home_popup_features` MODIFY `type` VARCHAR(32) NOT NULL");
    }
};
