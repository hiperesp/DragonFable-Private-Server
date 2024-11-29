<?php declare(strict_types=1);
namespace hiperesp\server\controllers\game;

use hiperesp\server\controllers\Controller;
use hiperesp\server\attributes\Inject;
use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\projection\UserProjection;
use hiperesp\server\services\UserService;

class UserController extends Controller {

    #[Inject] private UserService $userService;

    #[Request(
        endpoint: '/cf-userlogin.asp',
        inputType: Input::NINJA2,
        outputType: Output::NINJA2XML
    )]
    public function login(\SimpleXMLElement $input): \SimpleXMLElement {
        $user = $this->userService->auth($input);

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

        $user = $this->userService->signup(
            username: $username,
            password: $password,
            email: $email,
            birthdate: $dob
        );

        return UserProjection::instance()->signed($user);
    }

}