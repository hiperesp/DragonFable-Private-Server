<?php
$config = [];

$config["DB_DRIVER"] = \hiperesp\server\storage\MySQL::class;
$config["DB_OPTIONS"] = \json_encode([
    "host" => "host2.gabstep.com.br",
    "port" => 3306,
    "username" => "dragonfable",
    "password" => "password",
    "database" => "dragonfable",
    "prefix" => "df_",
]);

$config["DB_DRIVER"] = \hiperesp\server\storage\SQLite::class;
$config["DB_OPTIONS"] = \json_encode([
    "location" => "/var/www/html/server-emulator/data/db.sqlite3",
    "prefix" => "df_",
]);

$config["DF_SETTINGS_ID"] = 1;               # Optional, default 1
$config["DF_NINJA2_KEY"]  = "ZorbakOwnsYou"; # Optional, default "ZorbakOwnsYou"
