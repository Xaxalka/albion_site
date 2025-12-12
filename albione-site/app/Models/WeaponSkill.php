<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeaponSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'weapon_id',
        'name',
        'description',
        'author_notes',
    ];

    public function weapon()
    {
        return $this->belongsTo(Weapon::class);
    }

    public function media()
    {
        return $this->morphMany(SkillMedia::class, 'skillable');
    }
}
