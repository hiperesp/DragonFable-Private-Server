<?php
namespace hiperesp\server\exceptions;

class DFException extends \Exception {

    public const SUCCESS = "0";
    public const USER_NOT_FOUND = "526.14";


    public function __construct(
        private string $dfCode,
        private string $dfReason,
        private string $dfMessage,
        private string $dfAction
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
        "526.14" => [
            "reason" => "User Not Found or Wrong Password",
            "message" => "The username or password you typed was not correct. Please check the exact spelling and try again.",
            "action" => "None",
        ],
    ];

}