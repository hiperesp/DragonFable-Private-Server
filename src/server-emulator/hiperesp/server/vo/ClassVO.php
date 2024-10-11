<?php declare(strict_types=1);
namespace hiperesp\server\vo;

class ClassVO extends ValueObject {

    public readonly string $name;
    public readonly string $element;
    public readonly string $swf;

    public readonly int $armorId;
    public readonly int $weaponId;
    public readonly int $savable;

    public string $equippable { // will be changed later. See #62
        get {
            return \implode(",", [
                "Sword", "Mace", "Dagger", "Axe", "Ring", "Necklace", "Staff", "Belt", "Earring",
                "Bracer", "Pet", "Cape", "Wings", "Helmet", "Armor", "Wand", "Scythe", "Trinket",
                "Artifact"
            ]);
        }
    }

}
