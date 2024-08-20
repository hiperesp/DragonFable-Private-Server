<?php
namespace hiperesp\server\exceptions;

class DFException extends \Exception {

    const SUCCESS = "0";
    const INVALID_REFERENCE = "500.73";
    const CLASS_NOT_FOUND = self::INVALID_REFERENCE;
    const RACE_NOT_FOUND = self::INVALID_REFERENCE;
    const ITEM_NOT_FOUND = self::INVALID_REFERENCE;
    const QUEST_NOT_FOUND = self::INVALID_REFERENCE;
    const HAIR_NOT_FOUND = self::INVALID_REFERENCE;
    const MONSTER_NOT_FOUND = self::INVALID_REFERENCE;
    const SHOP_NOT_FOUND = self::INVALID_REFERENCE;

    const USERNAME_ALREADY_EXISTS = "000.40";
    const EMAIL_ALREADY_EXISTS = "000.50";

    const CHARACTER_NOT_FOUND = "500.71";
    const USER_NOT_FOUND = "526.14";
    const BAD_REQUEST = "538.07";

    public function __construct(
        public readonly string $dfCode,
        public readonly string $dfReason,
        public readonly string $dfMessage,
        public readonly string $dfAction
    ) {
        parent::__construct("{$dfCode}: {$dfReason} - {$dfMessage}");
    }

    public function asXML(): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<error/>');
        $info = $xml->addChild('info');
        $info->addAttribute('code', $this->dfCode);
        $info->addAttribute('reason', $this->dfReason);
        $info->addAttribute('message', $this->dfMessage);
        $info->addAttribute('action', $this->dfAction);
        return $xml;
    }

    public function asXMLString(): string {
        return $this->asXML()->asXML();
    }

    public function asArray(): array {
        return [
            'code' => $this->dfCode,
            'reason' => $this->dfReason,
            'message' => $this->dfMessage,
            'action' => $this->dfAction,
        ];
    }

    public function asJSON(): string {
        return \json_encode($this->asArray());
    }

    public function asString(): string {
        return $this->getMessage();
    }

    public static function fromCode(string $code): self {
        if(!isset(self::$knownExceptions[$code])) {
            return new self($code, "Unknown Error", "An unknown error occurred", "None");
        }
        $exception = self::$knownExceptions[$code];
        return new self(
            dfCode: $code,
            dfReason: $exception['reason'],
            dfMessage: $exception['message'],
            dfAction: $exception['action']
        );
    }

    private static array $knownExceptions = [
        self::SUCCESS => [
            "reason" => "Success",
            "message" => "The operation was successful",
            "action" => "None",
        ],
        self::INVALID_REFERENCE => [
            "reason" => "Invalid Reference",
            "message" => "Invalid Reference",
            "action" => "Continue",
        ],
        self::USERNAME_ALREADY_EXISTS => [
            "reason" => "Username Already Exists",
            "message" => "The username you are trying to use is already taken",
            "action" => "UserName",
        ],
        self::EMAIL_ALREADY_EXISTS => [
            "reason" => "Email Already Exists",
            "message" => "The email you are trying to use is already taken",
            "action" => "Email",
        ],
        self::USER_NOT_FOUND => [
            "reason" => "User Not Found or Wrong Password",
            "message" => "The username or password you typed was not correct. Please check the exact spelling and try again.",
            "action" => "none",
        ],
        self::CHARACTER_NOT_FOUND => [
            "reason" => "Character doesn't exist!",
            "message" => "Character doesn't exist!",
            "action" => "None",
        ],
        self::BAD_REQUEST => [
            "reason" => "Invalid Input!",
            "message" => "Message",
            "action" => "None",
        ],
    ];

}