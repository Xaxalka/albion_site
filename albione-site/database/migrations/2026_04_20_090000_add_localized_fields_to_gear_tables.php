<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weapon_lines', function (Blueprint $table) {
            $table->string('name_ru')->nullable()->after('name');
            $table->text('description_ru')->nullable()->after('description');
        });

        Schema::table('weapons', function (Blueprint $table) {
            $table->string('name_ru')->nullable()->after('name');
            $table->text('description_ru')->nullable()->after('description');
        });

        Schema::table('armor_items', function (Blueprint $table) {
            $table->string('name_ru')->nullable()->after('name');
            $table->text('description_ru')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('armor_items', function (Blueprint $table) {
            $table->dropColumn(['name_ru', 'description_ru']);
        });

        Schema::table('weapons', function (Blueprint $table) {
            $table->dropColumn(['name_ru', 'description_ru']);
        });

        Schema::table('weapon_lines', function (Blueprint $table) {
            $table->dropColumn(['name_ru', 'description_ru']);
        });
    }
};
