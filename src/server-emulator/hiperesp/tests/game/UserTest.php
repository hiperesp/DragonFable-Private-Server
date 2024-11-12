<?php declare(strict_types=1);
namespace hiperesp\newtests\game;

use hiperesp\newtests\Test;
use hiperesp\newtests\TestCase;

#[TestCase("User test")]
class UserTest extends Test {

    #[TestCase("Register to game")]
    public function testRegister(): void {
        $context["username"]  = "test-".\date("YmdHis")."-".\str_pad((string)\rand(0, 99999), 5, "0", \STR_PAD_LEFT);
        $context["email"]     = "{$context["username"]}@dragonfable.hiper.esp.br";
        $context["password"]  = "test-".\str_pad((string)\rand(0, 9999999999), 5, "0", \STR_PAD_LEFT);
        $context["birthDate"] = "2024-01-01";

        $userController = new \hiperesp\server\controllers\UserController;

        $response = $userController->signup([
            'strUserName' => $context["username"],
            'strEmail'    => $context["email"],
            'strPassword' => $context["password"],
            'strDOB'      => $context["birthDate"]
        ]);

        \assert("Success" == $response["status"], "Failed to register user: {$response["message"]}");
    }

    
}