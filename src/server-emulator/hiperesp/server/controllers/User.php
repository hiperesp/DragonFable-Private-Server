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
use hiperesp\server\models\UserModel;

class User extends Controller {

    private SettingsModel $settingsModel;
    private UserModel $userModel;
    private CharacterModel $characterModel;
    private ClassModel $classModel;
    private RaceModel $raceModel;

    #[Request(
        endpoint: '/cf-userlogin.asp',
        inputType: Input::NINJA2,
        outputType: Output::NINJA2XML
    )]
    public function login(\SimpleXMLElement $input): \SimpleXMLElement {
        $username = (string)$input->strUsername;
        $password = (string)$input->strPassword;

        $user = $this->userModel->login($username, $password);
        return $user->asLoginResponse(
            $this->settingsModel,
            $this->characterModel,
            $this->classModel,
            $this->raceModel
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

        try {
            $user = $this->userModel->signup([
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