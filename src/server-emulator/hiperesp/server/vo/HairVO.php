<?php declare(strict_types=1);
namespace hiperesp\server\vo;

use hiperesp\server\interfaces\Purchasable;

class HairVO extends ValueObject implements Purchasable {
    public readonly int $id;

    public readonly string $name;
    public readonly string $swf;
    public readonly int $frame;
    public readonly int $price;
    public readonly string $gender;
    public readonly int $raceId;
    public readonly bool $earVisible;

    public function getPriceCoins(): int {
        return 0;
    }
    public function getPriceGold(): int {
        return $this->price;
    }

}
