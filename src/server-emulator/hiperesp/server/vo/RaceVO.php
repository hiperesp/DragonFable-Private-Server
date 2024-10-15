<?php declare(strict_types=1);
namespace hiperesp\server\vo;

class RaceVO extends ValueObject {
    public readonly int $id;

    public readonly string $name;
    public readonly string $resists;

}
