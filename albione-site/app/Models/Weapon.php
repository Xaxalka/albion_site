<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weapon extends Model
{
    use HasFactory;

    protected $fillable = [
        'weapon_line_id',
        'name',
        'slug',
        'tier',
        'description',
        'author_notes',
        'image',
    ];

    public function weaponLine()
    {
        return $this->belongsTo(WeaponLine::class);
    }

    public function weaponSkill()
    {
        return $this->hasOne(WeaponSkill::class);
    }
}
