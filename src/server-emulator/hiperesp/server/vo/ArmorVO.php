<?php
namespace hiperesp\server\vo;

class ArmorVO extends ValueObject {

    public readonly int $id;

    public readonly string $name;
    public readonly string $description;
    public readonly string $resists;

    public readonly int $defenseMelee;
    public readonly int $defensePierce;
    public readonly int $defenseMagic;
    public readonly int $parry;
    public readonly int $dodge;
    public readonly int $block;

    public function __construct(array $data) {

        $this->id = $data['id'];

        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->resists = $data['resists'];

        $this->defenseMelee = $data['defenseMelee'];
        $this->defensePierce = $data['defensePierce'];
        $this->defenseMagic = $data['defenseMagic'];
        $this->parry = $data['parry'];
        $this->dodge = $data['dodge'];
        $this->block = $data['block'];

    }

}
