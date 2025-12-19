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
        Schema::table('armor_items', function (Blueprint $table) {
            $table->index(['material', 'slot'], 'armor_items_material_slot_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('armor_items', function (Blueprint $table) {
            $table->dropIndex('armor_items_material_slot_index');
        });
    }
};
