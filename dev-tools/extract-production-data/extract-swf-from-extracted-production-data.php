<?php
$folders = [
    "quest" => __DIR__."/quests/",
    "shop" => __DIR__."/shops/"
];

foreach($folders as $key => $folder) {
    $files = \scandir($folder);
    foreach($files as $file) {
        if($file=="." || $file==".." || $file == ".gitkeep") continue;
        if(\preg_match('/^da_/', $file)) continue; // ignore dragon amulet required files
        if(\preg_match('/^ir_/', $file)) continue; // ignore invalid reference files
        if(\preg_match('/^lvl_/', $file)) continue; // ignore invalid reference files


        $xmlStr = \trim(\file_get_contents("{$folder}{$file}"));

        // count .swf in xmlStr
        $possibleSwfCount = \substr_count($xmlStr, ".swf");
        $swfs = [];

        $xmlStr = \preg_replace('/\r?\n/', "HIPERESP-NEWLINE", $xmlStr);
        $xmlStr = \str_replace('>HIPERESP-NEWLINE<', ">\n<", $xmlStr);

        $xml = \simplexml_load_string($xmlStr);
        if($xml===false) {
            echo "Failed to load XML: {$folder}{$file}\n";
            echo $xmlStr;
            die;
        }

        if($key==="quest") {
            $quest = $xml->quest;
            $questAttributes = $quest->attributes();

            if($questAttributes->strFileName != "none") {
                $swfs[] = $questAttributes->strFileName;
            }
            if($questAttributes->strXFileName != "none") {
                $swfs[] = $questAttributes->strXFileName;
            }
            if($questAttributes->strExtra != "none") {
                $params = \explode('HIPERESP-NEWLINE', $questAttributes->strExtra);
                foreach($params as $param) {
                    $param = \trim($param);
                    if(empty($param)) continue;

                    $paramParts = \explode('=', $param, 2);
                    $paramName = $paramParts[0];
                    $paramValue = $paramParts[1];
                    if(\preg_match('/\.swf/', $paramValue)) { // sometimes swf has query params
                        $swfs[] = $paramValue;
                    }
                }
            }
            if($questAttributes->strMonsterGroupFileName != "none") {
                $swfs[] = "monsters/{$questAttributes->strMonsterGroupFileName}";
            }
        }

        if(\count($swfs) != $possibleSwfCount) {
            echo "SWF count mismatch: {$folder}{$file}\n";
            echo "Possible SWF count: {$possibleSwfCount}\n";
            echo "Actual SWF count: ".\count($swfs)."\n";
            echo "SWFs: ".\json_encode($swfs)."\n";
            die;
        }

        foreach($swfs as $swf) {
            extractSwf($swf);
        }
    }
}

function extractSwf($swf) {
    // remove query params
    $swf = \explode('?', $swf)[0];

    $swfParts = \explode('/', $swf);

    if($swfParts) {
        if(\in_array($swfParts[0], ["towns", "zones", "shops", "quests", "random"])) {
            $swf = "maps/{$swf}";
        }
    }

    $skips = [
        "maps/random/ramdom-sandseagate-b.swf",
        "maps/towns/SulenEska/quest-encampment.swf",
    ];
    if(\in_array($swf, $skips)) {
        return;
    }

    $file = __DIR__."/../../src/cdn/gamefiles/{$swf}";
    $url = "http://localhost:40000/cdn/gamefiles/{$swf}";

    if(\file_exists($file)) {
        $data = \file_get_contents($file);
        if($data[0] == 'C' && $data[1] == 'W' && $data[2] == 'S') {
            // echo "Already exists: {$swf}\n";
            return;
        } else {
            echo "Invalid existing SWF: {$swf}\n";
            // die;
        }
    }

    \file_get_contents($url);

    if(\file_exists($file)) {
        $data = \file_get_contents($file);
        if($data[0] == 'C' && $data[1] == 'W' && $data[2] == 'S') {
            echo "Downloaded: {$swf}\n";
            return;
        } else {
            echo "Invalid downloaded SWF: {$swf}\n";
            // die;
        }
    } else {
        echo "Failed to download: {$swf}\n";
        // die;
    }
}