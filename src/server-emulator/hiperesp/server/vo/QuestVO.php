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

    public function __construct(array $quest) {
        parent::__construct($quest);

        $this->maxSilver = $this->maxSilver * $this->settings->silverMultiplier;
        $this->maxGold   = $this->maxGold   * $this->settings->goldMultiplier;
        $this->maxGems   = $this->maxGems   * $this->settings->gemsMultiplier;
        $this->maxExp    = $this->maxExp    * $this->settings->experienceMultiplier;
    }

    public function isDailyQuest(): bool {
        return $this->dailyIndex > 0;
    }

}
