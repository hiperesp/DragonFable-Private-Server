<?php declare(strict_types=1);
namespace hiperesp\server\vo;

use hiperesp\server\attributes\Inject;
use hiperesp\server\models\ItemModel;

class MergeVO extends ValueObject {

    #[Inject] private ItemModel $itemModel;

    public readonly int $itemId1;
    public readonly int $amount1;
    public readonly int $itemId2;
    public readonly int $amount2;
    public readonly int $itemId;
    public readonly int $string;
    public readonly int $index;
    public readonly int $value;
    public readonly int $level;

    #[\Override]
    protected function patch(array $data): array {
        if($data['itemId1'] == -1) {
            $data['itemId1'] = 0;
        }
        if($data['itemId2'] == -1) {
            $data['itemId2'] = 0;
        }
        return $data;
    }

    public function getItem1(): ?ItemVO {
        if(!$this->itemId1) {
            return null;
        }
        return $this->itemModel->getById($this->itemId1);
    }

    public function getItem2(): ?ItemVO {
        if(!$this->itemId2) {
            return null;
        }
        return $this->itemModel->getById($this->itemId2);
    }

    public function getItem(): ItemVO {
        return $this->itemModel->getById($this->itemId);
    }

}