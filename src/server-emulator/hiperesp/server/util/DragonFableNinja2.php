<?php declare(strict_types=1);
namespace hiperesp\server\util;

class DragonFableNinja2 {

    private readonly string $key;

    public function __construct() {
        $this->key = \getenv("DF_NINJA2_KEY");
    }

    public function decrypt(string $theText): string {
        $decrypted = "";

        $textLength = \strlen($theText);
        $keyLength = \strlen($this->key);

        for($i=0; $i<$textLength; $i+=4) {
            $charP1 = \base_convert(\substr($theText, $i, 2), 30, 10);
            $charP2 = \base_convert(\substr($theText, $i + 2, 2), 30, 10);
            $charP3 = \ord($this->key[$i / 4 % $keyLength]);
            $decrypted .= \chr($charP1 - $charP2 - $charP3);
        }

        if(!\mb_check_encoding($decrypted, "UTF-8")) {
            throw new \Exception("Invalid decrypted text. Verify the ninja2 key.");
        }

        return $decrypted;
    }
    public function encrypt(string $theText): string {
        $encrypted = "";
        
        $textLength = \strlen($theText);
        $keyLength = \strlen($this->key);

        for($i=0; $i<$textLength; $i++) {
            $random = \floor(\mt_rand() / \mt_getrandmax() * 66) + 33;
            $char = \ord($this->key[$i % $keyLength]);
            $encrypted .= \base_convert((string)(\ord($theText[$i]) + $random + $char), 10, 30).\base_convert((string)$random, 10, 30);
        }
        return $encrypted;
    }
}