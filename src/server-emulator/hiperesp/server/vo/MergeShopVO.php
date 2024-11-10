<?php declare(strict_types=1);
namespace hiperesp\server\vo;

use hiperesp\server\attributes\Inject;
use hiperesp\server\models\MergeModel;

class MergeShopVO extends ValueObject {

    #[Inject] private MergeModel $mergeModel;

    public readonly string $name;

    /** @return array<MergeVO> */
    public function getMerges(): array {
        return $this->mergeModel->getByShop($this);
    }

}