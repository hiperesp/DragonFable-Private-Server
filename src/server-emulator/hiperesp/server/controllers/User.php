<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\exceptions\DFException;
use hiperesp\server\models\CharacterModel;
use hiperesp\server\models\ClassModel;
use hiperesp\server\models\RaceModel;
use hiperesp\server\models\SettingsModel;

class User extends Controller {

    #[Request(
        endpoint: '/cf-userlogin.asp',
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

    #[Request(
        endpoint: '/cf-usersignup.asp',
        inputType: Input::FORM,
        outputType: Output::FORM
    )]
    public function signup(array $input): array {
        $username = (string)$input['strUserName'];
        $password = (string)$input['strPassword'];
        $email = (string)$input['strEmail'];
        $dob = (string)$input['strDOB'];

        $userModel = new \hiperesp\server\models\UserModel($this->storage);
        try {
            $user = $userModel->signup([
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'birthdate' => $dob
            ]);

            return [
                'status' => 'Success',
            ];
        } catch(DFException $e) {
            return [
                'status' => 'Failure',
                'strErr' => "Error Code {$e->dfCode}",
                'strReason' => $e->dfReason,
                'strButtonName' => 'Back',
                'strButtonAction' => $e->dfAction,
                'strMsg' => $e->dfMessage
            ];
        }
    }

}