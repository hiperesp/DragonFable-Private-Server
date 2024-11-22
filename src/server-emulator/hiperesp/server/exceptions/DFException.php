<?php declare(strict_types=1);
namespace hiperesp\server\exceptions;

final class DFException extends \Exception {

    const DYNAMIC_ERROR = "000.00";

    const CHARACTER_NOT_FOUND = "500.71";
    const INVALID_SESSION = "500.72";
    const INVALID_REFERENCE = "500.73";
    const CLASS_NOT_FOUND = self::INVALID_REFERENCE;
    const RACE_NOT_FOUND = self::INVALID_REFERENCE;
    const ITEM_NOT_FOUND = self::INVALID_REFERENCE;
    const MERGE_NOT_FOUND = self::INVALID_REFERENCE;
    const QUEST_NOT_FOUND = self::INVALID_REFERENCE;
    const HAIR_NOT_FOUND = self::INVALID_REFERENCE;
    const MONSTER_NOT_FOUND = self::INVALID_REFERENCE;
    const ITEM_SHOP_NOT_FOUND = self::INVALID_REFERENCE;
    const HAIR_SHOP_NOT_FOUND = self::INVALID_REFERENCE;
    const MERGE_SHOP_NOT_FOUND = self::INVALID_REFERENCE;
    const HOUSE_SHOP_NOT_FOUND = self::INVALID_REFERENCE;
    const INTERFACE_NOT_FOUND = self::INVALID_REFERENCE;
    const SETTINGS_NOT_FOUND = self::INVALID_REFERENCE;
    const CATEGORY_NOT_FOUND = self::INVALID_REFERENCE;
    const CURRENCY_NOT_FOUND = self::INVALID_REFERENCE;
    const CANNOT_BUY_ITEM = self::INVALID_REFERENCE;
    const CANNOT_MERGE_ITEM = self::INVALID_REFERENCE;
    const CANNOT_BUY_HAIR = self::INVALID_REFERENCE;
    const CHARACTER_ITEM_NOT_FOUND = self::INVALID_REFERENCE;
    const ITEM_NOT_DESTROYABLE = self::INVALID_REFERENCE;
    const ITEM_NOT_ENOUGH = self::INVALID_REFERENCE;
    const STATS_POINTS_NOT_ENOUGH = self::INVALID_REFERENCE;
    const GOLD_NOT_ENOUGH = self::INVALID_REFERENCE;
    const DRAGONCOINS_NOT_ENOUGH = self::INVALID_REFERENCE;
    const CANNOT_UNTRAIN_STATS = self::INVALID_REFERENCE;
    const CHARACTER_ITEM_MAX_STACK_SIZE = self::INVALID_REFERENCE;

    const USERNAME_ALREADY_EXISTS = "000.40";
    const INVALID_USERNAME = "000.41";
    const EMAIL_ALREADY_EXISTS = "000.50";
    const INVALID_EMAIL = "000.51";
    const USER_BANNED = "000.60";

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
        if(!isset($theException["httpStatus"])) {
            $theException["httpStatus"] = 200; // default, because the client is not handling some status codes rather than 200
        }
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
            "dfAction"  => "none",
        ],
        self::INVALID_SESSION => [
            "dfReason"  => "Account Already in Use",
            "dfMessage" => "Warning: Your account has been logged into from another web browser terminating this game session. Please use the other browser or re-login.",
            "dfAction"  => "none",
        ],
        self::USERNAME_ALREADY_EXISTS => [
            "dfReason"  => "Username Already Exists",
            "dfMessage" => "The username you are trying to use is already taken",
            "dfAction"  => "UserName",
        ],
        self::INVALID_USERNAME => [
            "dfReason"  => "Invalid Username",
            "dfMessage" => "The username you are trying to use is invalid",
            "dfAction"  => "UserName",
        ],
        self::EMAIL_ALREADY_EXISTS => [
            "dfReason"  => "Email Already Exists",
            "dfMessage" => "The email you are trying to use is already taken",
            "dfAction"  => "Email",
        ],
        self::INVALID_EMAIL => [
            "dfReason"  => "Invalid Email",
            "dfMessage" => "The email you are trying to use is invalid",
            "dfAction"  => "Email",
        ],
        self::USER_NOT_FOUND => [
            "dfReason"  => "User Not Found or Wrong Password",
            "dfMessage" => "The username or password you typed was not correct. Please check the exact spelling and try again.",
            "dfAction"  => "none",
        ],
        self::CHARACTER_NOT_FOUND => [
            "dfReason"  => "Character doesn't exist!",
            "dfMessage" => "Character doesn't exist!",
            "dfAction"  => "none",
        ],
        self::BAD_REQUEST => [
            "dfReason"  => "Invalid Input!",
            "dfMessage" => "Message",
            "dfAction"  => "none",
        ],
        self::USER_BANNED => [
            "dfReason"  => "User Banned",
            // create a custom message using \n, <font color> tags, etc
            "dfMessage" => "Your account has been <font color=\"#FF0000\">banned</font>.\n\nIf you believe this is an error, please contact us through the <font color=\"#ff0000\">Help Pages</font>.",
            "dfAction"  => "none",
        ],
        self::DYNAMIC_ERROR => [
            "dfReason"  => "Dynamic Error",
            "dfMessage" => "Dynamic Error",
            "dfAction"  => "none",
        ],
    ];

    public static function dynamicError(string $dfReason, string $dfMessage, string $dfAction): DFException {
        $exception = new DFException(self::DYNAMIC_ERROR);
        $exception->dfReason = $dfReason;
        $exception->dfMessage = $dfMessage;
        $exception->dfAction = $dfAction;
        return $exception;
    }

    public function getDFCode(): string {
        return $this->dfCode;
    }

}