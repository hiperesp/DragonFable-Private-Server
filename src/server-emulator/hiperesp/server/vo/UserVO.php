<?php
namespace hiperesp\server\vo;

use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\ClassModel;
use hiperesp\server\models\RaceModel;
use hiperesp\server\models\SettingsModel;

class UserVO extends ValueObject {

    public readonly int $id;

    public readonly string $createdAt;
    public readonly string $updatedAt;

    public readonly string $username;
    public readonly string $email;
    public readonly string $password;

    public readonly string $birthdate;

    public readonly ?string $sessionToken;

    public readonly int $charsAllowed;
    public readonly int $accessLevel;
    public readonly int $upgrade;
    public readonly int $activationFlag;
    public readonly int $optin;
    public readonly int $adFlag;

    public readonly ?string $lastLogin;

    public function __construct(array $user) {
        $this->id = $user['id'];

        $this->createdAt = $user['createdAt'];
        $this->updatedAt = $user['updatedAt'];

        $this->username = $user['username'];
        $this->email = $user['email'];
        $this->password = "";

        $this->birthdate = $user['birthdate'];

        $this->sessionToken = $user['sessionToken'];

        $this->charsAllowed = $user['charsAllowed'];
        $this->accessLevel = $user['accessLevel'];
        $this->upgrade = $user['upgrade'];
        $this->activationFlag = $user['activationFlag'];
        $this->optin = $user['optIn'];
        $this->adFlag = $user['adFlag'];

        $this->lastLogin = $user['lastLogin'];
    }

    public function asLoginResponse(SettingsModel $settingsModel, CharacterModel $characterModel, ClassModel $classModel, RaceModel $raceModel): \SimpleXMLElement {

        $settings = $settingsModel->getSettings();

        $xml = new \SimpleXMLElement('<characters/>');
        $user = $xml->addChild('user');
        $user->addAttribute('UserID', $this->id);
        $user->addAttribute('intCharsAllowed', $this->charsAllowed);
        $user->addAttribute('intAccessLevel', $this->accessLevel);
        $user->addAttribute('intUpgrade', $this->upgrade);
        $user->addAttribute('intActivationFlag', $this->activationFlag);
        $user->addAttribute('bitOptin', $this->optin);
        $user->addAttribute('strToken', $this->sessionToken);
        $user->addAttribute('strNews', $settings->news);
        $user->addAttribute('bitAdFlag', $this->adFlag);
        $user->addAttribute('dateToday', \date('c'));

        $characters = $characterModel->getByUser($this);

        /** @var CharacterVO $character */
        foreach($characters as $character) {
            $char = $user->addChild('characters');
            $char->addAttribute('CharID', $character->id);
            $char->addAttribute('strCharacterName', $character->name);
            $char->addAttribute('intLevel', $character->level);
            $char->addAttribute('intAccessLevel', $character->accessLevel);
            $char->addAttribute('intDragonAmulet', $character->hasDragonAmulet ? 1 : 0);

            $class = $classModel->getByCharacter($character);
            $char->addAttribute('orgClassID', $char->id);
            $char->addAttribute('strClassName', $class->name);

            $race = $raceModel->getByCharacter($character);
            $char->addAttribute('strRaceName', $race->name);
        }

        return $xml;
    }

    public function isBirthday(string $today): bool {
        $birthdate = \date('m-d', \strtotime($this->birthdate));
        $today = \date('m-d', \strtotime($today));
        return $birthdate === $today;
    }

}