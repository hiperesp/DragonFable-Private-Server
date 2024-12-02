<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\attributes\Inject;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\SettingsVO;

class WebApiService extends Service {

    #[Inject] private CharacterModel $characterModel;
    #[Inject] private SettingsVO $settings;

    public function version(): array {
        return [
            "gamemovie"     => $this->settings->gameSwf,
            "signUpMessage" => $this->settings->signUpMessage,
            "server"        => $this->settings->serverLocation,
            "gamefilesPath" => $this->settings->gamefilesPath,
            "gameVersion"   => $this->settings->serverVersion,
            "online"        => $this->getStatus()['online'] ? "true" : "false",
            "end"           => "here",
        ];
    }

    public function characterData(int $id): CharacterVO {
        return $this->characterModel->getById($id);
    }

    public function stats(): array {
        global $config;
        return [
            'onlineUsers' => $this->characterModel->getOnlineCount(),
            'status' => $this->getStatus(),
            'serverTime' => \date('c'),
            'serverVersion' => $this->settings->serverVersion,
            'gitRev' => $config['GIT_REV'] ?: null,
        ];
    }

    private function getStatus(): array {
        global $base;

        if(!\file_exists("{$base}/setup.lock")) {
            return [
                'online' => false,
                'text' => 'Upgrade Needed',
                'color' => 'hsl(0deg, 60%, 50%)',
            ];
        }

        $setupStatusTxt = \file_get_contents("{$base}/setup.lock");
        if($setupStatusTxt === 'UPGRADING') {
            return [
                'online' => false,
                'text' => 'Upgrading',
                'color' => 'hsl(60deg, 60%, 50%)',
            ];
        }
        if($setupStatusTxt === 'DONE') {
            return [
                'online' => true,
                'text' => 'Online',
                'color' => 'hsl(110deg, 60%, 50%)',
            ];
        }
        if(!$setupStatusTxt) {
            $setupStatusTxt = 'Empty';
        }
        return [
            'online' => false,
            'text' => "Unknown ({$setupStatusTxt})",
            'color' => 'hsl(200deg, 60%, 50%)',
        ];
    }

}