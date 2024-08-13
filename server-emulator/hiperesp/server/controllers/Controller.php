<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;
use hiperesp\server\util\DragonFableCrypto2;

abstract class Controller {

    protected DragonFableCrypto2 $crypto2;

    public function __construct() {

        $this->cors();

        $this->crypto2 = new DragonFableCrypto2;

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

    public final function entry($method): void {
        $foundMethod = false;
        $inputType = null;
        $outputType = null;
        $rMethod = null;

        $default = [
            "inputType" => null,
            "outputType" => null,
            "rMethod" => null
        ];

        $rClass = new \ReflectionClass($this);
        foreach($rClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $rMethod) {
            foreach($rMethod->getAttributes(Request::class) as $rAttribute) {
                /** @var Request $request */
                $request = $rAttribute->newInstance();

                if($request->getMethod() == 'default') {
                    $default["inputType"] = $request->getInputType();
                    $default["outputType"] = $request->getOutputType();
                    $default["rMethod"] = $rMethod;
                    continue;
                }

                if($request->getMethod() != $method) continue;

                $foundMethod = true;
                $inputType = $request->getInputType();
                $outputType = $request->getOutputType();
                break 2;
            }
        }

        if(!$foundMethod) {
            if(!$default["rMethod"]) {
                \http_response_code(404);
                echo "Method Not Found: {$method}";
                return;
            }
            $inputType = $default["inputType"];
            $outputType = $default["outputType"];
            $rMethod = $default["rMethod"];
        }

        $input = match($inputType) {
            Input::NINJA2 => $this->getInputNinja2(),
            Input::XML    => $this->getInputXml(),
            Input::FORM   => $this->getInputForm(),
            Input::RAW    => $this->getInputRaw(),
            default  => throw new \Exception("Invalid input type: {$inputType}")
        };

        $output = $rMethod->invokeArgs($this, [$input]);

        $outputInfo = match($outputType) {
            Output::NINJA2XML => [ \SimpleXMLElement::class, 'application/xml', 'getOutputNinja2' ],
            Output::NINJA2STR => [ '\is_string',             'application/xml', 'getOutputNinja2' ],
            Output::XML       => [ \SimpleXMLElement::class, 'application/xml', 'getOutputXml'    ],
            Output::FORM      => [ '\is_array',              'text/plain',      'getOutputForm'   ],
            Output::RAW       => [ '\is_string',             'text/plain',      'getOutputRaw'    ],
            Output::HTML      => [ '\is_string',             'text/html',       'getOutputRaw'    ],
            default => throw new \Exception("Invalid output type: {$outputType}")
        };

        // validate return type
        if($output===null) {
            throw new \Exception("{$method}: Invalid output return type. Expected instance of {$outputInfo[0]}");
        } else if(\is_callable($outputInfo[0])) { // \is_array, \is_string
            if(!$outputInfo[0]($output)) {
                throw new \Exception("{$method}: Invalid output return type. Expected true when calling {$outputInfo[0]}");
            }
        } else if($output::class != $outputInfo[0]) { // \SimpleXMLElement
            throw new \Exception("{$method}: Invalid output return type. Expected instance of {$outputInfo[0]}");
        }

        \header("Content-Type: {$outputInfo[1]}");
        echo $this->{$outputInfo[2]}($output);
    }

    private function getInputNinja2(): \SimpleXMLElement {
        $xml = \file_get_contents("php://input");
        if(\preg_match('/^<ninja2>(.+)<\/ninja2>$/', $xml, $matches)) {
            $xml = $this->crypto2->decrypt($matches[1]);
        }
        $output = \simplexml_load_string($xml);
        if($output===false) {
            throw new \Exception("Invalid input Ninja2 XML: {$xml}");
        }
        return $output;
    }

    private function getInputXml(): \SimpleXMLElement {
        $xml = \file_get_contents("php://input");
        $output = \simplexml_load_string($xml);
        if($output===false) {
            throw new \Exception("Invalid input XML: {$xml}");
        }
        return $output;
    }

    private function getInputForm(): array {
        return $_POST;
    }

    private function getInputRaw(): string {
        return \file_get_contents("php://input");
    }

    private function getOutputNinja2(\SimpleXMLElement|string $xmlOrString): string {
        if($xmlOrString instanceof \SimpleXMLElement) {
            $toEncrypt = $xmlOrString->asXML();
            $toEncrypt = \trim(\preg_replace('/<\?xml.+\?>/', '', $toEncrypt)); // remove xml version tag
        } else {
            $toEncrypt = $xmlOrString;
        }
        return "<ninja2>{$this->crypto2->encrypt($toEncrypt)}</ninja2>";
    }

    private function getOutputXml(\SimpleXMLElement $xml): string {
        return $xml->asXML();
    }

    private function getOutputForm(array $form): string {
        return \http_build_query($form);
    }

    private function getOutputRaw(string $raw): string {
        return $raw;
    }

}