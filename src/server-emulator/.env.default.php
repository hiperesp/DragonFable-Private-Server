<?php

\putenv("DB_DRIVER=".\hiperesp\server\storage\SQLite::class);
\putenv("DB_OPTIONS=".\json_encode([
    "location" => "/data/db.sqlite3",
    "prefix" => "df_",
]));

\putenv("DB_DRIVER=".\hiperesp\server\storage\MySQL::class);
\putenv("DB_OPTIONS=".\json_encode([
    "host" => "host2.gabstep.com.br",
    "port" => 3306,
    "username" => "dragonfable",
    "password" => "password",
    "database" => "dragonfable",
    "prefix" => "df_",
]));