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

        if (! Schema::hasColumn(self::TABLE, 'show_action_buttons')) {
            Schema::table(self::TABLE, function (Blueprint $table): void {
                $table->boolean('show_action_buttons')->default(true);
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable(self::TABLE) || ! Schema::hasColumn(self::TABLE, 'show_action_buttons')) {
            return;
        }

        Schema::table(self::TABLE, function (Blueprint $table): void {
            $table->dropColumn('show_action_buttons');
        });
    }
};
