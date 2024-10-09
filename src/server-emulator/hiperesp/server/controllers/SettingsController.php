<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\vo\SettingsVO;

class SettingsController extends Controller {

    private SettingsVO $settings;

    #[Request(
        endpoint: '/DFversion.asp',
        inputType: Input::NONE,
        outputType: Output::FORM
    )]
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