--TEST--
100 - Delete test user
--FILE--
$userData = [
    "testUsername"  => $context["userNameId"],
    "password"      => $context["userPassword"],
];

$response = \file_get_contents('http://localhost/server-emulator/server.php/test-clean/delete-test-user', false, \stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'content' => \http_build_query($userData),
    ],
]));

var_dump($response);

--EXPECT--
string(10) "&success=1"
