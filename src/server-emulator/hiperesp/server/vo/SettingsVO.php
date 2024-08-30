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

    public readonly bool $levelUpMultipleTimes;

    public readonly int $onlineTimeout;

    public function __construct() {
        $this->gameSwf        = "game15_9_00-patched.swf?v2";
        $this->serverVersion  = "Build 15.9.00 alpha"; // match with the game version, only for display
        //                                         ^ last visible char (aprox. 19 chars)
        $this->serverLocation = "server-emulator/server.php/";
        // $this->serverLocation = "http://localhost:40000/server-emulator/server.php/";
        $this->gamefilesPath  = "cdn/gamefiles/";
        // $this->gamefilesPath  = "http://localhost:40000/cdn/gamefiles/";
        // $this->gamefilesPath  = "https://df.hiper.esp.br/gamefiles/";

        $this->homeUrl          = "../../../index.html";
        $this->playUrl          = "../../../play.html";
        $this->signUpUrl        = "../../../signup.html";
        $this->lostPasswordUrl  = "../../../lostpassword.html";
        $this->tosUrl           = "../../../tos.html";

        $this->signUpMessage  = "Welcome to the world of DragonFable!\n\nPlease sign up to play!";
        $this->news = "It's been a thousand years... and once more, the Toglights in the sky have aligned. Which can mean only one, terrible, terrifying thing...!\n\nIt's Togsday!\n\nCheck out the DNs for more info!";

        $this->enableAdvertising = false; // if true, the game will show ads
        $this->nonUpgradedChars = 3; // number of characters allowed for non-upgraded players
        $this->upgradedChars = 6; // number of characters allowed for upgraded players
        $this->dailyQuestCoinsReward = 200; // coins reward for daily quests (default: 3)

        $this->levelUpMultipleTimes = false; // if true, player can level up multiple times according to the experience gained

        $this->onlineTimeout = 10; // minutes. It affects only the online status of the player
    }

}