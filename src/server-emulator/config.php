<?php
$serverMode = 'server'; // 'proxy' or 'server'

$storage = [
    "driver" => \hiperesp\server\storage\SQLite::class,
    "options" => [
        "location" => "{$base}/data/db.sqlite3",
        "prefix" => "df_",
    ],
];
