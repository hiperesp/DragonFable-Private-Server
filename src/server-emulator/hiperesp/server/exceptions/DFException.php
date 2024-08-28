<?php
namespace hiperesp\server\exceptions;

class DFException extends \Exception {

    const INVALID_REFERENCE = "500.73";
    const CLASS_NOT_FOUND = self::INVALID_REFERENCE;
    const RACE_NOT_FOUND = self::INVALID_REFERENCE;
    const ITEM_NOT_FOUND = self::INVALID_REFERENCE;
    const QUEST_NOT_FOUND = self::INVALID_REFERENCE;
    const HAIR_NOT_FOUND = self::INVALID_REFERENCE;
    const MONSTER_NOT_FOUND = self::INVALID_REFERENCE;
    const SHOP_NOT_FOUND = self::INVALID_REFERENCE;
    const INTERFACE_NOT_FOUND = self::INVALID_REFERENCE;

    const USERNAME_ALREADY_EXISTS = "000.40";
    const EMAIL_ALREADY_EXISTS = "000.50";

    const CHARACTER_NOT_FOUND = "500.71";
    const USER_NOT_FOUND = "526.14";
    const BAD_REQUEST = "538.07";

    private string $dfCode;
    private string $dfReason;
    private string $dfMessage;
    private string $dfAction;
    private int $httpStatus;

    public function __construct(string $dfCode) {
        if(!isset(self::$knownExceptions[$dfCode])) {
            $dfCode = self::INVALID_REFERENCE;
        }
        $theException = self::$knownExceptions[$dfCode];

        $this->dfCode       = $dfCode;
        $this->dfReason     = $theException["dfReason"];
        $this->dfMessage    = $theException["dfMessage"];
        $this->dfAction     = $theException["dfAction"];
        $this->httpStatus   = $theException["httpStatus"];

        parent::__construct("{$dfCode}: {$this->dfReason} - {$this->dfMessage}");
    }

    public function asXML(): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<error/>');
        $info = $xml->addChild('info');
        $info->addAttribute('code',     $this->dfCode);
        $info->addAttribute('reason',   $this->dfReason);
        $info->addAttribute('message',  $this->dfMessage);
        $info->addAttribute('action',   $this->dfAction);
        return $xml;
    }

    public function asArray(): array {
        return [
            'code'      => $this->dfCode,
            'reason'    => $this->dfReason,
            'message'   => $this->dfMessage,
            'action'    => $this->dfAction,
            'status' => 'Failure',
            'strErr' => "Error Code {$this->dfCode}",
            'strReason' => $this->dfReason,
            'strButtonName' => 'Back',
            'strButtonAction' => $this->dfAction,
            'strMsg' => $this->dfMessage,
        ];
    }

    public function asJSON(): string {
        return \json_encode($this->asArray());
    }

    public function asString(): string {
        return $this->getMessage();
    }

    public function getHttpStatusCode(): int {
        return $this->httpStatus;
    }

    private static array $knownExceptions = [
        self::INVALID_REFERENCE => [
            "dfReason"  => "Invalid Reference",
            "dfMessage" => "Invalid Reference",
            "dfAction"  => "Continue",
            "httpStatus"=> 200,
        ],
        self::USERNAME_ALREADY_EXISTS => [
            "dfReason"  => "Username Already Exists",
            "dfMessage" => "The username you are trying to use is already taken",
            "dfAction"  => "UserName",
            "httpStatus"=> 200,
        ],
        self::EMAIL_ALREADY_EXISTS => [
            "dfReason"  => "Email Already Exists",
            "dfMessage" => "The email you are trying to use is already taken",
            "dfAction"  => "Email",
            "httpStatus"=> 200,
        ],
        self::USER_NOT_FOUND => [
            "dfReason"  => "User Not Found or Wrong Password",
            "dfMessage" => "The username or password you typed was not correct. Please check the exact spelling and try again.",
            "dfAction"  => "none",
            "httpStatus"=> 200,
        ],
        self::CHARACTER_NOT_FOUND => [
            "dfReason"  => "Character doesn't exist!",
            "dfMessage" => "Character doesn't exist!",
            "dfAction"  => "None",
            "httpStatus"=> 200,
        ],
        self::BAD_REQUEST => [
            "dfReason"  => "Invalid Input!",
            "dfMessage" => "Message",
            "dfAction"  => "None",
            "httpStatus"=> 200,
        ],
    ];

}