<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\vo\SettingsVO;

class SettingsService extends Service {

    private SettingsVO $settings;

    public function version(): array {
        return [
            "gamemovie"     => $this->settings->gameSwf,
            "signUpMessage" => $this->settings->signUpMessage,
            "server"        => $this->settings->serverLocation,
            "gamefilesPath" => $this->settings->gamefilesPath,
            "gameVersion"   => $this->settings->serverVersion,
            "end"           => "here",
        ];
    }
}