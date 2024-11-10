<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\attributes\Inject;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\SettingsVO;

class WebApiService extends Service {

    #[Inject] private CharacterModel $characterModel;
    #[Inject] private SettingsVO $settings;

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

    public function characterData(int $id): CharacterVO {
        return $this->characterModel->getById($id);
    }

}