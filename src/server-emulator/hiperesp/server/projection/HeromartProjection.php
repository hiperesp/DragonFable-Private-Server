<?php
namespace hiperesp\server\projection;

class HeromartProjection extends Projection {

    public function success(): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<DCBuy/>');
        $xml->addChild('status', 'SUCCESS');

        return $xml;
    }

}