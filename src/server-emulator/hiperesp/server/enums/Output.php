<?php declare(strict_types=1);
namespace hiperesp\server\enums;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\util\DragonFableNinja2;

enum Output {
    case NINJA2STR;
    case NINJA2XML;
    case XML;
    case FORM;
    case RAW;
    case HTML;
    case JSON;
    case REDIRECT;
    case NONE;
    case EVENT_SOURCE;

    public function display(mixed $output): void {
        match($this) {
            Output::NINJA2XML, Output::NINJA2STR => $this->ninja2($output),
            Output::XML => $this->xml($output),
            Output::FORM => $this->form($output),
            Output::RAW, Output::HTML => $this->raw($output),
            Output::REDIRECT => $this->redirect($output),
            Output::JSON => $this->json($output),
            Output::NONE => null,
            Output::EVENT_SOURCE => $this->eventSource($output),
        };
    }

    public function error(DFException $exception): void {
        \http_response_code($exception->getHttpStatusCode());
        match($this) {
            Output::NINJA2STR => $this->ninja2($exception->asString()),
            Output::NINJA2XML, Output::XML => $this->xml($exception->asXML()),
            Output::FORM => $this->form($exception->asArray()),
            Output::RAW, Output::HTML => $this->raw($exception->asString()),
            Output::REDIRECT => $this->redirect("/error/{$exception->getHttpStatusCode()}"),
            Output::JSON => $this->json($exception->asArray()),
            Output::NONE => null,
            Output::EVENT_SOURCE => $this->eventSource($exception->asEventSource()),
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

    private function json(mixed $data): void {
        \header("Content-Type: application/json");
        echo \json_encode($data);
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

    private function eventSource(callable $update): void {
        \http_response_code(200);
        \header("Content-Type: text/event-stream");
        \header("Cache-Control: no-cache");
        \header("Connection: keep-alive");

        while(!\connection_aborted()) {
            @\ob_flush();
            @\flush();
            $data = $update();
            if($data) {
                foreach($data as $key => $value) {
                    echo "{$key}: {$value}\n";
                }
                echo "\n";
            }
        }
    }


}