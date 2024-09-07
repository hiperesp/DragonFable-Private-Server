<?php
namespace hiperesp\server\vo;

class SettingsVO extends ValueObject {

    public readonly string $gameSwf;
    public readonly string $serverVersion;
    public readonly string $serverLocation;
    public readonly string $gamefilesPath;

    public readonly string $homeUrl;
    public readonly string $playUrl;
    public readonly string $signUpUrl;
    public readonly string $lostPasswordUrl;
    public readonly string $tosUrl;

    public readonly string $signUpMessage;
    public readonly string $news;

    public readonly bool $enableAdvertising;
    public readonly int $nonUpgradedChars;
    public readonly int $upgradedChars;
    public readonly int $dailyQuestCoinsReward;

    public readonly float $experienceMultiplier;
    public readonly float $gemsMultiplier;
    public readonly float $goldMultiplier;
    public readonly float $silverMultiplier;

    public readonly bool $levelUpMultipleTimes;

    public readonly int $onlineTimeout;

    public readonly bool $detailed404ClientError;

}