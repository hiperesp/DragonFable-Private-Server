--TEST--
2 - Login to game
--FILE--

$userController = new \hiperesp\server\controllers\UserController;

$response = $userController->login(new \SimpleXMLElement(<<<XML
<flash><strUsername>{$context["username"]}</strUsername><strPassword>{$context["password"]}</strPassword></flash>
XML));

$response = \json_decode(\json_encode($response), true);

$context["userId"] = $response["user"]["@attributes"]["UserID"];
$context["userToken"] = $response["user"]["@attributes"]["strToken"];

$printArray = function(callable $printArray, array $array, int $indentLevel = 0): void {
    $indent = \str_repeat("    ", $indentLevel);
    foreach($array as $key => $value) {
        $type = \gettype($value);
        echo "{$indent}{$key}: {$type}\n";
        if(\is_array($value)) {
            $printArray($printArray, $value, $indentLevel+1);
        }
    }
};

$printArray($printArray, $response);

--EXPECT--
user: array
    @attributes: array
        UserID: string
        intCharsAllowed: string
        intAccessLevel: string
        intUpgrade: string
        intActivationFlag: string
        bitOptin: string
        strToken: string
        strNews: string
        bitAdFlag: string
        dateToday: string
