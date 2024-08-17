<?php
namespace hiperesp\server\vo;

class ClassVO extends ValueObject {

    public readonly int $id;

    public readonly string $name;
    public readonly string $element;
    public readonly string $swf;

    public readonly int $armorId;
    public readonly int $weaponId;
    public readonly int $savable;

    public function __construct(array $data) {

        $this->id = $data['id'];

        $this->name = $data['name'];
        $this->element = $data['element'];
        $this->swf = $data['swf'];

        $this->armorId = $data['armorId'];
        $this->weaponId = $data['weaponId'];
        $this->savable = $data['savable'];

    }

}
