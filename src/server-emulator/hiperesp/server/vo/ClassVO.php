<?php
namespace hiperesp\server\vo;

class ClassVO extends ValueObject {

    public readonly int $id;

    public readonly string $name;
    public readonly string $swf;

    public readonly string $armorId;
    public readonly string $weaponId;

    public function __construct(array $data) {

        $this->id = $data['id'];

        $this->name = $data['name'];
        $this->swf = $data['swf'];

        $this->armorId = $data['armorId'];
        $this->weaponId = $data['weaponId'];

    }

}
