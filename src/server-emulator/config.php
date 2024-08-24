<?php
$storage = [
    "driver" => \hiperesp\server\storage\SQLite::class,
    "options" => [
        "location" => "{$base}/data/db.sqlite3",
        "prefix" => "df_",
    ],
];

$storage = [
    "driver" => \hiperesp\server\storage\MySQL::class,
    "options" => [
        "host" => "host2.gabstep.com.br",
        "port" => 3306,
        "username" => "dragonfable",
        "password" => "bfj6y6zkWRnGrFyn",
        "database" => "dragonfable",
        "prefix" => "df_",
    ],
];