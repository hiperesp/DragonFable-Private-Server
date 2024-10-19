<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\models\UserModel;

class TestService extends Service {

    private UserModel $userModel;

    public function deleteTestUser(string $username, $password): true {
        $user = $this->userModel->login($username, $password);
        $this->userModel->delete($user);
        return true;
    }

}