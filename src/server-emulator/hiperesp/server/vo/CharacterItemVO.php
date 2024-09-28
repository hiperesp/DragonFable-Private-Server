<?php
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
    public readonly bool $count;

    public function getHoursOwned(string $today): int {
        $todaySeconds = \strtotime($today);
        $ownedAtSeconds = \strtotime($this->createdAt);

        $secondsOwned = $todaySeconds - $ownedAtSeconds;
        $hoursOwned = \floor($secondsOwned / 3600);

        return $hoursOwned;
    }

    public function getItem(): ItemVO {
        return $this->itemModel->getByCharItem($this);
    }

    public function getChar(): CharacterVO {
        return $this->characterModel->getByCharItem($this);
    }

}
