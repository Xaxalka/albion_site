<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobs', function (Blueprint $table) {
            $table->id();
            $table->string('unique_name')->unique();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('name_ru')->nullable();
            $table->text('description')->nullable();
            $table->text('description_ru')->nullable();
            $table->unsignedTinyInteger('tier')->nullable()->index();
            $table->string('faction')->nullable()->index();
            $table->string('category')->nullable()->index();
            $table->string('danger_state')->nullable()->index();
            $table->string('npc_hostility')->nullable()->index();
            $table->string('attack_type')->nullable()->index();
            $table->decimal('ability_power', 10, 2)->nullable();
            $table->unsignedInteger('fame')->nullable();
            $table->decimal('attack_range', 10, 2)->nullable();
            $table->decimal('attack_damage', 12, 2)->nullable();
            $table->decimal('hitpoints_max', 12, 2)->nullable();
            $table->decimal('hitpoints_regeneration', 12, 2)->nullable();
            $table->decimal('energy_max', 12, 2)->nullable();
            $table->decimal('energy_regeneration', 12, 2)->nullable();
            $table->decimal('move_speed', 10, 3)->nullable();
            $table->decimal('attack_move_speed', 10, 3)->nullable();
            $table->decimal('attack_speed', 10, 3)->nullable();
            $table->decimal('melee_attack_damage_time', 10, 3)->nullable();
            $table->decimal('physical_armor', 10, 2)->nullable();
            $table->decimal('magic_resistance', 10, 2)->nullable();
            $table->decimal('crowd_control_resistance', 10, 2)->nullable();
            $table->decimal('aggro_radius', 10, 2)->nullable();
            $table->decimal('pursuit_radius', 10, 2)->nullable();
            $table->decimal('alert_radius', 10, 2)->nullable();
            $table->decimal('collision_radius', 10, 2)->nullable();
            $table->decimal('attack_collision_radius', 10, 2)->nullable();
            $table->string('avatar')->nullable();
            $table->string('prefab')->nullable();
            $table->string('source')->default('aodb')->index();
            $table->json('raw_data')->nullable();
            $table->timestamps();
        });

        Schema::create('mob_spells', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mob_id')->constrained('mobs')->cascadeOnDelete();
            $table->string('phase')->default('Spells');
            $table->string('unique_name');
            $table->string('name');
            $table->string('name_ru')->nullable();
            $table->string('target')->nullable();
            $table->string('saytext_key')->nullable();
            $table->text('saytext')->nullable();
            $table->text('saytext_ru')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->json('conditions')->nullable();
            $table->json('details')->nullable();
            $table->timestamps();

            $table->index(['mob_id', 'phase', 'sort_order']);
            $table->index('unique_name');
        });

        Schema::create('mob_loot_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mob_id')->constrained('mobs')->cascadeOnDelete();
            $table->string('entry_type');
            $table->string('reference_name');
            $table->string('reference_label')->nullable();
            $table->string('reference_label_ru')->nullable();
            $table->decimal('chance', 10, 6)->nullable();
            $table->string('amount')->nullable();
            $table->string('tier')->nullable();
            $table->unsignedTinyInteger('enchantment_level')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->json('details')->nullable();
            $table->timestamps();

            $table->index(['mob_id', 'sort_order']);
            $table->index('reference_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mob_loot_entries');
        Schema::dropIfExists('mob_spells');
        Schema::dropIfExists('mobs');
    }
};
