<?php
namespace hiperesp\server\controllers;

use hiperesp\server\attributes\Request;
use hiperesp\server\enums\Input;
use hiperesp\server\enums\Output;

class BankController extends Controller {

    // [WIP]
    #[Request(
        endpoint: '/cf-bankload.asp',
        inputType: Input::NINJA2,
        outputType: Output::XML
    )]
    public function load(\SimpleXMLElement $input): \SimpleXMLElement {

        return \simplexml_load_string(<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<bank xmlns:sql="urn:schemas-microsoft-com:xml-sql">
    <bank bankID="47643338" strCharacterName="hiperesp            "/>
</bank>
XML);
    }

}