<?php declare(strict_types=1);
namespace hiperesp\server\exceptions;

class DFException extends \Exception {

    const DYNAMIC_ERROR = "DFPS-DYNAMIC_ERROR";

    const CHARACTER_NOT_FOUND = "500.71-CHARACTER_NOT_FOUND";
    const INVALID_SESSION = "500.72-INVALID_SESSION";
    const INVALID_REFERENCE = "500.73-INVALID_REFERENCE";
    const USER_NOT_FOUND = "526.14-USER_NOT_FOUND";
    const BAD_REQUEST = "538.07-BAD_REQUEST";

    const SETTINGS_NOT_FOUND            = "DFPS-SETTINGS_NOT_FOUND";
    const CLASS_NOT_FOUND               = "DFPS-CLASS_NOT_FOUND";
    const RACE_NOT_FOUND                = "DFPS-RACE_NOT_FOUND";
    const ITEM_NOT_FOUND                = "DFPS-ITEM_NOT_FOUND";
    const MERGE_NOT_FOUND               = "DFPS-MERGE_NOT_FOUND";
    const QUEST_NOT_FOUND               = "DFPS-QUEST_NOT_FOUND";
    const HAIR_NOT_FOUND                = "DFPS-HAIR_NOT_FOUND";
    const MONSTER_NOT_FOUND             = "DFPS-MONSTER_NOT_FOUND";
    const ITEM_SHOP_NOT_FOUND           = "DFPS-ITEM_SHOP_NOT_FOUND";
    const HAIR_SHOP_NOT_FOUND           = "DFPS-HAIR_SHOP_NOT_FOUND";
    const MERGE_SHOP_NOT_FOUND          = "DFPS-MERGE_SHOP_NOT_FOUND";
    const HOUSE_SHOP_NOT_FOUND          = "DFPS-HOUSE_SHOP_NOT_FOUND";
    const INTERFACE_NOT_FOUND           = "DFPS-INTERFACE_NOT_FOUND";
    const CATEGORY_NOT_FOUND            = "DFPS-CATEGORY_NOT_FOUND";
    const CURRENCY_NOT_FOUND            = "DFPS-CURRENCY_NOT_FOUND";
    const CANNOT_BUY_ITEM               = "DFPS-CANNOT_BUY_ITEM";
    const CANNOT_MERGE_ITEM             = "DFPS-CANNOT_MERGE_ITEM";
    const CANNOT_BUY_HAIR               = "DFPS-CANNOT_BUY_HAIR";
    const CHARACTER_ITEM_NOT_FOUND      = "DFPS-CHARACTER_ITEM_NOT_FOUND";
    const ITEM_NOT_DESTROYABLE          = "DFPS-ITEM_NOT_DESTROYABLE";
    const ITEM_NOT_ENOUGH               = "DFPS-ITEM_NOT_ENOUGH";
    const STATS_POINTS_NOT_ENOUGH       = "DFPS-STATS_POINTS_NOT_ENOUGH";
    const GOLD_NOT_ENOUGH               = "DFPS-GOLD_NOT_ENOUGH";
    const DRAGONCOINS_NOT_ENOUGH        = "DFPS-DRAGONCOINS_NOT_ENOUGH";
    const CANNOT_UNTRAIN_STATS          = "DFPS-CANNOT_UNTRAIN_STATS";
    const CHARACTER_ITEM_MAX_STACK_SIZE = "DFPS-CHARACTER_ITEM_MAX_STACK_SIZE";

    const USERNAME_ALREADY_EXISTS       = "DFPS-USERNAME_ALREADY_EXISTS";
    const INVALID_USERNAME              = "DFPS-INVALID_USERNAME";
    const EMAIL_ALREADY_EXISTS          = "DFPS-EMAIL_ALREADY_EXISTS";
    const INVALID_EMAIL                 = "DFPS-INVALID_EMAIL";
    const USER_BANNED                   = "DFPS-USER_BANNED";

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

    public function asEventSource(): callable {
        return function() {
            return [
                'event' => 'error',
                'data' => $this->asJSON()
            ];
        };
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

    public function getDFMessage(): string {
        return $this->dfMessage;
    }

}