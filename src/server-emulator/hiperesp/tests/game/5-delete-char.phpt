--TEST--
5 - Delete char
--FILE--

$charController = new \hiperesp\server\controllers\CharacterController;

$response = $charController->delete(new \SimpleXMLElement(<<<XML
<flash><strToken>{$context["userToken"]}</strToken><intCharID>{$context["charId"]}</intCharID></flash>
XML));

print_r($response);

--EXPECT--
SimpleXMLElement Object
(
    [charDelete] => SimpleXMLElement Object
        (
            [@attributes] => Array
                (
                    [message] => Character Deleteion Successful!!
                )

        )

)
