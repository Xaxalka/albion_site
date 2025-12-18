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
        Schema::create('weapon_lines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('line_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weapon_line_id')->constrained('weapon_lines')->cascadeOnDelete();
            $table->enum('slot', ['Q', 'W', 'Passive']);
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('author_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('weapons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weapon_line_id')->constrained('weapon_lines')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedTinyInteger('enchantment')->default(0);
            $table->enum('type', ['melee', 'ranged', 'magic'])->default('melee');
            $table->text('description')->nullable();
            $table->text('author_notes')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('weapon_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weapon_id')->constrained('weapons')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('author_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('skill_media', function (Blueprint $table) {
            $table->id();
            $table->morphs('skillable'); // LineSkill or WeaponSkill
            $table->string('path');
            $table->string('disk')->default('private');
            $table->string('original_name')->nullable();
            $table->boolean('is_private')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_media');
        Schema::dropIfExists('weapon_skills');
        Schema::dropIfExists('weapons');
        Schema::dropIfExists('line_skills');
        Schema::dropIfExists('weapon_lines');
    }
};
