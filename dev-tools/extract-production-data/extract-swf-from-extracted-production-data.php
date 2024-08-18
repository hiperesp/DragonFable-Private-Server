<?php
$folders = [
    "quest" => __DIR__."/quests/",
    "shop" => __DIR__."/shops/"
];

foreach($folders as $key => $folder) {
    $files = \scandir($folder);
    \usort($files, function($a, $b) {
        return \strnatcmp(\strtolower($a), \strtolower($b));
    });
    foreach($files as $file) {
        if($file=="." || $file==".." || $file == ".gitkeep") continue;
        if(\preg_match('/^da_quest/', $file)) continue; // ignore dragon amulet required files
        if(\preg_match('/^ir_quest/', $file)) continue; // ignore invalid reference files
        if(\preg_match('/^lvl_quest/', $file)) continue; // ignore level required files
        if(\preg_match('/^empty_shop/', $file)) continue; // ignore empty shop files

        // echo "Processing {$file}:\n";

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
                $swfs[] = "{$questAttributes->strFileName}";
            }
            if($questAttributes->strXFileName != "none") {
                $swfs[] = "{$questAttributes->strXFileName}";
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
                        $swfs[] = "{$paramValue}";
                    } else if(\preg_match('/\.swf/', $paramName)) {
                        $swfs[] = "{$paramName}";
                    }
                }
            }
            if($questAttributes->strMonsterGroupFileName != "none") {
                $swfs[] = "monsters/{$questAttributes->strMonsterGroupFileName}";
            }

            foreach($quest->monsters as $monster) {
                $monsterAttributes = $monster->attributes();
                if($monsterAttributes->strMonsterFileName != "none") {
                    $swfs[] = "monsters/{$monsterAttributes->strMonsterFileName}";
                }
            }
        }
        if($key=="shop") {
            $shop = $xml->shop;

            foreach($shop->items as $item) {
                $itemAttributes = $item->attributes();

                if($itemAttributes->strFileName != "none") {
                    $swfs[] = "{$itemAttributes->strFileName}";
                }
            }
        }

        $swfs = \array_values(\array_filter($swfs, function($swf) {
            return \preg_match('/\.swf/', $swf);
        }));

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

    echo "Done with {$key}\n";
}

echo "Done with all\n";

function extractSwf($swf) {
    $swf = \trim($swf);

    // normalize path
    $swf = \preg_replace('/\\\\/', '/', $swf);

    // remove query params
    $swf = \explode('?', $swf)[0];

    $swfParts = \explode('/', $swf);

    // remove empty parts
    $swfParts = \array_values(\array_filter($swfParts, function($part) {
        return !empty($part);
    }));

    if($swfParts) {
        if(\in_array(\strtolower($swfParts[0]), ["towns", "zones", "shops", "quests", "random", "wars"])) {
            $swf = "maps/{$swf}";
        }
    }

    $skips = [
        // "maps/random/ramdom-sandseagate-b.swf",
        // "maps/towns/SulenEska/quest-encampment.swf",
    ];
    if(\in_array($swf, $skips)) {
        return;
    }

    $file = __DIR__."/../../src/cdn/gamefiles/{$swf}";

    $swfUrl = \str_replace(' ', '%20', $swf);
    $url = "http://localhost:40000/cdn/gamefiles/{$swfUrl}";

    if(\file_exists($file)) {
        $data = \file_get_contents($file);
        if(($data[0] == 'C' || $data[0] == 'F') && $data[1] == 'W' && $data[2] == 'S') {
            // echo "Already exists: {$swf}\n";
            return;
        } else {
            echo "Invalid existing SWF: {$swf}\n";
            die;
        }
    }

    \file_get_contents($url);

    if(\file_exists($file)) {
        $data = \file_get_contents($file);
        if(($data[0] == 'C' || $data[0] == 'F') && $data[1] == 'W' && $data[2] == 'S') {
            echo "Downloaded: {$swf}\n";
            return;
        } else {
            echo "Invalid downloaded SWF: {$swf}\n";
            die;
        }
    } else {
        echo "Failed to download: {$swf}\n";
        // die;
    }
}