<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobSpell extends Model
{
    use HasFactory;

    protected $fillable = [
        'mob_id',
        'phase',
        'unique_name',
        'name',
        'name_ru',
        'target',
        'saytext_key',
        'saytext',
        'saytext_ru',
        'sort_order',
        'conditions',
        'details',
    ];

    protected $casts = [
        'conditions' => 'array',
        'details' => 'array',
    ];

    public function mob()
    {
        return $this->belongsTo(Mob::class);
    }

    public function getDisplayNameAttribute(): string
    {
        if (app()->getLocale() === 'ru' && filled($this->name_ru)) {
            return $this->name_ru;
        }

        return $this->name;
    }

    public function getDisplaySaytextAttribute(): ?string
    {
        if (app()->getLocale() === 'ru' && filled($this->saytext_ru)) {
            return $this->saytext_ru;
        }

        return $this->saytext;
    }
}
