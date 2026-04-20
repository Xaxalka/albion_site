<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobLootEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'mob_id',
        'entry_type',
        'reference_name',
        'reference_label',
        'reference_label_ru',
        'chance',
        'amount',
        'tier',
        'enchantment_level',
        'sort_order',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function mob()
    {
        return $this->belongsTo(Mob::class);
    }

    public function getDisplayReferenceLabelAttribute(): string
    {
        if (app()->getLocale() === 'ru' && filled($this->reference_label_ru)) {
            return $this->reference_label_ru;
        }

        return $this->reference_label ?: $this->reference_name;
    }
}
