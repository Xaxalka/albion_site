<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArmorItem extends Model
{
    use HasFactory;

    public const MATERIALS = ['cloth', 'leather', 'plate'];

    public const SLOTS = ['chest', 'head', 'feet'];

    protected $fillable = [
        'name',
        'name_ru',
        'slug',
        'enchantment',
        'material',
        'slot',
        'description',
        'description_ru',
        'author_notes',
        'icon',
        'image',
    ];

    protected $casts = [
        'enchantment' => 'integer',
    ];

    public static function materialLabel(string $material): string
    {
        return __('ui.armor.materials.'.$material);
    }

    public static function slotLabel(string $slot): string
    {
        return __('ui.armor.slots.'.$slot);
    }

    /**
     * Albion naming for each armor material + slot combination.
     */
    public static function pieceLabel(string $material, string $slot): string
    {
        $translation = __('ui.armor.pieces.'.$material.'.'.$slot);

        return $translation === 'ui.armor.pieces.'.$material.'.'.$slot
            ? self::slotLabel($slot)
            : $translation;
    }

    public function armorSkills()
    {
        return $this->hasMany(ArmorSkill::class);
    }

    public function getDisplayNameAttribute(): string
    {
        if (app()->getLocale() === 'ru' && filled($this->name_ru)) {
            return $this->name_ru;
        }

        return $this->name;
    }

    public function getDisplayDescriptionAttribute(): ?string
    {
        if (app()->getLocale() === 'ru' && filled($this->description_ru)) {
            return $this->description_ru;
        }

        return $this->description;
    }
}
