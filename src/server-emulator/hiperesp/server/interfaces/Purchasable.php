<?php declare(strict_types=1);
namespace hiperesp\server\interfaces;

interface Purchasable {

    public function getPriceCoins(): int;
    public function getPriceGold(): int;

}
