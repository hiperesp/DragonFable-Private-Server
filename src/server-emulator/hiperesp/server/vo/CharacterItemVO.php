<?php
namespace hiperesp\server\vo;

class CharacterItemVO extends ValueObject {

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

}
