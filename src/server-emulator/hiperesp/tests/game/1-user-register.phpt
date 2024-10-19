--TEST--
1 - Register to game
--FILE--
$context["username"]  = "test-".\date("YmdHis")."-".\str_pad(\rand(0, 99999), 5, "0", \STR_PAD_LEFT);
$context["email"]     = "test-{$context["username"]}@hiper.esp.br";
$context["password"]  = "test-".\str_pad(\rand(0, 99999), 5, "0", \STR_PAD_LEFT);
$context["birthDate"] = "2024-01-01"

$userController = new \hiperesp\server\controllers\UserController;

$response = $userController->signup([
    'strUserName' => $context["username"],
    'strEmail'    => $context["email"],
    'strPassword' => $context["password"],
    'strDOB'      => $context["birthDate"]
]);

print_r($response);

--EXPECT--
Array
(
    [status] => Success
)
