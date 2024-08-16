<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\ClassModel;
use hiperesp\server\models\RaceModel;
use hiperesp\server\models\SettingsModel;

class User extends Controller {

    #[Request(
        method: '/cf-userlogin.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function login(\SimpleXMLElement $input): \SimpleXMLElement {
        $username = (string)$input->strUsername;
        $password = (string)$input->strPassword;

        $userModel = new \hiperesp\server\models\UserModel($this->storage);
        $user = $userModel->login($username, $password);
        return $user->asLoginResponse(
            new SettingsModel($this->storage),
            new CharacterModel($this->storage),
            new ClassModel($this->storage),
            new RaceModel($this->storage)
        );
    }

}