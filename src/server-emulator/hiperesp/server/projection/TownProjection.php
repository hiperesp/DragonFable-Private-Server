<?php
namespace hiperesp\server\projection;

use hiperesp\server\vo\QuestVO;

class TownProjection extends Projection {

    public function loaded(QuestVO $quest): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<LoadTown/>');
        $newTownEl = $xml->addChild('newTown');
        $newTownEl->addAttribute('strQuestFileName', $quest->swf);
        $newTownEl->addAttribute('strQuestXFileName', $quest->swfX);
        $newTownEl->addAttribute('strExtra', $quest->extra);

        return $xml;
    }

    public function changedHome(QuestVO $quest): \SimpleXMLElement {
        $xml = new \SimpleXMLElement('<changeHomeTown/>');
        $newTownEl = $xml->addChild('newTown');
        $newTownEl->addAttribute('strQuestFileName', $quest->swf);
        $newTownEl->addAttribute('strQuestXFileName', $quest->swfX);
        $newTownEl->addAttribute('strExtra', $quest->extra);

        return $xml;
    }

}