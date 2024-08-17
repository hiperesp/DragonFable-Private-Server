<?php
namespace hiperesp\server\vo;

class WeaponVO extends ValueObject {

    public readonly int $id;

    public readonly string $name;
    public readonly string $description;
    public readonly string $designInfo;
    public readonly string $resists;
    public readonly int $level;
    public readonly string $icon;
    public readonly string $type;
    public readonly string $itemType;
    public readonly int $critical;
    public readonly int $damageMin;
    public readonly int $damageMax;
    public readonly int $bonus;
    public readonly string $swf;

    public function __construct(array $data) {

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
