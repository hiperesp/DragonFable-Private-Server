<?php declare(strict_types=1);
namespace hiperesp\server\vo;

use hiperesp\server\attributes\Inject;
use hiperesp\server\models\HouseModel;

class HouseShopVO extends ValueObject {
    public readonly int $id;

    #[Inject] private HouseModel $houseModel;

    public readonly string $name;

    /** @return array<HouseVO> */
    public function getHouses(): array {
        return $this->houseModel->getByShop($this);
    }

}