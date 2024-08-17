<?php
namespace hiperesp\server\vo;

class QuestVO extends ValueObject {

    public readonly int $id;

    public readonly string $name;

    public readonly string $swf;
    public readonly string $swfX;

    public readonly string $extra;

    public function __construct(array $data) {

        $this->id = $data['id'];

        $this->name = $data['name'];

        $this->swf = $data['swf'];
        $this->swfX = $data['swfX'];

        $this->extra = $data['extra'];

    }

}
