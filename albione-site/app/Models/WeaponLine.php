<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeaponLine extends Model
{
    use HasFactory;

    public const ALLOWED_NAMES = [
        'Warrior Weapons',
        'Hunter Weapons',
        'Mage Weapons',
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function weapons()
    {
        return $this->hasMany(Weapon::class);
    }

    public function lineSkills()
    {
        return $this->hasMany(LineSkill::class);
    }
}
