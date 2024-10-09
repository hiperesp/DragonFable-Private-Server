<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\UserModel;
use hiperesp\server\projection\UserProjection;

class UserController extends Controller {

    private UserModel $userModel;

    #[Request(
        endpoint: '/cf-userlogin.asp',
        inputType: Input::NINJA2,
        outputType: Output::NINJA2XML
    )]
    public function login(\SimpleXMLElement $input): \SimpleXMLElement {
        $username = (string)$input->strUsername;
        $password = (string)$input->strPassword;

        $user = $this->userModel->login($username, $password);

        return UserProjection::instance()->logged($user);
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

        $user = $this->userModel->signup(
            username: $username,
            password: $password,
            email: $email,
            birthdate: $dob
        );

        return UserProjection::instance()->signed($user);
    }

}