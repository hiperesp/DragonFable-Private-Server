<?php declare(strict_types=1);
namespace hiperesp\server\enums;

use hiperesp\server\util\DragonFableNinja2;

enum Input {

    case NONE;
    case NINJA2;
    case XML;
    case FORM;
    case JSON;
    case QUERY;
    case RAW;

    public function get(): mixed {
        return match($this) {
            Input::NINJA2 => $this->ninja2(),
            Input::XML => $this->xml(),
            Input::FORM => $this->form(),
            Input::JSON => $this->json(),
            Input::QUERY => $this->query(),
            Input::RAW => $this->raw(),
            Input::NONE => null,
        };
    }

    private function ninja2(): \SimpleXMLElement {
        $xml = \file_get_contents("php://input");
        if(\preg_match('/^<ninja2>(.+)<\/ninja2>$/', $xml, $matches)) {
            $ninja2 = new DragonFableNinja2;
            $xml = $ninja2->decrypt($matches[1]);
        }
        $output = \simplexml_load_string($xml);
        if($output===false) {
            throw new \Exception("Invalid input Ninja2 XML: {$xml}");
        }
        return $output;
    }

    private function xml(): \SimpleXMLElement {
        $xml = \file_get_contents("php://input");
        $output = \simplexml_load_string($xml);
        if($output===false) {
            throw new \Exception("Invalid input XML: {$xml}");
        }
        return $output;
    }

    private function form(): array {
        return $_POST;
    }

    private function query(): array {
        return $_GET;
    }

    private function json(): mixed {
        return \json_decode($this->raw(), true);
    }

    private function raw(): string {
        return \file_get_contents("php://input");
    }
}