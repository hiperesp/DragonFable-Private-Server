--TEST--
1 - Register to game
--FILE--
$context["uniqueId"] = $uniqueId = \date("YmdHis")."-".\str_pad(\rand(0, 99999), 5, "0", \STR_PAD_LEFT);

$userData = [
    'strUserName' => "test-{$uniqueId}",
    'strEmail'    => "test-{$uniqueId}@hiper.esp.br",
    'strPassword' => "test123456",
    'strDOB'      => "2024-01-01"
];
$response = \file_get_contents('http://localhost/server-emulator/server.php/cf-usersignup.asp', false, \stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'content' => \http_build_query($userData),
    ],
]));

var_dump($response);

--EXPECT--
string(15) "&status=Success"

--CLEAN--
$userData = [
    "testUsername" => "{$context["uniqueId"]}", // server will append "test-" prefix
    "password" => "test123456",
];

$response = \file_get_contents('http://localhost/server-emulator/server.php/test-clean/delete-test-user', false, \stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'content' => \http_build_query($userData),
    ],
]));
