<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeaponLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ru',
        'slug',
        'description',
        'description_ru',
    ];

    public function weapons()
    {
        return $this->hasMany(Weapon::class);
    }

    public function lineSkills()
    {
        return $this->hasMany(LineSkill::class);
    }

    public function branch()
    {
        return $this->hasOne(Branch::class, 'key', 'slug');
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
