<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'weapon_line_id',
        'slot',
        'name',
        'description',
        'author_notes',
    ];

    public function weaponLine()
    {
        return $this->belongsTo(WeaponLine::class);
    }

    public function media()
    {
        return $this->morphMany(SkillMedia::class, 'skillable');
    }
}
