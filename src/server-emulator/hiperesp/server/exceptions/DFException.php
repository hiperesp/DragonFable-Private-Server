<?php
namespace hiperesp\server\exceptions;

class DFException extends \Exception {

    public const SUCCESS = "0";
    public const CLASS_NOT_FOUND = "000.10";
    public const RACE_NOT_FOUND = "000.20";
    public const ARMOR_NOT_FOUND = "000.30";
    public const WEAPON_NOT_FOUND = "000.31";
    public const QUEST_NOT_FOUND = "000.32";
    public const HAIR_NOT_FOUND = "000.33";
    public const USERNAME_ALREADY_EXISTS = "000.40";
    public const EMAIL_ALREADY_EXISTS = "000.50";

    public const USER_NOT_FOUND = "526.14";
    public const CHARACTER_NOT_FOUND = "500.71";

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
        self::CLASS_NOT_FOUND => [
            "reason" => "Class Not Found",
            "message" => "The class you are looking for doesn't exist",
            "action" => "None",
        ],
        self::RACE_NOT_FOUND => [
            "reason" => "Race Not Found",
            "message" => "The race you are looking for doesn't exist",
            "action" => "None",
        ],
        self::ARMOR_NOT_FOUND => [
            "reason" => "Armor Not Found",
            "message" => "The armor you are looking for doesn't exist",
            "action" => "None",
        ],
        self::WEAPON_NOT_FOUND => [
            "reason" => "Weapon Not Found",
            "message" => "The weapon you are looking for doesn't exist",
            "action" => "None",
        ],
        self::QUEST_NOT_FOUND => [
            "reason" => "Quest Not Found",
            "message" => "The quest you are looking for doesn't exist",
            "action" => "None",
        ],
        self::HAIR_NOT_FOUND => [
            "reason" => "Hair Not Found",
            "message" => "The hair you are looking for doesn't exist",
            "action" => "None",
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
    ];

}