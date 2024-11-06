<?php declare(strict_types=1);
namespace hiperesp\server\vo;

use hiperesp\server\attributes\Inject;
use hiperesp\server\models\ItemModel;

class ItemShopVO extends ValueObject {

    #[Inject] private ItemModel $itemModel;

    public readonly string $name;
    public readonly int $count;

    /** @return array<ItemVO> */
    public function getItems(): array {
        return $this->itemModel->getByShop($this);
    }

}