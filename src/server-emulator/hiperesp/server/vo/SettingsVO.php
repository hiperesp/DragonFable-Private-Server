<?php declare(strict_types=1);
namespace hiperesp\server\vo;

class SettingsVO extends ValueObject {
    public readonly int $id;

    public readonly string $serverName;

    public readonly string $gameSwf;
    public readonly string $serverVersion;
    public readonly string $serverLocation;
    public readonly string $gamefilesPath;

    public readonly string $homeUrl;
    public readonly string $playUrl;
    public readonly string $signUpUrl;
    public readonly string $lostPasswordUrl;
    public readonly string $tosUrl;
    public readonly string $charDetailUrl;

    public readonly string $signUpMessage;
    public readonly string $news;

    public readonly bool $enableAdvertising;
    public readonly int  $dailyQuestCoinsReward;

    public readonly bool $dragonAmuletForAll;

    public readonly bool $revalidateClientValues;
    public readonly bool $banInvalidClientValues;
    public readonly bool $canDeleteUpgradedChar;

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

    public readonly int $onlineThreshold;

    public readonly bool $detailed404ClientError;

    public readonly bool   $sendEmails;
    public readonly string $emailApiUrl;
    public readonly string $emailApiToken;
    public readonly string $emailAddress;

}