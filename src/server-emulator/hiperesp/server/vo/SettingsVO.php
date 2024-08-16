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

    public readonly string $news;

    public function __construct() {
        $this->gameVersion    = "game15_8_05-patched.swf";
        $this->serverLocation = "server-emulator/server.php/"; // http://localhost:40000/server-emulator/server.php/
        $this->gamefilesPath  = "cdn/gamefiles/";       // http://localhost:40000/cdn/gamefiles/

        $this->homeUrl   = "../../";
        $this->playUrl   = "../../index.html";
        $this->signUpUrl = "../../signup.html";
        $this->lostPasswordUrl = "../../lostpassword.html";

        $this->news = "It's been a thousand years... and once more, the Toglights in the sky have aligned. Which can mean only one, terrible, terrifying thing...!\n\nIt's Togsday!\n\nCheck out the DNs for more info!";
    }

}