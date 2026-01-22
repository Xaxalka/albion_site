<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'slot',
        'name',
        'description',
        'icon',
        'sort',
        'is_placeholder',
    ];

    protected $casts = [
        'is_placeholder' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
