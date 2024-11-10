<?php
namespace hiperesp\server\projection;

use hiperesp\server\vo\HairShopVO;
use hiperesp\server\vo\HairVO;

class HairShopProjection extends Projection {

    public function loaded(HairShopVO $shop, string $gender): \SimpleXMLElement {

        $xml = new \SimpleXMLElement('<hairShop/>');
        $shopEl = $xml->addChild('HairShop');
        $shopEl->addAttribute('HairShopID', $shop->id);
        $shopEl->addAttribute('strHairShopName', $shop->name);
        $shopEl->addAttribute('strFileName', $shop->swf);

        foreach($shop->getHairList($gender) as $hair) {
            $itemEl = $shopEl->addChild('hair');

            $itemEl->addAttribute('HairID', $hair->id);
            $itemEl->addAttribute('strName', $hair->name);
            $itemEl->addAttribute('strFileName', $hair->swf);
            $itemEl->addAttribute('intFrame', $hair->frame);
            $itemEl->addAttribute('intPrice', $hair->price);
            $itemEl->addAttribute('strGender', $hair->gender);
            $itemEl->addAttribute('RaceID', $hair->raceId);
            $itemEl->addAttribute('bitEarVisible', $hair->earVisible ? '1' : '0');
        }

        return $xml;
    }

    public function bought(HairVO $hair): \SimpleXMLElement {

        $xml = new \SimpleXMLElement('<hairBuy/>');

        return $xml;
    }

}