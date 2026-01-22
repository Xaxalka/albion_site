<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeaponLine extends Model
{
    use HasFactory;

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

    public function branch()
    {
        return $this->hasOne(Branch::class, 'key', 'slug');
    }
}
