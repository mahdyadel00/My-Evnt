<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Older installs may already have `home_popup_features` with a different column set,
 * causing the guarded create migration to skip while inserts expect the new schema.
 */
return new class extends Migration
{
    private const TABLE = 'home_popup_features';

    public function up(): void
    {
        if (!Schema::hasTable(self::TABLE)) {
            return;
        }

        $addImagePath = !Schema::hasColumn(self::TABLE, 'image_path');
        $addManualLocation = !Schema::hasColumn(self::TABLE, 'manual_location');
        $addManualDatetime = !Schema::hasColumn(self::TABLE, 'manual_datetime_label');
        $addManualCta = !Schema::hasColumn(self::TABLE, 'manual_cta_url');

        Schema::table(self::TABLE, function (Blueprint $table) use (
            $addImagePath,
            $addManualLocation,
            $addManualDatetime,
            $addManualCta
        ): void {
            if ($addImagePath) {
                $table->string('image_path')->nullable();
            }
            if ($addManualLocation) {
                $table->string('manual_location')->nullable();
            }
            if ($addManualDatetime) {
                $table->string('manual_datetime_label')->nullable();
            }
            if ($addManualCta) {
                $table->string('manual_cta_url', 2048)->nullable();
            }
        });

        if (Schema::hasColumn(self::TABLE, 'location_label') && Schema::hasColumn(self::TABLE, 'manual_location')) {
            DB::table(self::TABLE)
                ->whereNull('manual_location')
                ->whereNotNull('location_label')
                ->where('location_label', '!=', '')
                ->update(['manual_location' => DB::raw('`location_label`')]);
        }

        if (Schema::hasColumn(self::TABLE, 'schedule_label') && Schema::hasColumn(self::TABLE, 'manual_datetime_label')) {
            DB::table(self::TABLE)
                ->whereNull('manual_datetime_label')
                ->whereNotNull('schedule_label')
                ->where('schedule_label', '!=', '')
                ->update(['manual_datetime_label' => DB::raw('`schedule_label`')]);
        }

        if (Schema::hasColumn(self::TABLE, 'cta_url') && Schema::hasColumn(self::TABLE, 'manual_cta_url')) {
            DB::table(self::TABLE)
                ->whereNull('manual_cta_url')
                ->whereNotNull('cta_url')
                ->where('cta_url', '!=', '')
                ->update(['manual_cta_url' => DB::raw('`cta_url`')]);
        }

        if (Schema::hasColumn(self::TABLE, 'image_url') && Schema::hasColumn(self::TABLE, 'image_path')) {
            DB::table(self::TABLE)
                ->whereNull('image_path')
                ->whereNotNull('image_url')
                ->where('image_url', '!=', '')
                ->where(function ($q): void {
                    $q->where('image_url', 'not like', 'http://%')
                        ->where('image_url', 'not like', 'https://%');
                })
                ->update(['image_path' => DB::raw('`image_url`')]);
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable(self::TABLE)) {
            return;
        }

        Schema::table(self::TABLE, function (Blueprint $table): void {
            if (Schema::hasColumn(self::TABLE, 'manual_cta_url')) {
                $table->dropColumn('manual_cta_url');
            }
            if (Schema::hasColumn(self::TABLE, 'manual_datetime_label')) {
                $table->dropColumn('manual_datetime_label');
            }
            if (Schema::hasColumn(self::TABLE, 'manual_location')) {
                $table->dropColumn('manual_location');
            }
            if (Schema::hasColumn(self::TABLE, 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
    }
};
