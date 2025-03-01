<?php
namespace hiperesp\server\projection;

use hiperesp\server\attributes\Inject;
use hiperesp\server\vo\SettingsVO;
use hiperesp\server\vo\UserVO;

class UserProjection extends Projection {

    #[Inject] private SettingsVO $settings;

    public function logged(UserVO $user): \SimpleXMLElement {

        $xml = new \SimpleXMLElement('<characters/>');
        $userEl = $xml->addChild('user');
        $userEl->addAttribute('UserID', $user->id);
        $userEl->addAttribute('intCharsAllowed', $user->getCharsAllowed());
        $userEl->addAttribute('intAccessLevel', $user->getAccessLevel());
        $userEl->addAttribute('intUpgrade', $user->getUpgradedFlag());
        $userEl->addAttribute('intActivationFlag', $user->activated ? 5 : 1);
        $userEl->addAttribute('bitOptin', $user->optIn);
        $userEl->addAttribute('strToken', $user->sessionToken);
        $userEl->addAttribute('strNews', $this->settings->news);
        $userEl->addAttribute('bitAdFlag', $this->settings->enableAdvertising ? 1 : 0);
        $userEl->addAttribute('dateToday', \date('c'));

        foreach($user->getChars() as $char) {
            $charEl = $userEl->addChild('characters');
            $charEl->addAttribute('CharID', $char->id);
            $charEl->addAttribute('strCharacterName', $char->name);
            $charEl->addAttribute('intLevel', $char->level);
            $charEl->addAttribute('intAccessLevel', $char->getAccessLevel());
            $charEl->addAttribute('intDragonAmulet', $char->dragonAmulet ? 1 : 0);

            $class = $char->getClass();
            $charEl->addAttribute('orgClassID', $char->baseClassId);
            $charEl->addAttribute('strClassName', $class->name);

            $race = $char->getRace();
            $charEl->addAttribute('strRaceName', $race->name);
        }

        // custom parameters to be used in patched client
        $userEl->addAttribute('customParam_username', $user->username);

        return $xml;
    }

    public function signed(): array {
        return [
            'status' => 'Success',
        ];
    }
}