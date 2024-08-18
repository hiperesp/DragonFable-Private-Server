<?php
namespace hiperesp\server\vo;

class SettingsVO extends ValueObject {

    public readonly string $gameVersion;
    public readonly string $serverLocation;
    public readonly string $gamefilesPath;

    public readonly string $homeUrl;
    public readonly string $playUrl;
    public readonly string $signUpUrl;
    public readonly string $lostPasswordUrl;
    public readonly string $tosUrl;

    public readonly string $signUpMessage;
    public readonly string $news;

    public readonly bool $levelUpMultipleTimes;

    public readonly string $serverVersion;
    public readonly int $onlineTimeout;

    public function __construct() {
        $this->gameVersion    = "game15_8_05-patched.swf";
        $this->serverLocation = "server-emulator/server.php/"; // http://localhost:40000/server-emulator/server.php/
        $this->gamefilesPath  = "https://df.hiper.esp.br/gamefiles/";       // http://localhost:40000/cdn/gamefiles/

        $this->homeUrl          = "../../index.hmtl";
        $this->playUrl          = "../../play.html";
        $this->signUpUrl        = "../../signup.html";
        $this->lostPasswordUrl  = "../../lostpassword.html";
        $this->tosUrl           = "../../tos.html";

        $this->signUpMessage  = "Welcome to the world of DragonFable!\n\nPlease sign up to play!";
        $this->news = "It's been a thousand years... and once more, the Toglights in the sky have aligned. Which can mean only one, terrible, terrifying thing...!\n\nIt's Togsday!\n\nCheck out the DNs for more info!";

        $this->levelUpMultipleTimes = false; // if true, player can level up multiple times according to the experience gained

        $this->serverVersion = "0.0.1 alpha";
        $this->onlineTimeout = 10; // minutes. It affects only the online status of the player
    }

}