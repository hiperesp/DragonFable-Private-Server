<?php
namespace hiperesp\server\vo;

use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\ItemModel;

class CharacterItemVO extends ValueObject {

    private ItemModel $itemModel;
    private CharacterModel $characterModel;

    public readonly int $id;

    public readonly int $charId;

    public readonly string $createdAt;
    public readonly string $updatedAt;

    public readonly int $itemId;

    public readonly bool $equipped;
    public readonly bool $count;

    public function getHoursOwned(CharacterVO $char): int {
        if($char->id != $this->charId) {
            throw new \Exception('Item does not belong to the character');
        }

        $charCreated = $char->createdAt;
        $itemPurchase = $this->createdAt;

        $secondsOwned = \strtotime($itemPurchase) - \strtotime($charCreated);
        return \intval($secondsOwned / 3600);
    }

    public function getItem(): ItemVO {
        return $this->itemModel->getByCharItem($this);
    }

    public function getChar(): CharacterVO {
        return $this->characterModel->getByCharItem($this);
    }

}
