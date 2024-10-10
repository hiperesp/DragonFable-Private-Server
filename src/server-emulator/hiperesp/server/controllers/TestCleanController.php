<?php declare(strict_types=1);
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\models\UserModel;

class TestCleanController extends Controller {

    private UserModel $userModel;

    #[Request(
        endpoint: '/test-clean/delete-test-user',
        inputType: Input::FORM,
        outputType: Output::FORM
    )]
    public function deleteTestUser(array $input): array {
        $user = $this->userModel->login("test-{$input["testUsername"]}", $input["password"]);
        $this->userModel->delete($user);
        return [ "success" => true, ];
    }

}