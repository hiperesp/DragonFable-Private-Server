<?php
// $sessionToken = "";
// $charId = 0;

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

foreach([
    "quest",
    "town",
    "shop",
    "interface",
    "houseShop",
    "houseItemShop",
    "mergeShop",
    "classes"
] as $choice) {
    echo "Extracting {$choice} data...\n";
    echo `php extract-production-data.php {$choice} {$sessionToken} {$charId}`;
    echo "Done.\n";
}