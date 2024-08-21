<?php
namespace hiperesp\server\enums;

use hiperesp\server\util\DragonFableNinja2;

enum Output {
    case NINJA2STR;
    case NINJA2XML;
    case XML;
    case FORM;
    case RAW;
    case HTML;
    case REDIRECT;

    public function display(mixed $output): void {
        match($this) {
            Output::NINJA2XML, Output::NINJA2STR => $this->ninja2($output),
            Output::XML => $this->xml($output),
            Output::FORM => $this->form($output),
            Output::RAW, Output::HTML => $this->raw($output),
            Output::REDIRECT => $this->redirect($output),
        };
    }

    private function ninja2(\SimpleXMLElement|string $xmlOrString): void {
        if($xmlOrString instanceof \SimpleXMLElement) {
            $toEncrypt = $xmlOrString->asXML();
            $toEncrypt = \trim(\preg_replace('/<\?xml.+\?>/', '', $toEncrypt)); // remove xml version tag
        } else {
            $toEncrypt = $xmlOrString;
        }

        $ninja2 = new DragonFableNinja2;
        $this->xml(new \SimpleXMLElement("<ninja2>{$ninja2->encrypt($toEncrypt)}</ninja2>"));
    }

    private function xml(\SimpleXMLElement $xml): void {
        \header("Content-Type: application/xml");
        echo $xml->asXML();
    }

    private function form(array $form): void {
        \header("Content-Type: text/plain");
        echo "&".\http_build_query($form);
    }

    private function raw(string $raw): void {
        $contentType = match($this) {
            Output::RAW => "text/plain",
            Output::HTML => "text/html",
        };

        \header("Content-Type: {$contentType}");
        echo $raw;
    }

    private function redirect(string $url): void {
        \http_response_code(302);
        \header("Location: {$url}");

        $this->raw("<!DOCTYPE html><html><head><meta http-equiv=\"refresh\" content=\"1;url={$url}\"></head><body><h1>Redirecting...</h1><hr><a href=\"{$url}\">Click here if you are not redirected</a></body></html>");
    }


}