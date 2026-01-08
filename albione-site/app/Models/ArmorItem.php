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
        'slug',
        'enchantment',
        'material',
        'slot',
        'description',
        'author_notes',
        'icon',
        'image',
    ];

    protected $casts = [
        'enchantment' => 'integer',
    ];

    public static function materialLabel(string $material): string
    {
        return match ($material) {
            'cloth' => 'Ткань',
            'leather' => 'Кожа',
            'plate' => 'Латка',
            default => $material,
        };
    }

    public static function slotLabel(string $slot): string
    {
        return match ($slot) {
            'chest' => 'Тело',
            'head' => 'Голова',
            'feet' => 'Ноги',
            default => $slot,
        };
    }

    /**
     * Albion naming for each armor material + slot combination.
     */
    public static function pieceLabel(string $material, string $slot): string
    {
        return match ($material) {
            'cloth' => match ($slot) {
                'chest' => 'Мантии',
                'head' => 'Колпаки',
                'feet' => 'Сандали',
                default => self::slotLabel($slot),
            },
            'leather' => match ($slot) {
                'chest' => 'Куртки',
                'head' => 'Капюшоны',
                'feet' => 'Сапоги',
                default => self::slotLabel($slot),
            },
            'plate' => match ($slot) {
                'chest' => 'Броня',
                'head' => 'Шлемы',
                'feet' => 'Ботинки',
                default => self::slotLabel($slot),
            },
            default => self::slotLabel($slot),
        };
    }

    public function armorSkills()
    {
        return $this->hasMany(ArmorSkill::class);
    }
}
