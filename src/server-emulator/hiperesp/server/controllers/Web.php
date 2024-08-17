<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\SettingsModel;

class Web extends Controller {

    private SettingsModel $settingsModel;

    #[Request(
        endpoint: '/home',
        inputType: Input::RAW,
        outputType: Output::REDIRECT
    )]
    public function index(string $input): string {
        return $this->settingsModel->getSettings()->homeUrl;
    }

    #[Request(
        endpoint: '/game.asp',
        inputType: Input::RAW,
        outputType: Output::REDIRECT
    )]
    public function play(string $input): string {
        return $this->settingsModel->getSettings()->playUrl;
    }

    #[Request(
        endpoint: '/df-signup.asp',
        inputType: Input::RAW,
        outputType: Output::REDIRECT
    )]
    public function signUp(string $input): string {
        return $this->settingsModel->getSettings()->signUpUrl;
    }

    #[Request(
        endpoint: '/df-terms.asp',
        inputType: Input::RAW,
        outputType: Output::REDIRECT
    )]
    public function terms(string $input): string {
        return $this->settingsModel->getSettings()->tosUrl;
    }

    #[Request(
        endpoint: '/df-lostpassword.aspx',
        inputType: Input::RAW,
        outputType: Output::REDIRECT
    )]
    public function lostPassword(string $input): string {
        return $this->settingsModel->getSettings()->lostPasswordUrl;
    }

}