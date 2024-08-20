<?php
namespace hiperesp\server\vo;

class ItemVO extends ValueObject {

    public readonly int $id;
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
    public readonly string $category;
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

}