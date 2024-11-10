--TEST--
3 - Create char to delete
--FILE--

$charController = new \hiperesp\server\controllers\CharacterController;

$response = $charController->new([
    "intUserID" => $context["userId"],
    "strUsername" => $context["username"],
    "strPassword" => $context["password"],
    "strToken" => $context["userToken"],
    "strCharacterName" => "testchar",
    "strGender" => "M",
    "strPronoun" => "M",
    "intHairID" => "3",
    "intColorHair" => "7027237",
    "intColorSkin" => "15388042",
    "intColorBase" => "12766664",
    "intColorTrim" => "7570056",
    "intClassID" => "2",
    "intRaceID" => "1",
    "strClass" => "Warrior",
]);

print_r($response);

--EXPECT--
Array
(
    [code] => 0
    [reason] => Character created Successfully!
    [message] => none
    [action] => none
)
