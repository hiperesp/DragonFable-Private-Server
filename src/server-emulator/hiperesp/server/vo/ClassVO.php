<?php declare(strict_types=1);
namespace hiperesp\server\vo;

class ClassVO extends ValueObject {
    public readonly int $id;

    public readonly string $name;
    public readonly string $element;
    public readonly string $swf;

    public readonly int $armorId;
    public readonly int $weaponId;
    public readonly int $savable;

    public readonly int $equippable;

}
