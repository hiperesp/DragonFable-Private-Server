<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\SettingsModel;

class Web extends Controller {

    private SettingsModel $settingsModel;
    private CharacterModel $characterModel;

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

    #[Request(
        endpoint: '/web-stats.json',
        inputType: Input::RAW,
        outputType: Output::RAW
    )]
    public function webStats(string $input): string {

        $settings = $this->settingsModel->getSettings();
        $onlineCount = $this->characterModel->getOnlineCount($settings->onlineTimeout);

        return \json_encode([
            'onlineUsers' => $onlineCount,
            'serverTime' => \date('c'),
            'serverVersion' => $settings->serverVersion,
        ]);
    }

    #[Request(
        endpoint: 'default',
        inputType: Input::RAW,
        outputType: Output::HTML
    )]
    public function default(string $input): string {
        // return 404
        \http_response_code(404);
        return '<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><h1>404 Not Found</h1><hr><p>The requested URL was not found on this server.</p></body></html>';
    }


}