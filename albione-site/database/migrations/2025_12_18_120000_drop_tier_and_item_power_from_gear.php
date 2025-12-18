<?php

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
        if (Schema::hasTable('weapons')) {
            Schema::table('weapons', function (Blueprint $table) {
                if (Schema::hasColumn('weapons', 'tier')) {
                    $table->dropColumn('tier');
                }

                if (Schema::hasColumn('weapons', 'item_power')) {
                    $table->dropColumn('item_power');
                }
            });
        }

        if (Schema::hasTable('armor_items')) {
            Schema::table('armor_items', function (Blueprint $table) {
                if (Schema::hasColumn('armor_items', 'tier')) {
                    $table->dropColumn('tier');
                }

                if (Schema::hasColumn('armor_items', 'item_power')) {
                    $table->dropColumn('item_power');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('weapons')) {
            Schema::table('weapons', function (Blueprint $table) {
                if (! Schema::hasColumn('weapons', 'tier')) {
                    $table->string('tier')->nullable()->after('slug');
                }

                if (! Schema::hasColumn('weapons', 'item_power')) {
                    $table->unsignedSmallInteger('item_power')->default(700)->after('enchantment');
                }
            });
        }

        if (Schema::hasTable('armor_items')) {
            Schema::table('armor_items', function (Blueprint $table) {
                if (! Schema::hasColumn('armor_items', 'tier')) {
                    $table->string('tier')->nullable()->after('slug');
                }

                if (! Schema::hasColumn('armor_items', 'item_power')) {
                    $table->unsignedSmallInteger('item_power')->default(700)->after('enchantment');
                }
            });
        }
    }
};
