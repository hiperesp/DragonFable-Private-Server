<?php
namespace hiperesp\server\vo;

class RaceVO extends ValueObject {

    public readonly int $id;

    public readonly string $name;

    public function __construct(array $data) {

        $this->id = $data['id'];

        $this->name = $data['name'];

    }

}
