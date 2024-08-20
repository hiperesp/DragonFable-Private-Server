<?php
namespace hiperesp\server\vo;

class ItemVO extends ValueObject {

    public readonly int $id;

    public readonly string $name;
    public readonly string $designInfo;
    public readonly string $description;
    public readonly string $resists;

    public readonly bool $visible;
    public readonly bool $destroyable;
    public readonly bool $sellable;
    public readonly bool $dragonAmulet;

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
    public readonly int $parry;
    public readonly int $dodge;
    public readonly int $block;
    public readonly int $critical;

    public function __construct(array $data) {
        $this->id = $data['id'];

        $this->name = $data['name'];
        $this->designInfo = $data['designInfo'];
        $this->description = $data['description'];
        $this->resists = $data['resists'];

        $this->defenseMelee = $data['defenseMelee'];
        $this->defensePierce = $data['defensePierce'];
        $this->defenseMagic = $data['defenseMagic'];
        $this->parry = $data['parry'];
        $this->dodge = $data['dodge'];
        $this->block = $data['block'];

        
        $this->id = $data['id'];

        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->designInfo = $data['designInfo'];
        $this->resists = $data['resists'];
        $this->level = $data['level'];
        $this->icon = $data['icon'];
        $this->type = $data['type'];
        $this->itemType = $data['itemType'];
        $this->critical = $data['critical'];
        $this->damageMin = $data['damageMin'];
        $this->damageMax = $data['damageMax'];
        $this->bonus = $data['bonus'];
        $this->swf = $data['swf'];

    }
}