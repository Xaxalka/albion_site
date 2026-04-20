<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mob extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_name',
        'slug',
        'name',
        'name_ru',
        'description',
        'description_ru',
        'tier',
        'faction',
        'category',
        'danger_state',
        'npc_hostility',
        'attack_type',
        'ability_power',
        'fame',
        'attack_range',
        'attack_damage',
        'hitpoints_max',
        'hitpoints_regeneration',
        'energy_max',
        'energy_regeneration',
        'move_speed',
        'attack_move_speed',
        'attack_speed',
        'melee_attack_damage_time',
        'physical_armor',
        'magic_resistance',
        'crowd_control_resistance',
        'aggro_radius',
        'pursuit_radius',
        'alert_radius',
        'collision_radius',
        'attack_collision_radius',
        'avatar',
        'prefab',
        'source',
        'raw_data',
    ];

    protected $casts = [
        'raw_data' => 'array',
    ];

    public function spells()
    {
        return $this->hasMany(MobSpell::class)->orderBy('sort_order');
    }

    public function lootEntries()
    {
        return $this->hasMany(MobLootEntry::class)->orderBy('sort_order');
    }

    public function getDisplayNameAttribute(): string
    {
        if (app()->getLocale() === 'ru' && filled($this->name_ru)) {
            return $this->name_ru;
        }

        return $this->name;
    }

    public function getDisplayDescriptionAttribute(): ?string
    {
        if (app()->getLocale() === 'ru' && filled($this->description_ru)) {
            return $this->description_ru;
        }

        return $this->description;
    }
}
