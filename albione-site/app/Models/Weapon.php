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
        'description',
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

    public function weaponSkill()
    {
        return $this->hasOne(WeaponSkill::class);
    }
}
