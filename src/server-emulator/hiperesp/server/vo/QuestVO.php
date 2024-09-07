<?php
namespace hiperesp\server\vo;

class QuestVO extends ValueObject {

    private SettingsVO $settings;

    public readonly int $id;

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

    public function isDailyQuest(): bool {
        return $this->dailyIndex > 0;
    }

    public function setMaxSilver(int $maxSilver): void {
        $this->maxSilver = $maxSilver * $this->settings->silverMultiplier;
    }

    public function setMaxGold(int $maxGold): void {
        $this->maxGold = $maxGold * $this->settings->goldMultiplier;
    }

    public function setMaxGems(int $maxGems): void {
        $this->maxGems = $maxGems * $this->settings->gemsMultiplier;
    }

    public function setMaxExp(int $maxExp): void {
        $this->maxExp = $maxExp * $this->settings->experienceMultiplier;
    }

}
