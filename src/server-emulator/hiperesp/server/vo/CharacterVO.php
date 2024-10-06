<?php
namespace hiperesp\server\vo;

use hiperesp\server\models\UserModel;

class CharacterVO extends ValueObject {

    private UserModel $userModel;
    private SettingsVO $settings;

    public readonly int $userId;

    public readonly string $createdAt;
    public readonly string $updatedAt;

    public readonly string $name;

    public readonly int $level;
    public readonly int $experience;
    public readonly int $experienceToLevel;

    public readonly int $hitPoints;
    public readonly int $manaPoints;

    public readonly int $silver;
    public readonly int $gold;
    public readonly int $gems;
    public readonly int $coins;

    public readonly bool $dragonAmulet;
    public readonly bool $pvpStatus;

    public readonly string $gender;
    public readonly string $pronoun;

    public readonly int $hairId;
    public readonly string $colorHair;
    public readonly string $colorSkin;
    public readonly string $colorBase;
    public readonly string $colorTrim;

    public readonly int $questId;

    public readonly int $strength;
    public readonly int $dexterity;
    public readonly int $intelligence;
    public readonly int $luck;
    public readonly int $charisma;
    public readonly int $endurance;
    public readonly int $wisdom;

    public readonly string $lastDailyQuestDone;

    public readonly string $armor;
    public readonly string $skills;
    public readonly string $quests;

    public readonly int $raceId;
    public readonly int $classId;
    public readonly int $baseClassId;

    public function getStatPoints(): int {
        return ($this->level - 1) * 5;
    }

    public function isBirthday(UserVO $user, string $today): bool {
        if($user->id != $this->userId) {
            throw new \Exception('Character does not belong to the user');
        }

        return $user->isBirthday($today);
    }

    public function getAccessLevel(): int {
        return $this->dragonAmulet ? 1 : 0;
    }

    public function getEquippable(): string {
        $equippable = [
            "Sword", "Mace", "Dagger", "Axe", "Ring", "Necklace", "Staff", "Belt", "Earring", "Bracer",
            "Pet", "Cape", "Wings", "Helmet", "Armor", "Wand", "Scythe", "Trinket", "Artifact"
        ];
        return \implode(",", $equippable);
    }

    public function getDailyQuestAvailable(): bool {
        $today = \date('Y-m-d');
        return $this->lastDailyQuestDone != $today;
    }

    public function getMaxBagSlots(): int {
        if($this->getAccessLevel() > 0) {
            return $this->settings->upgradedMaxBagSlots;
        }
        return $this->settings->nonUpgradedMaxBagSlots;
    }

    public function getMaxBankSlots(): int {
        if($this->getAccessLevel() > 0) {
            return $this->settings->upgradedMaxBankSlots;
        }
        return $this->settings->nonUpgradedMaxBankSlots;
    }

    public function getMaxHouseSlots(): int {
        if($this->getAccessLevel() > 0) {
            return $this->settings->upgradedMaxHouseSlots;
        }
        return $this->settings->nonUpgradedMaxHouseSlots;
    }

    public function getMaxHouseItemSlots(): int {
        if($this->getAccessLevel() > 0) {
            return $this->settings->upgradedMaxHouseItemSlots;
        }
        return $this->settings->nonUpgradedMaxHouseItemSlots;
    }

    public function canBuyItem(ItemVO $item): bool {
        if($this->coins < $item->getPriceCoins()) {
            return false;
        }
        if($this->gold < $item->getPriceGold()) {
            return false;
        }
        return true;
    }

    public function getUser(): UserVO {
        return $this->userModel->getByChar($this);
    }

}
