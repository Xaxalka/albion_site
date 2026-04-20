<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'description',
    ];

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function weapons()
    {
        return $this->hasMany(Weapon::class);
    }
}
