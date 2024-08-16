<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\SettingsModel;

class Settings extends Controller {

    #[Request(
        endpoint: '/DFversion.asp',
        inputType: Input::RAW,
        outputType: Output::FORM
    )]
    public function version(string $input): array {
        $settingsModel = new SettingsModel($this->storage);
        $settings = $settingsModel->getSettings();

        return [
            "gamemovie"     => $settings->gameVersion,
            "server"        => $settings->serverLocation,
            "gamefilesPath" => $settings->gamefilesPath,
            "end"           => "here",
        ];
    }

}