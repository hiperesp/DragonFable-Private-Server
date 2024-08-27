<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\SettingsModel;

class SettingsController extends Controller {

    private SettingsModel $settingsModel;

    #[Request(
        endpoint: '/DFversion.asp',
        inputType: Input::NONE,
        outputType: Output::FORM
    )]
    public function version(): array {
        $settings = $this->settingsModel->getSettings();

        return [
            "gamemovie"     => $settings->gameVersion,
            "signUpMessage" => $settings->signUpMessage,
            "server"        => $settings->serverLocation,
            "gamefilesPath" => $settings->gamefilesPath,
            "end"           => "here",
        ];
    }

}