<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\SettingsModel;

class WebController extends Controller {

    private SettingsModel $settingsModel;

    #[Request(
        endpoint: '/web/default.asp',
        inputType: Input::NONE,
        outputType: Output::REDIRECT
    )]
    public function index(): string {
        return $this->settingsModel->getSettings()->homeUrl;
    }

    #[Request(
        endpoint: '/web/game.asp',
        inputType: Input::NONE,
        outputType: Output::REDIRECT
    )]
    public function play(): string {
        return $this->settingsModel->getSettings()->playUrl;
    }

    #[Request(
        endpoint: '/web/df-signup.asp',
        inputType: Input::NONE,
        outputType: Output::REDIRECT
    )]
    public function signUp(): string {
        return $this->settingsModel->getSettings()->signUpUrl;
    }

    #[Request(
        endpoint: '/web/df-terms.asp',
        inputType: Input::NONE,
        outputType: Output::REDIRECT
    )]
    public function terms(): string {
        return $this->settingsModel->getSettings()->tosUrl;
    }

    #[Request(
        endpoint: '/web/df-lostpassword.aspx',
        inputType: Input::NONE,
        outputType: Output::REDIRECT
    )]
    public function lostPassword(): string {
        return $this->settingsModel->getSettings()->lostPasswordUrl;
    }

}