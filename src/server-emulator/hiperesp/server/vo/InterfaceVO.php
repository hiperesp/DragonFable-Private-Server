<?php declare(strict_types=1);
namespace hiperesp\server\vo;

class InterfaceVO extends ValueObject {

    public readonly string $name;
    public readonly string $swf;
    public readonly bool $loadUnder;

}
