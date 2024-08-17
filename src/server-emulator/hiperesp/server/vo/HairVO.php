<?php
namespace hiperesp\server\vo;

class HairVO extends ValueObject {

    public readonly int $id;
    public readonly string $name;
    public readonly string $swf;
    public readonly bool $isEarVisible;
    public readonly string $gender;

    public function __construct(array $data) {

        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->swf = $data['swf'];
        $this->isEarVisible = $data['earVisible'] != 0;
        $this->gender = $data['gender'];
    }

}
