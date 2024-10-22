#!/usr/bin/env php
<?php
// $sessionToken = "";
// $charId = 0;
// $skipDownloaded = true;

if(!isset($sessionToken)) {
    echo "What is your session token?\n";
    echo "Session Token: ";
    $sessionToken = \trim(\fgets(\STDIN));
}
if(!isset($charId)) {
    echo "What is your character ID?\n";
    echo "Character ID: ";
    $charId = (int)\trim(\fgets(\STDIN));
}
if(!isset($skipDownloaded)) {
    echo "Skip downloaded files? (y/n)\n";
    echo "Skip Downloaded: ";
    $res = \trim(\fgets(\STDIN));
    $skipDownloaded = $res === "y";
}


$thingsToDownload = [
    "quest" => [
        "from" => 1,
        "to" => 2200,
        "needAuth" => true,
        "endpoint" => "/cf-questload.asp",
        "param" => "intQuestID",
    ],
    "town" => [
        "from" => 1,
        "to" => 2200,
        "needAuth" => true,
        "endpoint" => "/cf-loadtowninfo.asp",
        "param" => "intTownID",
    ],
    "shop" => [
        "from" => 1,
        "to" => 850,
        "needAuth" => false,
        "endpoint" => "/cf-shopload.asp",
        "param" => "intShopID",
    ],
    "interface" => [
        "from" => 1,
        "to" => 30,
        "needAuth" => false,
        "endpoint" => "/cf-interfaceload.asp",
        "param" => "intInterfaceID",
    ],
    "houseShop" => [
        "from" => 1,
        "to" => 30,
        "needAuth" => true,
        "endpoint" => "/cf-houseshopload.asp",
        "param" => "intShopID",
    ],
    "houseItemShop" => [
        "from" => 1,
        "to" => 100,
        "needAuth" => false,
        "endpoint" => "/cf-loadhouseitemshop.asp",
        "param" => "intHouseItemShopID",
    ],
    "mergeShop" => [
        "from" => 1,
        "to" => 450,
        "needAuth" => false,
        "endpoint" => "/cf-mergeshopload.asp",
        "param" => "intMergeShopID",
    ],
    "classes" => [
        "from" => 1,
        "to" => 200,
        "needAuth" => true,
        "endpoint" => "/cf-classload.asp",
        "param" => "intClassID",
    ],
];

downloadAll();

function downloadAll(): void {
    global $thingsToDownload;
    $totalThingsToDownload = \count($thingsToDownload);
    $maxProgressPerThing = 1 / $totalThingsToDownload;

    $lastDownloaded = [];

    $currentThingToDownload = 0;
    foreach ($thingsToDownload as $thingToDownload => $thing) {
        for ($i = $thing["from"]; $i <= $thing["to"]; $i++) {
            $percent = (\number_format($maxProgressPerThing * $currentThingToDownload + ($i - $thing["from"]) / ($thing["to"] - $thing["from"] + 1) * $maxProgressPerThing, 5) * 100)."%";
            echo "[0] Downloading {$thingToDownload} {$i} of {$thing["to"]} ({$percent})\n";
            $success = download($thingToDownload, $i);
            if($i===$thing["to"] && $success) {
                $lastDownloaded[] = $thingToDownload;
            }
        }
        $currentThingToDownload++;
    }
    echo "[0] Downloaded all things\n";
    if($lastDownloaded) {
        echo "[0] ATTENTION : The following things has the last thing downloaded:\n";
        echo "[0] ATTENTION : You should check if there are more things to download\n";
        echo "[0] ATTENTION : Please increase the 'to' value in the array to see if there are more things to download\n";
        foreach($lastDownloaded as $thing) {
            echo "[0] MAYBE MORE: {$thing}\n";
        }
    }
}

function download(string $thingToDownload, int $id): bool {
    global $sessionToken, $charId, $skipDownloaded, $thingsToDownload;

    $file = __DIR__ . "/downloaded/{$thingToDownload}/{$id}.xml";

    if($skipDownloaded && \file_exists($file)) {
        // echo "[0] Skipping {$thingToDownload} {$id} because it already exists\n";
        return true;
    }

    $thing = $thingsToDownload[$thingToDownload];

    $input = "<{$thing["param"]}>{$id}</{$thing["param"]}>";
    if($thing["needAuth"]) {
        $input.= "<intCharID>{$charId}</intCharID><strToken>{$sessionToken}</strToken>";
    }
    $input = "<flash>{$input}</flash>";
    $input = "<ninja2>".encrypt($input)."</ninja2>";

    $ch = \curl_init();
    \curl_setopt($ch, \CURLOPT_URL, "http://dragonfable.battleon.com/game{$thing["endpoint"]}");
    \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
    \curl_setopt($ch, \CURLOPT_POST, 1);
    \curl_setopt($ch, \CURLOPT_POSTFIELDS, $input);
    \curl_setopt($ch, \CURLOPT_HTTPHEADER, [
        "Content-Type: application/x-www-form-urlencoded",
        "Content-Length: ".\strlen($input),
    ]);

    $result = \curl_exec($ch);
    \curl_close($ch);

    $result = \mb_convert_encoding($result, 'ISO-8859-1', 'UTF-8');

    if(\preg_match('/<ninja2>(.*)<\/ninja2>/', $result, $matches)) {
        $result = decrypt($matches[1]);
    }

    $xml = \simplexml_load_string($result);
    if($xml === false) {
        echo "[1] Failed to download {$thingToDownload} {$id}: Invalid XML\n";
        die;
        return false;
    }
    $child0 = @$xml->children()[0];
    if(!$child0) {
        echo "[2] Failed to download {$thingToDownload} {$id}: No children\n";
        return false;
    }
    if($child0->getName() === "info") {
        /** @var \SimpleXMLElement $child0 */
        $reason = $child0->attributes()->reason;
        if($reason == "Invalid Reference") {
            echo "[3] Failed to download {$thingToDownload} {$id}: Invalid Reference\n";
            return false;
        }
        if($reason == "Invalid Item Reference") {
            echo "[4] Failed to download {$thingToDownload} {$id}: Invalid Item Reference\n";
            return false;
        }

        echo "[5] Failed to download {$thingToDownload} {$id}: {$reason}\n";
        die;
        return false;
    }

    if(!\is_dir(\dirname($file))) {
        \mkdir(\dirname($file), 0777, true);
    }
    \file_put_contents($file, $result);
    return true;
}

function decrypt(string $theText): string {
    $decrypted = "";
    $key = "ZorbakOwnsYou";

    $textLength = \strlen($theText);
    $keyLength = \strlen($key);

    for($i=0; $i<$textLength; $i+=4) {
        $charP1 = \base_convert(\substr($theText, $i, 2), 30, 10);
        $charP2 = \base_convert(\substr($theText, $i + 2, 2), 30, 10);
        $charP3 = \ord($key[$i / 4 % $keyLength]);
        $decrypted .= \chr($charP1 - $charP2 - $charP3);
    }
    return $decrypted;
}
function encrypt(string $theText): string {
    $encrypted = "";
    $key = "ZorbakOwnsYou";

    $textLength = \strlen($theText);
    $keyLength = \strlen($key);

    for($i=0; $i<$textLength; $i++) {
        $random = \floor(\mt_rand() / \mt_getrandmax() * 66) + 33;
        $char = \ord($key[$i % $keyLength]);
        $encrypted .= \base_convert(\ord($theText[$i]) + $random + $char, 10, 30).\base_convert($random, 10, 30);
    }
    return $encrypted;
}