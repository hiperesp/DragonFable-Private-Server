--TEST--
1 - Register to game
--FILE--
$context["userNameId"]   = \date("YmdHis")."-".\str_pad(\rand(0, 99999), 5, "0", \STR_PAD_LEFT);
$context["userPassword"] = "test".\str_pad(\rand(0, 99999), 5, "0", \STR_PAD_LEFT);

$userController = new \hiperesp\server\controllers\UserController;

$response = $userController->signup([
    'strUserName' => "test-{$context["userNameId"]}",
    'strEmail'    => "test-{$context["userNameId"]}@hiper.esp.br",
    'strPassword' => $context["userPassword"],
    'strDOB'      => "2024-01-01"
]);

print_r($response);

--EXPECT--
Array
(
    [status] => Success
)
