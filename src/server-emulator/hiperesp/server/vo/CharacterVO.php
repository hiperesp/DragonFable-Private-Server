<?php declare(strict_types=1);
namespace hiperesp\server\vo;

use hiperesp\server\attributes\Inject;
use hiperesp\server\interfaces\Bannable;
use hiperesp\server\interfaces\Purchasable;
use hiperesp\server\models\CharacterItemModel;
use hiperesp\server\models\ClassModel;
use hiperesp\server\models\DragonModel;
use hiperesp\server\models\HairModel;
use hiperesp\server\models\RaceModel;
use hiperesp\server\models\TownModel;
use hiperesp\server\models\UserModel;

class CharacterVO extends ValueObject implements Bannable {
    public readonly int $id;

    #[Inject] private CharacterItemModel $characterItemModel;
    #[Inject] private ClassModel $classModel;
    #[Inject] private DragonModel $dragonModel;
    #[Inject] private HairModel $hairModel;
    #[Inject] private RaceModel $raceModel;
    #[Inject] private TownModel $townModel;
    #[Inject] private UserModel $userModel;
    #[Inject] private SettingsVO $settings;

    #[\Override]
    protected function patch(array $data): array {
        if($this->settings->dragonAmuletForAll) {
            $data['dragonAmulet'] = true;
        }
        return $data;
    }

    public readonly int $userId;

    public readonly string $createdAt;
    public readonly string $updatedAt;

    public readonly string $name;

    public readonly int $level;
    public readonly int $experience;

    public readonly int $silver;
    public readonly int $gold;
    public readonly int $gems;
    public readonly int $coins;

    public readonly bool $dragonAmulet;
    public readonly bool $hasDragon;
    public readonly int $bagSlots;
    public readonly int $bankSlots;
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

    public readonly string $lastTimeSeen;

    public function getExperienceToLevel(): int {
        $expToLevel = match(true) {
            // level values extracted from https://forums2.battleon.com/f/tm.asp?m=18647631
            $this->level < 10 => \pow(2, $this->level) * 10, // OK
            $this->level < 60 => 90 * \pow($this->level - 10, 2) + 1800 * ($this->level - 10) + 9000,
            // the next equation was taken from wolframalpha.com, prompting by
            // "interpolate polynomial {(0, 346480), (1, 363072), (2, 380184), (3, 397824), (4, 416000), (5, 434720)}"
            $this->level < 80 =>  4 * \pow($this->level - 60, 3) / 3 + 256 * \pow($this->level - 60, 2) + 49004 * ($this->level - 60) / 3 + 346480,
            $this->level < 81 =>   787_360,
            $this->level < 84 =>   815_000 + ($this->level - 81) * 29_000,
            $this->level < 87 =>   873_000 + ($this->level - 83) * 30_000,
            $this->level < 88 => 1_000_000,
            $this->level < 89 => 1_037_000,
            $this->level < 90 => 1_075_000,
            # custom
            $this->level < 96 => 1_075_000 + 41_000 * ($this->level - 90),
            default           => 1_280_000 + 48_000 * ($this->level - 95),
        };
        if($expToLevel <= $this->experience) {
            $expToLevel = $this->experience + 1;
        }
        return $expToLevel;
    }

    public function getHitPoints(): int {
        return ($this->level - 1) * 20 + 100;
    }
    public function getManaPoints(): int {
        return ($this->level - 1) * 5 + 100;
    }

    public function getStatPoints(): int {
        return ($this->level - 1) * 5;
    }

    public function getAccessLevel(): int {
        return $this->dragonAmulet ? 1 : 0;
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

    public function canBuy(Purchasable $item): bool {
        if($this->coins < $item->getPriceCoins()) {
            return false;
        }
        if($this->gold < $item->getPriceGold()) {
            return false;
        }
        return true;
    }

    public function canMerge(MergeVO $merge): bool {

        if($merge->itemId1) {
            if($requiredItem1 = $this->characterItemModel->getByCharAndItemId($this, $merge->itemId1)) {
                if($requiredItem1->count < $merge->amount1) {
                    return false;
                }
            } else {
                return false;
            }
        }

        if($merge->itemId2) {
            if($requiredItem2 = $this->characterItemModel->getByCharAndItemId($this, $merge->itemId2)) {
                if($requiredItem2->count < $merge->amount2) {
                    return false;
                }
            } else {
                return false;
            }
        }

        return true;
    }

    public function isBirthday(): bool {
        return $this->getUser()->isBirthday();
    }

    public function isDailyQuestAvailable(): bool {
        $today = \date('Y-m-d');
        return $this->lastDailyQuestDone != $today;
    }

    public function getUser(): UserVO {
        return $this->userModel->getByChar($this);
    }

    public function getRace(): RaceVO {
        return $this->raceModel->getByChar($this);
    }

    public function getTown(): QuestVO {
        return $this->townModel->getByChar($this);
    }

    public function getClass(): ClassVO {
        return $this->classModel->getByChar($this);
    }

    public function getHair(): HairVO {
        return $this->hairModel->getByChar($this);
    }

    /** @return array<CharacterItemVO> */
    public function getBag(): array {
        return $this->characterItemModel->getByChar($this);
    }

    public function getDragon(): array {
        return $this->dragonModel->getByChar($this);
    }

}
