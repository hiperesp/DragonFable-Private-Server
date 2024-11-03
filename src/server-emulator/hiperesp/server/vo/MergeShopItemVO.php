<?php declare(strict_types=1);
namespace hiperesp\server\vo;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\interfaces\Purchasable;
use hiperesp\server\models\ItemModel;

class MergeShopItemVO extends ValueObject implements Purchasable {

    private ItemModel $itemModel;

    public readonly int $mergeShopId;
    public readonly int $itemId1;
    public readonly int $amount1;
    public readonly int $itemId2;
    public readonly int $amount2;
    public readonly int $itemId;
    public readonly int $string;
    public readonly int $index;
    public readonly int $value;
    public readonly int $level;

    public function getPriceCoins(): int {
        return $this->getItem()->getPriceCoins();
    }
    public function getPriceGold(): int {
        return $this->getItem()->getPriceGold();
    }

    public function getItem1(): ItemVO {
        return $this->itemModel->getById($this->itemId1);
    }

    public function getItem2(): ItemVO {
        return $this->itemModel->getById($this->itemId2);
    }

    public function getItem(): ItemVO {
        return $this->itemModel->getById($this->itemId);
    }

}