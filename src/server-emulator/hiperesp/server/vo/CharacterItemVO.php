<?php declare(strict_types=1);
namespace hiperesp\server\vo;

use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\ItemModel;

class CharacterItemVO extends ValueObject {

    private ItemModel $itemModel;
    private CharacterModel $characterModel;

    public readonly int $charId;

    public readonly string $createdAt;
    public readonly string $updatedAt;

    public readonly int $itemId;

    public readonly bool $equipped;
    public readonly int $count;

    public int $hoursOwned {
        get {
            $todaySeconds = \strtotime(\date('c'));
            $ownedAtSeconds = \strtotime($this->createdAt);

            $secondsOwned = $todaySeconds - $ownedAtSeconds;
            $hoursOwned = (int)\floor($secondsOwned / 3600);

            return $hoursOwned;
        }
    }

    public function getItem(): ItemVO {
        return $this->itemModel->getByCharItem($this);
    }

    public function getChar(): CharacterVO {
        return $this->characterModel->getByCharItem($this);
    }

}
