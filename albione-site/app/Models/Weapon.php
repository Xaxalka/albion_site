<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weapon extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'weapon_line_id',
        'name',
        'name_ru',
        'slug',
        'description',
        'description_ru',
        'author_notes',
        'image',
        'icon',
        'enchantment',
        'type',
    ];

    protected $casts = [
        'enchantment' => 'integer',
    ];

    public function weaponLine()
    {
        return $this->belongsTo(WeaponLine::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function weaponSkill()
    {
        return $this->hasOne(WeaponSkill::class);
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
