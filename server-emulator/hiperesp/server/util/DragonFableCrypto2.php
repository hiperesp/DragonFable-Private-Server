<?php
namespace hiperesp\server\util;

class DragonFableCrypto2 {

    public function __construct(private string $key = "ZorbakOwnsYou") {}

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
        return $decrypted;
    }
    public function encrypt(string $theText): string {
        $encrypted = "";
        
        $textLength = \strlen($theText);
        $keyLength = \strlen($this->key);

        for($i=0; $i<$textLength; $i++) {
            $random = \floor(\mt_rand() / \mt_getrandmax() * 66) + 33;
            $char = \ord($this->key[$i % $keyLength]);
            $encrypted .= \base_convert(\ord($theText[$i]) + $random + $char, 10, 30).\base_convert($random, 10, 30);
        }
        return $encrypted;
    }
}