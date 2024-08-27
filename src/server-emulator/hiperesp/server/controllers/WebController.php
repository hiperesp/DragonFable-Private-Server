<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\SettingsModel;

class WebController extends Controller {

    private SettingsModel $settingsModel;
    private CharacterModel $characterModel;

    #[Request(
        endpoint: '/home',
        inputType: Input::NONE,
        outputType: Output::REDIRECT
    )]
    public function index(): string {
        return $this->settingsModel->getSettings()->homeUrl;
    }

    #[Request(
        endpoint: '/game.asp',
        inputType: Input::NONE,
        outputType: Output::REDIRECT
    )]
    public function play(): string {
        return $this->settingsModel->getSettings()->playUrl;
    }

    #[Request(
        endpoint: '/df-signup.asp',
        inputType: Input::NONE,
        outputType: Output::REDIRECT
    )]
    public function signUp(): string {
        return $this->settingsModel->getSettings()->signUpUrl;
    }

    #[Request(
        endpoint: '/df-terms.asp',
        inputType: Input::NONE,
        outputType: Output::REDIRECT
    )]
    public function terms(): string {
        return $this->settingsModel->getSettings()->tosUrl;
    }

    #[Request(
        endpoint: '/df-lostpassword.aspx',
        inputType: Input::NONE,
        outputType: Output::REDIRECT
    )]
    public function lostPassword(): string {
        return $this->settingsModel->getSettings()->lostPasswordUrl;
    }

    #[Request(
        endpoint: '/web-stats.json',
        inputType: Input::NONE,
        outputType: Output::RAW
    )]
    public function webStats(): string {

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
        inputType: Input::NONE,
        outputType: Output::HTML
    )]
    public function default(): string {
        \http_response_code(404);
        return "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this server.</p><hr>{$_SERVER["SERVER_SIGNATURE"]}</body></html>";
    }


}