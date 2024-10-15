<?php declare(strict_types=1);
namespace hiperesp\server\vo;

class ItemShopVO extends ValueObject {
    public readonly int $id;

    public readonly string $name;
    public readonly int $count;

}