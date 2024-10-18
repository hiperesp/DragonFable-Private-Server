<?php
namespace hiperesp\server\projection;

class WarProjection extends Projection {

    public function loaded(): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<warvars/>');
        $xml->addAttribute('intTotal', 5000000);
        $xml->addAttribute('intWar1', 7604226);
        $xml->addAttribute('intWar2', 7);
        $xml->addAttribute('intGold', 109551239150);

        return $xml;
    }
}