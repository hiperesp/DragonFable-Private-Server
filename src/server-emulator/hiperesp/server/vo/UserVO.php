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
    public readonly int $optIn;
    public readonly int $adFlag;

    public readonly ?string $lastLogin;

    public function __construct(array $user) {
        $user["password"] = "";
        parent::__construct($user);
    }

    public function isBirthday(string $today): bool {
        $birthdate = \date('m-d', \strtotime($this->birthdate));
        $today = \date('m-d', \strtotime($today));
        return $birthdate === $today;
    }

}