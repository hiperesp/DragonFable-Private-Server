--TEST--
100 - Delete test user
--FILE--
$userData = [
    "username" => $context["username"],
    "password" => $context["password"],
];

$testService = new \hiperesp\server\services\TestService;
$response = $testService->deleteTestUser($userData["username"], $userData["password"]);

var_dump($response);

--EXPECT--
bool(true)
