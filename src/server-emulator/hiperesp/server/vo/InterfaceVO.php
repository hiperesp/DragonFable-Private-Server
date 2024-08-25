<?php
namespace hiperesp\server\vo;

class InterfaceVO extends ValueObject {

    public readonly int $id;
    public readonly string $name;
    public readonly string $swf;
    public readonly bool $loadUnder;

}
