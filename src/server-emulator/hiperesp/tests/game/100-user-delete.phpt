--TEST--
100 - Delete test user
--FILE--
$userData = [
    "username" => $context["username"],
    "password" => $context["password"],
];


var_dump($response);

--EXPECT--
string(10) "&success=1"
