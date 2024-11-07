<?php declare(strict_types=1);
namespace hiperesp\server\vo;

use hiperesp\server\attributes\Inject;
use hiperesp\server\enums\ItemCategory;
use hiperesp\server\enums\Currency;
use hiperesp\server\interfaces\Purchasable;
use hiperesp\server\models\ItemCategoryModel;

class ItemVO extends ValueObject implements Purchasable {

    #[Inject] private ItemCategoryModel $itemCategoryModel;

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

    public function getPriceGold(): int {
        return $this->currency === Currency::GOLD ? $this->cost : 0;
    }

    public function getPriceCoins(): int {
        return $this->currency === Currency::DRAGON_COINS ? $this->cost : 0;
    }

    public function isWeapon(): bool {
        return $this->categoryId === ItemCategory::WEAPON;
    }

    public function isArmor(): bool {
        return $this->categoryId === ItemCategory::ARMOR;
    }

    public function isPet(): bool {
        return $this->categoryId === ItemCategory::PET;
    }

    public function isItem(): bool {
        return $this->categoryId === ItemCategory::ITEM;
    }

    public function getCategory(): ItemCategoryVO {
        return $this->itemCategoryModel->getByItem($this);
    }

}