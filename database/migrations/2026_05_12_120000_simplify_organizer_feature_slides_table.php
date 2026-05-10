<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const LEGACY_COLUMNS = [
        'slide_label',
        'badge_1_icon',
        'badge_1_text',
        'badge_2_icon',
        'badge_2_text',
        'stat_1_number',
        'stat_1_label',
        'stat_2_number',
        'stat_2_label',
        'stat_3_number',
        'stat_3_label',
        'cta_label',
        'cta_url',
    ];

    /**
     * Drop legacy columns for databases that still have the wide schema.
     */
    public function up(): void
    {
        if (! Schema::hasTable('organizer_feature_slides')) {
            return;
        }

        $present = array_values(array_filter(
            self::LEGACY_COLUMNS,
            static fn (string $col): bool => Schema::hasColumn('organizer_feature_slides', $col)
        ));

        if ($present === []) {
            return;
        }

        Schema::table('organizer_feature_slides', static function (Blueprint $table) use ($present): void {
            $table->dropColumn($present);
        });
    }

    /**
     * Best-effort rollback (re-adds nullable columns only).
     */
    public function down(): void
    {
        if (! Schema::hasTable('organizer_feature_slides')) {
            return;
        }

        Schema::table('organizer_feature_slides', function (Blueprint $table): void {
            if (! Schema::hasColumn('organizer_feature_slides', 'slide_label')) {
                $table->string('slide_label')->nullable()->after('is_active');
            }
            if (! Schema::hasColumn('organizer_feature_slides', 'hero_image')) {
                return;
            }
            if (! Schema::hasColumn('organizer_feature_slides', 'badge_1_icon')) {
                $table->string('badge_1_icon', 64)->nullable()->after('hero_image');
            }
            if (! Schema::hasColumn('organizer_feature_slides', 'badge_1_text')) {
                $table->string('badge_1_text')->nullable();
            }
            if (! Schema::hasColumn('organizer_feature_slides', 'badge_2_icon')) {
                $table->string('badge_2_icon', 64)->nullable();
            }
            if (! Schema::hasColumn('organizer_feature_slides', 'badge_2_text')) {
                $table->string('badge_2_text')->nullable();
            }
            if (! Schema::hasColumn('organizer_feature_slides', 'stat_1_number')) {
                $table->string('stat_1_number', 64)->nullable()->after('subtitle');
            }
            if (! Schema::hasColumn('organizer_feature_slides', 'stat_1_label')) {
                $table->string('stat_1_label')->nullable();
            }
            if (! Schema::hasColumn('organizer_feature_slides', 'stat_2_number')) {
                $table->string('stat_2_number', 64)->nullable();
            }
            if (! Schema::hasColumn('organizer_feature_slides', 'stat_2_label')) {
                $table->string('stat_2_label')->nullable();
            }
            if (! Schema::hasColumn('organizer_feature_slides', 'stat_3_number')) {
                $table->string('stat_3_number', 64)->nullable();
            }
            if (! Schema::hasColumn('organizer_feature_slides', 'stat_3_label')) {
                $table->string('stat_3_label')->nullable();
            }
            if (! Schema::hasColumn('organizer_feature_slides', 'cta_label')) {
                $table->string('cta_label')->nullable();
            }
            if (! Schema::hasColumn('organizer_feature_slides', 'cta_url')) {
                $table->string('cta_url')->nullable();
            }
        });
    }
};
