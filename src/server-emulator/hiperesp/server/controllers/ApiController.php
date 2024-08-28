<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\SettingsModel;

class ApiController extends Controller {

    private SettingsModel $settingsModel;
    private CharacterModel $characterModel;

    #[Request(
        endpoint: '/api/web-stats.json',
        inputType: Input::NONE,
        outputType: Output::JSON
    )]
    public function webStats(): mixed {

        $settings = $this->settingsModel->getSettings();
        $onlineCount = $this->characterModel->getOnlineCount($settings->onlineTimeout);

        return [
            'onlineUsers' => $onlineCount,
            'serverTime' => \date('c'),
            'serverVersion' => $settings->serverVersion,
        ];
    }

}