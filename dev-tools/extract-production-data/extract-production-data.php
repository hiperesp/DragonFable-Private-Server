<?php

$data = [
    "quest" => [
        "minId" => 1,
        "maxId" => 2172,
        "skips" => [],
        "onlyIds" => [
            # lvl
            462, 
        ],
    ],
    "shop" => [
        "minId" => 0,
        "maxId" => 817,
        "skips" => [],
    ],
    "interface" => [
        "minId" => 1,
        "maxId" => 200,
        "skips" => [],
    ],
    "houseShop" => [
        "minId" => 0,
        "maxId" => 20,
        "skips" => [],
    ],
    "houseItemShop" => [
        "minId" => 0,
        "maxId" => 200,
        "skips" => [],
    ],
    "mergeShop" => [
        "minId" => 0,
        "maxId" => 500,
        "skips" => [],
    ],
    //? classes
    //? dragoncustomize
    //? dragons
    //? equipment
    //? hairlist (extracted all from signup, need more) + hair vendors
    //? interfaces
    //? item merges + merge vendors
    //? quest merge
    //? vendors
    //? wars
    //? war waves
];

echo "What do you want to extract?\n";
foreach ($data as $key => $value) {
    echo " - {$key}\n";
}
echo "Choice: ";
$choice = \trim(\fgets(\STDIN));

if (!isset($data[$choice])) {
    echo "Invalid choice\n";
    exit;
}

$data = $data[$choice];

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

if($choice=="quest") {
    for($questId=$data['minId']; $questId<=$data['maxId']; $questId++) {
        if(\in_array($questId, $data['skips'])) {
            echo "Skipping quest {$questId}...\n";
            continue;
        }
        if($data['onlyIds'] && !\in_array($questId, $data['onlyIds'])) {
            continue;
        }
        if(\file_exists("quests/quest{$questId}.xml")) {
            // echo "Quest {$questId} already extracted\n";
            continue;
        }
        if(\file_exists("quests/da_quest{$questId}.xml")) {
            // echo "Quest {$questId} not extracted due to Dragon Amulet requirement\n";
            continue;
        }
        if(\file_exists("quests/lvl_quest{$questId}.xml")) {
            // echo "Quest {$questId} not extracted due to level requirement\n";
            continue;
        }
        if(\file_exists("quests/ir_quest{$questId}.xml")) {
            // echo "Quest {$questId} not extracted due to invalid reference\n";
            continue;
        }
        echo "Extracting quest {$questId}...\n";
        try {
            $quest = getQuestData($questId);
            \file_put_contents("quests/quest{$questId}.xml", $quest);
            echo "Quest: {$questId} done\n";
        } catch (\Exception $e) {
            echo "Quest: {$questId} failed\n";
            echo "Error: {$e->getMessage()}\n";
            if($e->getMessage() === "Amulet Required!") {
                \file_put_contents("quests/da_quest{$questId}.xml", "");
            } else if($e->getMessage() === "Quest Level Requirement Not Met!") {
                \file_put_contents("quests/lvl_quest{$questId}.xml", "");
            } else if($e->getMessage() === 'Invalid Reference') {
                \file_put_contents("quests/ir_quest{$questId}.xml", "");
            }
            // die;
        }
    }

} else if($choice == "shop") {
    for($shopId=$data['minId']; $shopId<=$data['maxId']; $shopId++) {
        if(\in_array($shopId, $data['skips'])) {
            echo "Skipping shop {$shopId}...\n";
            continue;
        }
        if(\file_exists("shops/shop{$shopId}.xml")) {
            // echo "Shop {$shopId} already extracted\n";
            continue;
        }
        if(\file_exists("shops/empty_shop{$shopId}.xml")) {
            // echo "Shop {$shopId} not extracted due to invalid reference\n";
            continue;
        }
        echo "Extracting shop {$shopId}...\n";
        try {
            $shop = getShopData($shopId);
            \file_put_contents("shops/shop{$shopId}.xml", $shop);
            echo "Shop: {$shopId} done\n";
        } catch (\Exception $e) {
            echo "Shop: {$shopId} failed\n";
            echo "Error: {$e->getMessage()}\n";
            if($e->getMessage() === 'Empty shop') {
                \file_put_contents("shops/empty_shop{$shopId}.xml", "");
            }
        }
    }

} else if($choice == "interface") {
    for($interfaceId=$data['minId']; $interfaceId<=$data['maxId']; $interfaceId++) {
        if(\in_array($interfaceId, $data['skips'])) {
            echo "Skipping interface {$interfaceId}...\n";
            continue;
        }
        if(\file_exists("interfaces/interface{$interfaceId}.xml")) {
            // echo "Interface {$interfaceId} already extracted\n";
            continue;
        }
        echo "Extracting interface {$interfaceId}...\n";
        try {
            $interface = getInterfaceData($interfaceId);
            \file_put_contents("interfaces/interface{$interfaceId}.xml", $interface);
            echo "Interface: {$interfaceId} done\n";
        } catch (\Exception $e) {
            echo "Interface: {$interfaceId} failed\n";
            echo "Error: {$e->getMessage()}\n";
        }
    }

} else if($choice == "houseShop") {
    for($houseShopId=$data['minId']; $houseShopId<=$data['maxId']; $houseShopId++) {
        if(\in_array($houseShopId, $data['skips'])) {
            echo "Skipping house shop {$houseShopId}...\n";
            continue;
        }
        if(\file_exists("houseShops/houseShop{$houseShopId}.xml")) {
            // echo "House shop {$houseShopId} already extracted\n";
            continue;
        }
        echo "Extracting house shop {$houseShopId}...\n";
        try {
            $houseShop = getHouseShop($houseShopId);
            \file_put_contents("houseShops/houseShop{$houseShopId}.xml", $houseShop);
            echo "House shop: {$houseShopId} done\n";
        } catch (\Exception $e) {
            echo "House shop: {$houseShopId} failed\n";
            echo "Error: {$e->getMessage()}\n";
        }
    }

} else if($choice == "houseItemShop") {
    for($houseItemShopId=$data['minId']; $houseItemShopId<=$data['maxId']; $houseItemShopId++) {
        if(\in_array($houseItemShopId, $data['skips'])) {
            echo "Skipping house item shop {$houseItemShopId}...\n";
            continue;
        }
        if(\file_exists("houseItemShops/houseItemShop{$houseItemShopId}.xml")) {
            // echo "House item shop {$houseItemShopId} already extracted\n";
            continue;
        }
        echo "Extracting house item shop {$houseItemShopId}...\n";
        try {
            $houseItemShop = getHouseItemShop($houseItemShopId);
            \file_put_contents("houseItemShops/houseItemShop{$houseItemShopId}.xml", $houseItemShop);
            echo "House item shop: {$houseItemShopId} done\n";
        } catch (\Exception $e) {
            echo "House item shop: {$houseItemShopId} failed\n";
            echo "Error: {$e->getMessage()}\n";
        }
    }

} else if($choice == "mergeShop") {
    for($mergeShopId=$data['minId']; $mergeShopId<=$data['maxId']; $mergeShopId++) {
        if(\in_array($mergeShopId, $data['skips'])) {
            echo "Skipping merge shop {$mergeShopId}...\n";
            continue;
        }
        if(\file_exists("mergeShops/mergeShop{$mergeShopId}.xml")) {
            // echo "Merge shop {$mergeShopId} already extracted\n";
            continue;
        }
        echo "Extracting merge shop {$mergeShopId}...\n";
        try {
            $mergeShop = getMergeShop($mergeShopId);
            \file_put_contents("mergeShops/mergeShop{$mergeShopId}.xml", $mergeShop);
            echo "Merge shop: {$mergeShopId} done\n";
        } catch (\Exception $e) {
            echo "Merge shop: {$mergeShopId} failed\n";
            echo "Error: {$e->getMessage()}\n";
        }
    }

} else {
    echo "Invalid choice\n";
    exit;
}

echo "Done\n";
die;

function getQuestData(int $questId): string {
    global $sessionToken;
    global $charId;

    $ch = \curl_init();
    \curl_setopt($ch, \CURLOPT_URL, "http://dragonfable.battleon.com/game/cf-questload.asp");
    \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
    \curl_setopt($ch, \CURLOPT_POST, 1);
    $data = "<ninja2>".encrypt("<flash><strToken>{$sessionToken}</strToken><intCharID>{$charId}</intCharID><intQuestID>{$questId}</intQuestID></flash>")."</ninja2>";
    \curl_setopt($ch, \CURLOPT_POSTFIELDS, $data);

    $headers = [
        "Content-Type: application/x-www-form-urlencoded",
        "Content-Length: ".\strlen($data),
    ];
    \curl_setopt($ch, \CURLOPT_HTTPHEADER, $headers);

    $result = \curl_exec($ch);
    \curl_close($ch);

    if(\curl_errno($ch)) {
        throw new \Exception('Curl error: ' . \curl_error($ch));
    }
    if(!$result) {
        throw new \Exception('Empty response');
    }

    $result = \mb_convert_encoding($result, 'ISO-8859-1', 'UTF-8');
    $validateXml = \simplexml_load_string($result);
    if($validateXml === false) {
        echo $result;die;
        throw new \Exception('Invalid XML');
    }
    $child0 = $validateXml->children()[0];
    if(!$child0) {
        echo $result;die;
        throw new \Exception('Invalid XML');
    }
    if($child0->getName() === "info") {
        /** @var \SimpleXMLElement $child0 */
        $reason = $child0->attributes()->reason;
        throw new \Exception("{$reason}");
    }

    return $result;
}

function getShopData(int $shopId): string {
    $ch = \curl_init();
    \curl_setopt($ch, \CURLOPT_URL, "http://dragonfable.battleon.com/game/cf-shopload.asp");
    \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
    \curl_setopt($ch, \CURLOPT_POST, 1);
    $data = "<ninja2>".encrypt("<flash><intShopID>{$shopId}</intShopID></flash>")."</ninja2>";
    \curl_setopt($ch, \CURLOPT_POSTFIELDS, $data);

    $headers = [
        "Content-Type: application/x-www-form-urlencoded",
        "Content-Length: ".\strlen($data),
    ];
    \curl_setopt($ch, \CURLOPT_HTTPHEADER, $headers);

    $result = \curl_exec($ch);
    \curl_close($ch);

    if(\curl_errno($ch)) {
        throw new \Exception('Curl error: ' . \curl_error($ch));
    }
    if(!$result) {
        throw new \Exception('Empty response');
    }

    $result = \mb_convert_encoding($result, 'ISO-8859-1', 'UTF-8');

    if(\strpos($result, '<shop xmlns:sql="urn:schemas-microsoft-com:xml-sql"></shop>') !== false) {
        throw new \Exception('Empty shop');
    }
    $validateXml = \simplexml_load_string($result);
    if($validateXml === false) {
        echo $result."??";die;
        throw new \Exception('Invalid XML');
    }
    $child0 = $validateXml->children()[0];
    if(!$child0) {
        echo $result."???";die;
        throw new \Exception('Invalid XML');
    }
    if($child0->getName() === "info") {
        /** @var \SimpleXMLElement $child0 */
        $reason = $child0->attributes()->reason;
        throw new \Exception("{$reason}");
    }

    return $result;
}

function getInterfaceData(int $interfaceId): string {
    $ch = \curl_init();
    \curl_setopt($ch, \CURLOPT_URL, "http://dragonfable.battleon.com/game/cf-interfaceload.asp");
    \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
    \curl_setopt($ch, \CURLOPT_POST, 1);
    $data = "<ninja2>".encrypt("<flash><intInterfaceID>{$interfaceId}</intInterfaceID></flash>")."</ninja2>";
    \curl_setopt($ch, \CURLOPT_POSTFIELDS, $data);

    $headers = [
        "Content-Type: application/x-www-form-urlencoded",
        "Content-Length: ".\strlen($data),
    ];
    \curl_setopt($ch, \CURLOPT_HTTPHEADER, $headers);

    $result = \curl_exec($ch);
    \curl_close($ch);

    if(\curl_errno($ch)) {
        throw new \Exception('Curl error: ' . \curl_error($ch));
    }
    if(!$result) {
        throw new \Exception('Empty response');
    }

    $result = \mb_convert_encoding($result, 'ISO-8859-1', 'UTF-8');
    $validateXml = \simplexml_load_string($result);
    if($validateXml === false) {
        echo $result;die;
        throw new \Exception('Invalid XML');
    }
    $child0 = $validateXml->children()[0];
    if(!$child0) {
        throw new \Exception('Invalid XML');
    }
    if($child0->getName() === "info") {
        /** @var \SimpleXMLElement $child0 */
        $reason = $child0->attributes()->reason;
        throw new \Exception("{$reason}");
    }

    return $result;
}

function getHouseShop(int $shopId): string {
    global $sessionToken;
    global $charId;

    $ch = \curl_init();
    \curl_setopt($ch, \CURLOPT_URL, "http://dragonfable.battleon.com/game/cf-houseshopload.asp");
    \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
    \curl_setopt($ch, \CURLOPT_POST, 1);
    $data = "<ninja2>".encrypt("<flash><strToken>{$sessionToken}</strToken><intCharID>{$charId}</intCharID><intShopID>{$shopId}</intShopID></flash>")."</ninja2>";
    \curl_setopt($ch, \CURLOPT_POSTFIELDS, $data);

    $headers = [
        "Content-Type: application/x-www-form-urlencoded",
        "Content-Length: ".\strlen($data),
    ];
    \curl_setopt($ch, \CURLOPT_HTTPHEADER, $headers);

    $result = \curl_exec($ch);
    \curl_close($ch);

    if(\curl_errno($ch)) {
        throw new \Exception('Curl error: ' . \curl_error($ch));
    }
    if(!$result) {
        throw new \Exception('Empty response');
    }

    $result = \mb_convert_encoding($result, 'ISO-8859-1', 'UTF-8');

    if(\strpos($result, '<houseshop xmlns:sql="urn:schemas-microsoft-com:xml-sql"></houseshop>') !== false) {
        throw new \Exception('Empty shop');
    }
    $validateXml = \simplexml_load_string($result);
    if($validateXml === false) {
        echo $result."??";die;
        throw new \Exception('Invalid XML');
    }
    $child0 = $validateXml->children()[0];
    if(!$child0) {
        echo $result."???";die;
        throw new \Exception('Invalid XML');
    }
    if($child0->getName() === "info") {
        /** @var \SimpleXMLElement $child0 */
        $reason = $child0->attributes()->reason;
        throw new \Exception("{$reason}");
    }

    return $result;
}

function getHouseItemShop(int $shopId): string {
    $ch = \curl_init();
    \curl_setopt($ch, \CURLOPT_URL, "http://dragonfable.battleon.com/game/cf-loadhouseitemshop.asp");
    \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
    \curl_setopt($ch, \CURLOPT_POST, 1);
    $data = "<ninja2>".encrypt("<flash><intHouseItemShopID>{$shopId}</intHouseItemShopID></flash>")."</ninja2>";
    \curl_setopt($ch, \CURLOPT_POSTFIELDS, $data);

    $headers = [
        "Content-Type: application/x-www-form-urlencoded",
        "Content-Length: ".\strlen($data),
    ];
    \curl_setopt($ch, \CURLOPT_HTTPHEADER, $headers);

    $result = \curl_exec($ch);
    \curl_close($ch);

    if(\curl_errno($ch)) {
        throw new \Exception('Curl error: ' . \curl_error($ch));
    }
    if(!$result) {
        throw new \Exception('Empty response');
    }

    $result = \mb_convert_encoding($result, 'ISO-8859-1', 'UTF-8');

    if(\strpos($result, '<houseitemshop xmlns:sql="urn:schemas-microsoft-com:xml-sql"></houseitemshop>') !== false) {
        throw new \Exception('Empty shop');
    }
    $validateXml = \simplexml_load_string($result);
    if($validateXml === false) {
        echo $result."??";die;
        throw new \Exception('Invalid XML');
    }
    $child0 = $validateXml->children()[0];
    if(!$child0) {
        echo $result."???";die;
        throw new \Exception('Invalid XML');
    }
    $child0 = $validateXml->children()[0];
    if(!$child0) {
        throw new \Exception('Invalid XML');
    }
    if($child0->getName() === "info") {
        /** @var \SimpleXMLElement $child0 */
        $reason = $child0->attributes()->reason;
        throw new \Exception("{$reason}");
    }

    return $result;
}

function getMergeShop(int $shopId): string {
    $ch = \curl_init();
    \curl_setopt($ch, \CURLOPT_URL, "http://dragonfable.battleon.com/game/cf-mergeshopload.asp");
    \curl_setopt($ch, \CURLOPT_RETURNTRANSFER, 1);
    \curl_setopt($ch, \CURLOPT_POST, 1);
    $data = "<ninja2>".encrypt("<flash><intMergeShopID>{$shopId}</intMergeShopID></flash>")."</ninja2>";
    \curl_setopt($ch, \CURLOPT_POSTFIELDS, $data);

    $headers = [
        "Content-Type: application/x-www-form-urlencoded",
        "Content-Length: ".\strlen($data),
    ];
    \curl_setopt($ch, \CURLOPT_HTTPHEADER, $headers);

    $result = \curl_exec($ch);
    \curl_close($ch);

    if(\curl_errno($ch)) {
        throw new \Exception('Curl error: ' . \curl_error($ch));
    }
    if(!$result) {
        throw new \Exception('Empty response');
    }

    $result = \mb_convert_encoding($result, 'ISO-8859-1', 'UTF-8');

    if(\strpos($result, '<mergeshop xmlns:sql="urn:schemas-microsoft-com:xml-sql"></mergeshop>') !== false) {
        throw new \Exception('Empty shop');
    }
    $validateXml = \simplexml_load_string($result);
    if($validateXml === false) {
        echo $result."??";die;
        throw new \Exception('Invalid XML');
    }
    $child0 = $validateXml->children()[0];
    if(!$child0) {
        echo $result."???";die;
        throw new \Exception('Invalid XML');
    }
    if($child0->getName() === "info") {
        /** @var \SimpleXMLElement $child0 */
        $reason = $child0->attributes()->reason;
        throw new \Exception("{$reason}");
    }

    return $result;
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