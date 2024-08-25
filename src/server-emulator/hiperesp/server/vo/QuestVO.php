<?php
namespace hiperesp\server\vo;

class QuestVO extends ValueObject {

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

}
