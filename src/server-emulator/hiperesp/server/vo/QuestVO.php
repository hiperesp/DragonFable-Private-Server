<?php declare(strict_types=1);
namespace hiperesp\server\vo;

use hiperesp\server\attributes\Inject;
use hiperesp\server\models\ItemModel;
use hiperesp\server\models\MonsterModel;

class QuestVO extends ValueObject {
    public readonly int $id;

    #[Inject] private MonsterModel $monsterModel;
    #[Inject] private ItemModel $itemModel;
    #[Inject] private SettingsVO $settings;

    public readonly string $name;
    public readonly string $description;
    public readonly string $complete;

    public readonly string $swf;
    public readonly string $swfX;

    public readonly int $maxSilver;
    public readonly int $maxGold;
    public readonly int $maxGems;
    public readonly int $maxExp;

    public readonly int $minTime;
    public readonly int $counter;

    public readonly string $extra;

    public readonly int $dailyIndex;
    public readonly int $dailyReward;

    public readonly int $monsterMinLevel;
    public readonly int $monsterMaxLevel;

    public readonly string $monsterType;
    public readonly string $monsterGroupSwf;

    #[\Override]
    protected function patch(array $quest): array {
        $quest['maxSilver'] = $quest['maxSilver'] * $this->settings->silverMultiplier;
        $quest['maxGold']   = $quest['maxGold']   * $this->settings->goldMultiplier;
        $quest['maxGems']   = $quest['maxGems']   * $this->settings->gemsMultiplier;
        $quest['maxExp']    = $quest['maxExp']    * $this->settings->experienceMultiplier;

        return $quest;
    }

    public function isDailyQuest(): bool {
        return $this->dailyIndex > 0;
    }

    /** @return array<ItemVO> */
    public function getRewards(): array {
        return $this->itemModel->getByQuest($this);
    }

    /** @return array<MonsterVO> */
    public function getMonsters(): array {
        return $this->monsterModel->getByQuest($this);
    }

}
