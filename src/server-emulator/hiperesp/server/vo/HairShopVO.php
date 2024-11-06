<?php declare(strict_types=1);
namespace hiperesp\server\vo;

use hiperesp\server\attributes\Inject;
use hiperesp\server\models\HairModel;

class HairShopVO extends ValueObject {

    #[Inject] private HairModel $hairModel;

    public readonly string $name;
    public readonly string $swf;

    /** @return array<HairVO> */
    public function getHairList(string $gender): array {
        return $this->hairModel->getByShop($this, $gender);
    }

}