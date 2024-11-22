<?php declare(strict_types=1);
namespace hiperesp\server\services;

use hiperesp\server\attributes\Inject;
use hiperesp\server\models\UserModel;

class TestService extends Service {

    #[Inject] private UserModel $userModel;

    public function deleteTestUser(string $username, string $password): true {
        $user = $this->userModel->login($username, $password);
        $this->userModel->delete($user);
        return true;
    }

}