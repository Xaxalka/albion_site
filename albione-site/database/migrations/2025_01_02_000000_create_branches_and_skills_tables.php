<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('weapons', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->after('id')->constrained('branches')->nullOnDelete();
            $table->index(['branch_id']);
        });

        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->enum('slot', ['Q', 'W', 'E']);
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('sort')->default(0);
            $table->boolean('is_placeholder')->default(false);
            $table->timestamps();

            $table->index(['branch_id', 'slot', 'sort']);
        });

        if (Schema::hasTable('weapon_lines')) {
            $lines = DB::table('weapon_lines')->get();
            $branchMap = [];

            foreach ($lines as $line) {
                $branchId = DB::table('branches')->insertGetId([
                    'key' => $line->slug,
                    'name' => $line->name,
                    'description' => $line->description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $branchMap[$line->id] = $branchId;
            }

            foreach ($branchMap as $lineId => $branchId) {
                DB::table('weapons')
                    ->where('weapon_line_id', $lineId)
                    ->update(['branch_id' => $branchId]);
            }

            if (Schema::hasTable('line_skills')) {
                $lineSkills = DB::table('line_skills')
                    ->whereIn('slot', ['Q', 'W'])
                    ->get();

                foreach ($lineSkills as $lineSkill) {
                    $branchId = $branchMap[$lineSkill->weapon_line_id] ?? null;
                    if (! $branchId) {
                        continue;
                    }

                    DB::table('skills')->insert([
                        'branch_id' => $branchId,
                        'slot' => $lineSkill->slot,
                        'name' => $lineSkill->name,
                        'description' => $lineSkill->description,
                        'sort' => $lineSkill->id,
                        'is_placeholder' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            if (Schema::hasTable('weapon_skills')) {
                $weaponSkills = DB::table('weapon_skills')
                    ->join('weapons', 'weapon_skills.weapon_id', '=', 'weapons.id')
                    ->select('weapon_skills.*', 'weapons.branch_id')
                    ->get();

                foreach ($weaponSkills as $weaponSkill) {
                    if (! $weaponSkill->branch_id) {
                        continue;
                    }

                    DB::table('skills')->insert([
                        'branch_id' => $weaponSkill->branch_id,
                        'slot' => 'E',
                        'name' => $weaponSkill->name,
                        'description' => $weaponSkill->description,
                        'sort' => $weaponSkill->weapon_id,
                        'is_placeholder' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');

        Schema::table('weapons', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropIndex(['branch_id']);
            $table->dropColumn('branch_id');
        });

        Schema::dropIfExists('branches');
    }
};
