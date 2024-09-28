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
    public readonly int $dailyQuestCoinsReward;

    public readonly bool $revalidateClientValues;
    public readonly bool $banInvalidClientValues;

    public readonly int $nonUpgradedChars;
    public readonly int    $upgradedChars;
    public readonly int $nonUpgradedMaxBagSlots;
    public readonly int    $upgradedMaxBagSlots;
    public readonly int $nonUpgradedMaxBankSlots;
    public readonly int    $upgradedMaxBankSlots;
    public readonly int $nonUpgradedMaxHouseSlots;
    public readonly int    $upgradedMaxHouseSlots;
    public readonly int $nonUpgradedMaxHouseItemSlots;
    public readonly int    $upgradedMaxHouseItemSlots;

    public readonly float $experienceMultiplier;
    public readonly float $gemsMultiplier;
    public readonly float $goldMultiplier;
    public readonly float $silverMultiplier;

    public readonly int $onlineTimeout;

    public readonly bool $detailed404ClientError;

}