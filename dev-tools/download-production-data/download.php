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

$startTime = (int)\microtime(true);
$thingsToDownload = [
    "interface" => [
        "from" => 1,
        "to" => 30,
        "needAuth" => false,
        "endpoint" => "/cf-interfaceload.asp",
        "param" => "intInterfaceID",
    ],
    "hairShopM" => $hairShop = [
        "from" => 1,
        "to" => 50,
        "needAuth" => false,
        "endpoint" => "/cf-hairshopload.asp",
        "param" => "intHairShopID",
        "special" => "hairShop",
    ],
    "hairShopF" => $hairShop,
    "quest" => [
        "from" => 1,
        "to" => 2400,
        "needAuth" => true,
        "endpoint" => "/cf-questload.asp",
        "param" => "intQuestID",
    ],
    "town" => [
        "from" => 1,
        "to" => 2400,
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
    "houseShop" => [
        "from" => 1,
        "to" => 30,
        "needAuth" => true,
        "endpoint" => "/cf-houseshopload.asp",
        "param" => "intShopID",
    ],
    "houseItemShop" => [
        "from" => 1,
        "to" => 110,
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
    "class" => [
        "from" => 1,
        "to" => 200,
        "needAuth" => true,
        "endpoint" => "/cf-classload.asp",
        "param" => "intClassID",
    ],
    "questRewards" => [
        "from" => 1,
        "to" => 2400,
        "needAuth" => true,
        "endpoint" => "/cf-questcomplete-Mar2011.asp",
        "param" => "intQuestID",
        "overrideSkipDownloaded" => true,
        "maxRepeatedItems" => 100,
    ],
];

downloadAll([
    "questRewards" => $thingsToDownload["questRewards"],
]);

function downloadAll(array $thingsToDownload): void {
    global $skipDownloaded;
    $totalToDownload = \array_reduce($thingsToDownload, function($carry, $thing) {
        return $carry + $thing["to"] - $thing["from"] + 1;
    }, 0);

    $lastDownloaded = [];

    $currentItem = 0;
    foreach ($thingsToDownload as $thingToDownload => $thing) {
        for ($i = $thing["from"]; $i <= $thing["to"]; $i++) {

            $percentStr = getPercentString($currentItem, $totalToDownload);
            echo "[0] Downloading {$thingToDownload} {$i} of {$thing["to"]} {$percentStr}\n";

            $success = download($thingToDownload, $i, $skipDownloaded);
            if($i>=($thing["to"]-10) && $success) {
                $lastDownloaded[] = $thingToDownload;
            }
            $currentItem++;
        }
    }
    echo "[0] Downloaded all things\n";
    if($lastDownloaded) {
        echo "[0] ATTENTION : The following things has the last-10 thing downloaded:\n";
        echo "[0] ATTENTION : You should check if there are more things to download\n";
        echo "[0] ATTENTION : Please increase the 'to' value in the array to see if there are more things to download\n";
        foreach($lastDownloaded as $thing) {
            echo "[0] MAYBE MORE: {$thing}\n";
        }
    }
}

function getPercentString(int $current, int $total): string {
    global $startTime;

    $percent = (\number_format($current / $total, 5) * 100)."%";
    $memoryUsageMB = \memory_get_usage(true) / 1024 / 1024;
    $memoryUsageStr = \number_format($memoryUsageMB)."M";

    $elapsedTime = (int)\microtime(true) - $startTime;
    $estimatedTotalTime = ($elapsedTime / ($current + 1)) * $total;
    $remainingTime = $estimatedTotalTime - $elapsedTime;
    $eta = \gmdate("H:i:s", (int)$remainingTime);

    return "({$percent}) - MEM: {$memoryUsageStr} - ETA: {$eta}";
}

function download(string $thingToDownload, int $id, bool $skipDownloaded, array $customParams = []): bool {
    global $sessionToken, $charId, $thingsToDownload;

    if($thingToDownload=="questRewards") {
        $file = __DIR__ . "/downloaded/{$thingToDownload}/{$id}/%rewardId%.xml";
    } else {
        $file = __DIR__ . "/downloaded/{$thingToDownload}/{$id}.xml";
    }

    if(isset($thingsToDownload[$thingToDownload]["overrideSkipDownloaded"])) {
        $skipDownloaded = $thingsToDownload[$thingToDownload]["overrideSkipDownloaded"];
    }

    if($skipDownloaded) {
        if(\file_exists($file)) {
            echo "[0] Skipping {$thingToDownload} {$id} because it already exists\n";
            return true;
        }
    }
    if($thingToDownload=="questRewards") {
        if(!\file_exists(__DIR__."/downloaded/quest/{$id}.xml")) { // quest must exist
            return false;
        }
        if(!isset($customParams["sequenceWithRepeatedItems"])) {
            if(\is_dir(\dirname($file))) {
                if(\count(\scandir(\dirname($file))) < 3) { // verify if directory is empty, . and .. is always there and count.
                    return true;
                }
                if($skipDownloaded) {
                    echo "[0] Skipping {$thingToDownload} {$id} because it already exists\n";
                    return true;
                }
            }
        }
    }

    $thing = $thingsToDownload[$thingToDownload];

    $input = "<{$thing["param"]}>{$id}</{$thing["param"]}>";
    if($thing["needAuth"]) {
        $input.= "<intCharID>{$charId}</intCharID><strToken>{$sessionToken}</strToken>";
    }
    if($thingToDownload === "hairShopM") {
        $input.= "<strGender>M</strGender>";
    } else if($thingToDownload === "hairShopF") {
        $input.= "<strGender>F</strGender>";
    } else if($thingToDownload === "questRewards") {
        if(!startQuest($id)) {
            return false;
        }
        $input.= "<intWaveCount>1</intWaveCount><intRare>0</intRare><intWar>0</intWar><intLootID>-1</intLootID><intExp>0</intExp><intGold>0</intGold>";
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

        if(\in_array($reason, [
            "Invalid Reference",
            "Invalid Item Reference",
            "Database Syntax Error",
        ])) {
            echo "[3] Failed to download {$thingToDownload} {$id}: {$reason}\n";
            return false;
        }
        if(\in_array($reason, [
            "Amulet Required!",
            "Invalid Quest Time!",
            "Quest Level Requirement Not Met!",
        ])) {
            echo "[4] Failed to download {$thingToDownload} {$id}: {$reason}\n";
            \file_put_contents("treatable-errors.txt", "{$thingToDownload} {$id}: {$reason}\n", \FILE_APPEND);
            return false;
        }

        echo "[4] Failed to download {$thingToDownload} {$id}: {$reason}\n";
        die;
        return false;
    }

    $save = true;
    if($thingToDownload=="questRewards") {
        $itemId = $child0->items->attributes()?->ItemID;
        if(!$itemId) {
            echo "[4] Failed to download {$thingToDownload} {$id}: No ItemID\n";
            if(!\is_dir(\dirname($file))) {
                \mkdir(\dirname($file), 0777, true);
            }
            return false;
        }
        $file = \str_replace("%rewardId%", $itemId, $file);
        if(\file_exists($file)) {
            $save = false;
        } else {
            echo "[5] Downloaded {$thingToDownload}/{$id} ({$itemId})\n";
        }
    }

    if($save) {
        if(!\is_dir(\dirname($file))) {
            \mkdir(\dirname($file), 0777, true);
        }
        \file_put_contents($file, $result);
    }

    if($thingToDownload=="questRewards") {
        $maxRepeatedItems = $thing["maxRepeatedItems"];
        $customParams["sequenceWithRepeatedItems"] = isset($customParams["sequenceWithRepeatedItems"]) ? $customParams["sequenceWithRepeatedItems"] : 0;
        if($save) {
            $customParams["sequenceWithRepeatedItems"] = 0;
        } else {
            $customParams["sequenceWithRepeatedItems"]++;
        }
        if($customParams["sequenceWithRepeatedItems"] > $maxRepeatedItems) {
            echo "[5] Stopping quest rewards {$id} because of {$maxRepeatedItems} repeated items\n";
            return true;
        }
        return download($thingToDownload, $id, $skipDownloaded, $customParams);
    }

    return true;
}

function startQuest(int $id) {
    $quest = download("quest", $id, false);
    if(!$quest) {
        return false;
    }
    $xml = \simplexml_load_file(__DIR__ . "/downloaded/quest/{$id}.xml");

    $json = \json_decode(\json_encode($xml), true);
    $minTime = $json["quest"]["@attributes"]["intMinTime"];

    if($minTime > 0) {
        echo "[5] Starting quest {$id}. Waiting {$minTime} minute(s)\n";
    }
    \sleep($minTime * 60);

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