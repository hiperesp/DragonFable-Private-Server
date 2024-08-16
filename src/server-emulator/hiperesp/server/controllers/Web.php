<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\SettingsModel;
use hiperesp\server\vo\SettingsVO;

class Web extends Controller {

    private SettingsVO $settings;

    public function __construct() {
        parent::__construct();

        $settingsModel = new SettingsModel($this->storage);
        $this->settings = $settingsModel->getSettings();

    }

    #[Request(
        endpoint: '/',
        inputType: Input::RAW,
        outputType: Output::REDIRECT
    )]
    public function index(string $input): string {
        return $this->settings->homeUrl;
    }

    #[Request(
        endpoint: '/game/web.asp',
        inputType: Input::RAW,
        outputType: Output::REDIRECT
    )]
    public function play(string $input): string {
        return $this->settings->playUrl;
    }

    #[Request(
        endpoint: '/df-signup.asp',
        inputType: Input::RAW,
        outputType: Output::REDIRECT
    )]
    public function signUp(string $input): string {
        return $this->settings->signUpUrl;
    }

    #[Request(
        endpoint: '/df-lostpassword.asp',
        inputType: Input::RAW,
        outputType: Output::REDIRECT
    )]
    public function lostPassword(string $input): string {
        return $this->settings->lostPasswordUrl;
    }

}