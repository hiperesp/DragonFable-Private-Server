<?php declare(strict_types=1);
namespace hiperesp\server\vo;

use hiperesp\server\attributes\Inject;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\ItemModel;

class CharacterItemVO extends ValueObject {
    public readonly int $id;

    #[Inject] private ItemModel $itemModel;
    #[Inject] private CharacterModel $characterModel;

    public readonly int $charId;

    public readonly string $createdAt;
    public readonly string $updatedAt;

    public readonly int $itemId;

    public readonly bool $equipped;
    public readonly int $count;

    public function getHoursOwned(): int {
        $todaySeconds = \strtotime(\date('c'));
        $ownedAtSeconds = \strtotime($this->createdAt);

        $secondsOwned = $todaySeconds - $ownedAtSeconds;
        $hoursOwned = (int)\floor($secondsOwned / 3600);

        return $hoursOwned;
    }

    public function getItem(): ItemVO {
        return $this->itemModel->getByCharItem($this);
    }

    public function getChar(): CharacterVO {
        return $this->characterModel->getByCharItem($this);
    }

}
