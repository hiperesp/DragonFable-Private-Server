<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\SettingsModel;

class Settings extends Controller {

    private SettingsModel $settingsModel;

    #[Request(
        endpoint: '/DFversion.asp',
        inputType: Input::RAW,
        outputType: Output::FORM
    )]
    public function version(string $input): array {
        $settings = $this->settingsModel->getSettings();

        return [
            "gamemovie"     => $settings->gameVersion,
            "server"        => $settings->serverLocation,
            "gamefilesPath" => $settings->gamefilesPath,
            "end"           => "here",
        ];
    }

}