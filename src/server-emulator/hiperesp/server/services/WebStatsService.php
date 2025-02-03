<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\attributes\Inject;
use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\vo\SettingsVO;

class WebStatsService extends Service {

    # We are not using #[Inject] here because we want this to work without the correct table structure,
    # to show the user that the server is not ready yet if the setup is not done.

    public function version(): array {
        $settings = $this->getSettings();
        return [
            "gamemovie"     => $this->settings?->gameSwf,
            "signUpMessage" => $this->settings?->signUpMessage,
            "server"        => $this->settings?->serverLocation,
            "gamefilesPath" => $this->settings?->gamefilesPath,
            "gameVersion"   => $this->settings?->serverVersion,
            "online"        => $this->getStatus()['online'] ? "true" : "false",
            "end"           => "here",
        ];
    }

    public function stats(): array {
        global $config;
        $settings = $this->getSettings();

        try {
            $onlineUsers = (new CharacterModel())->getOnlineCount();
        } catch(DFException $e) {
            $onlineUsers = 0;
        }
        return [
            'onlineUsers' => $onlineUsers,
            'status' => $this->getStatus(),
            'serverTime' => \date('c'),
            'serverVersion' => $settings?->serverVersion,
            'gitRev' => $config['GIT_REV'] ?: null,
        ];
    }

    private function getSettings(): SettingsVO|null {
        try {
            return (new SettingsModel)->getSettings();
        } catch(\Exception $e) {
            return null;
        }
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