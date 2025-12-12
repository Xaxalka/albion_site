<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'disk',
        'original_name',
        'is_private',
    ];

    public function skillable()
    {
        return $this->morphTo();
    }
}
