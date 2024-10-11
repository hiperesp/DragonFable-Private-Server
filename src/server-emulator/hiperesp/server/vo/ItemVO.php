<?php declare(strict_types=1);
namespace hiperesp\server\vo;

use hiperesp\server\enums\Currency;
use hiperesp\server\exceptions\DFException;

class ItemVO extends ValueObject {

    const CATEGORY_WEAPON = 1;
    const CATEGORY_ARMOR = 2;
    const CATEGORY_PET = 3;
    const CATEGORY_ITEM = 4;

    public readonly string $name;
    public readonly string $description;
    public readonly string $designInfo;
    public readonly int $visible;
    public readonly int $destroyable;
    public readonly int $sellable;
    public readonly int $dragonAmulet;
    public readonly int $currency;
    public readonly int $cost;
    public readonly int $maxStackSize;
    public readonly int $bonus;
    public readonly int $rarity;
    public readonly int $level;
    public readonly string $type;
    public readonly string $element;
    public readonly int $categoryId;
    public readonly string $equipSpot;
    public readonly string $itemType;
    public readonly string $swf;
    public readonly string $icon;
    public readonly int $strength;
    public readonly int $dexterity;
    public readonly int $intelligence;
    public readonly int $luck;
    public readonly int $charisma;
    public readonly int $endurance;
    public readonly int $wisdom;
    public readonly int $damageMin;
    public readonly int $damageMax;
    public readonly int $defenseMelee;
    public readonly int $defensePierce;
    public readonly int $defenseMagic;
    public readonly int $critical;
    public readonly int $parry;
    public readonly int $dodge;
    public readonly int $block;
    public readonly string $resists;

    public function getCurrency(): Currency {
        return match($this->currency) {
            2 => Currency::CURRENCY_GOLD,
            1 => Currency::CURRENCY_DRAGON_COINS,
            default => throw new DFException(DFException::CURRENCY_NOT_FOUND)
        };
    }

    public function getPriceGold(): int {
        return $this->getCurrency() === Currency::CURRENCY_GOLD ? $this->cost : 0;
    }

    public function getPriceCoins(): int {
        return $this->getCurrency() === Currency::CURRENCY_DRAGON_COINS ? $this->cost : 0;
    }

    public function isWeapon(): bool {
        return $this->categoryId === self::CATEGORY_WEAPON;
    }

    public function isArmor(): bool {
        return $this->categoryId === self::CATEGORY_ARMOR;
    }

    public function isPet(): bool {
        return $this->categoryId === self::CATEGORY_PET;
    }

    public function isItem(): bool {
        return $this->categoryId === self::CATEGORY_ITEM;
    }

}