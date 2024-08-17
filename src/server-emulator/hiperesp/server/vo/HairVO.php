<?php
namespace hiperesp\server\vo;

class HairVO extends ValueObject {

    public readonly int $id;
    public readonly string $name;
    public readonly string $swf;
    public readonly bool $isEarVisible;
    public readonly string $gender;
    public readonly int $price;
    public readonly int $frame;
    public readonly int $raceId;

    public function __construct(array $data) {

        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->swf = $data['swf'];
        $this->isEarVisible = $data['earVisible'] != 0;
        $this->gender = $data['gender'];
        $this->price = $data['price'];
        $this->frame = $data['frame'];
        $this->raceId = $data['raceId'];
    }

}
