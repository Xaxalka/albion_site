<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArmorSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'armor_item_id',
        'name',
        'description',
        'author_notes',
    ];

    public function armorItem()
    {
        return $this->belongsTo(ArmorItem::class);
    }

    public function media()
    {
        return $this->morphMany(SkillMedia::class, 'skillable');
    }
}
