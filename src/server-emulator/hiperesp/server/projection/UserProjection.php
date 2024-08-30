<?php
namespace hiperesp\server\projection;

use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\ClassModel;
use hiperesp\server\models\RaceModel;
use hiperesp\server\models\SettingsModel;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\UserVO;

class UserProjection extends Projection {

    private SettingsModel $settingsModel;
    private CharacterModel $characterModel;
    private ClassModel $classModel;
    private RaceModel $raceModel;

    public function logged(UserVO $user): \SimpleXMLElement {

        $settings = $this->settingsModel->getSettings();

        $xml = new \SimpleXMLElement('<characters/>');
        $userEl = $xml->addChild('user');
        $userEl->addAttribute('UserID', $user->id);
        $userEl->addAttribute('intCharsAllowed', $user->getCharsAllowed($settings));
        $userEl->addAttribute('intAccessLevel', $user->getAccessLevel());
        $userEl->addAttribute('intUpgrade', $user->upgraded ? 1 : 0);
        $userEl->addAttribute('intActivationFlag', $user->activated ? 5 : 1);
        $userEl->addAttribute('bitOptin', $user->optIn);
        $userEl->addAttribute('strToken', $user->sessionToken);
        $userEl->addAttribute('strNews', $settings->news);
        $userEl->addAttribute('bitAdFlag', $settings->enableAdvertising ? 1 : 0);
        $userEl->addAttribute('dateToday', \date('c'));

        $chars = $this->characterModel->getByUser($user);

        /** @var CharacterVO $char */
        foreach($chars as $char) {
            $charEl = $userEl->addChild('characters');
            $charEl->addAttribute('CharID', $char->id);
            $charEl->addAttribute('strCharacterName', $char->name);
            $charEl->addAttribute('intLevel', $char->level);
            $charEl->addAttribute('intAccessLevel', $char->getAccessLevel());
            $charEl->addAttribute('intDragonAmulet', $char->dragonAmulet ? 1 : 0);

            $class = $this->classModel->getByChar($char);
            $charEl->addAttribute('orgClassID', $char->id);
            $charEl->addAttribute('strClassName', $class->name);

            $race = $this->raceModel->getByChar($char);
            $charEl->addAttribute('strRaceName', $race->name);
        }

        return $xml;
    }

    public function signed(): array {
        return [
            'status' => 'Success',
        ];
    }
}