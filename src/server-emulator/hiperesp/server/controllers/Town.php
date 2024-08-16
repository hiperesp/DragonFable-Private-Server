<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;

class Town extends Controller {

    #[Request(
        method: '/cf-changehometown.asp',
        inputType: Input::NINJA2,
        outputType: Output::NINJA2XML
    )]
    public function changeHome(\SimpleXMLElement $input): \SimpleXMLElement {
        // <flash><intTownID>51</intTownID><strToken>LOGINTOKENSTRNG</strToken><intCharID>12345678</intCharID></flash>

        // change home town, then return the load town, but instead of LoadTown, return changeHomeTown
        $loadTown = $this->load($input);
        if($loadTown->getName()!="LoadTown") {
            return $loadTown; // probably error
        }

        $changeTown = new \SimpleXMLElement('<ChangeHomeTown/>');
        $newTown = $changeTown->addChild('newTown');
        foreach ($loadTown->newTown->attributes() as $attrName => $attrValue) {
            $newTown->addAttribute($attrName, $attrValue);
        }

        return $changeTown;
    }

    #[Request(
        method: '/cf-loadtowninfo.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {
        $token = (string)$input->strToken;
        $charID = (int)$input->intCharID;
        $townID = (int)$input->intTownID;

        if($townID==3) {
            return \simplexml_load_string(<<<XML
<LoadTown xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <newTown strQuestFileName="towns/Oaklore/town-oaklore-loader-r1.swf" strQuestXFileName="none" strExtra=""/>
</LoadTown>
XML);
        }
        if($townID==40) {
            return \simplexml_load_string(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<LoadTown xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <newTown strQuestFileName="towns/Oaklore/town-oaklore-2019.swf" strQuestXFileName="none" strExtra="oakloretown=towns/Oaklore/town-oaklore-2019.swf&#10;oaklore=towns/Oaklore/zone-oaklore-forest.swf&#10;map=maps/map-oaklore.swf&#10;Sirvey=towns/Oaklore/town-sirvey.swf&#10;Maya=towns/Oaklore/shop-maya-new.swf"/>
</LoadTown>
XML);
        }
        if($townID==934) {
            return \simplexml_load_string(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<LoadTown xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <newTown strQuestFileName="towns/3Oaklore/town-oaklore-loader-r1.swf" strQuestXFileName="none" strExtra=""/>
</LoadTown>
XML);
        }
        if($townID==935) {
            return \simplexml_load_string(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<LoadTown xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <newTown strQuestFileName="towns/3Oaklore/town-3oaklore-r1.swf?ver=1" strQuestXFileName="none" strExtra="oakloretown=towns/3Oaklore/town-3oaklore-r1.swf?ver=1&#10;oaklore=towns/3Oaklore/zone-oaklore-forest.swf&#10;map=maps/map-oaklore.swf&#10;Sirvey=towns/Oaklore/town-sirvey.swf&#10;Maya=towns/3Oaklore/shop-maya.swf"/>
</LoadTown>
XML);
        }


        return \simplexml_load_string(<<<XML
<error>
    <info code="538.07" reason="Invalid Input!" message="Message" action="None"/>
</error>
XML);
    }

}