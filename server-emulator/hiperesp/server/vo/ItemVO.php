<?php
namespace hiperesp\server\vo;

//#[VO(dbTable: 'items')]
class ItemVO extends ValueObject {

    //#[Field(cardinality: 'primary')]
    private int $id;

    private string $name;
    private string $description;

    private bool $visible;
    private bool $destroyable;
    private bool $sellable;
    private bool $dragonAmulet;

    private int $currency;
    private int $cost;

    private int $maxStackSize;
    private int $bonus;
    private int $rarity;
    private int $level;

    private string $type;
    private string $element;
    private string $category;
    private string $equipSpot;
    private string $itemType;

    private string $fileName;
    private string $icon;

    private int $strength;
    private int $dexterity;
    private int $intelligence;
    private int $luck;
    private int $charisma;
    private int $endurance;
    private int $wisdom;

    private int $minDamage;
    private int $maxDamage;

    private int $defMelee;
    private int $defPierce;
    private int $defMagic;
    private int $crit;
    private int $parry;
    private int $dodge;
    private int $block;

    private string $resists;

}