<?php
namespace hiperesp\server\controllers;

use hiperesp\server\util\DragonFableCrypto2;

abstract class Controller {

    protected DragonFableCrypto2 $crypto2;

    public function __construct() {

        $this->cors();

        $this->crypto2 = new DragonFableCrypto2;

    }

    public final function entry(): void;

    protected function getInputXml(): \SimpleXMLElement {
        $xml = \file_get_contents("php://input");
        if(\preg_match('/^<ninja2>(.+)<\/ninja2>$/', $xml, $matches)) {
            $xml = $this->crypto2->decrypt($matches[1]);
        }
        return \simplexml_load_string($xml);
    }

    protected function getInputForm(): \SimpleXMLElement {
        return $_POST;
    }

    private function cors() { // https://stackoverflow.com/questions/8719276/cross-origin-request-headerscors-with-php-headers
        // Allow from any origin
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // you want to allow, and if so:
            \header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            \header('Access-Control-Allow-Credentials: true');
            \header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                // may also be using PUT, PATCH, HEAD etc
                \header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                \header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            exit(0);
        }
    }
}