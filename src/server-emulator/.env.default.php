<?php

\putenv("DB_DRIVER=".\hiperesp\server\storage\MySQL::class);
\putenv("DB_OPTIONS=".\json_encode([
    "host" => "host2.gabstep.com.br",
    "port" => 3306,
    "username" => "dragonfable",
    "password" => "password",
    "database" => "dragonfable",
    "prefix" => "df_",
]));

\putenv("DB_DRIVER=".\hiperesp\server\storage\SQLite::class);
\putenv("DB_OPTIONS=".\json_encode([
    "location" => "/var/www/html/server-emulator/data/db.sqlite3",
    "prefix" => "df_",
]));

\putenv("DF_SETTINGS_ID=1");            # Optional, default 1
\putenv("DF_NINJA2_KEY=ZorbakOwnsYou"); # Optional, default "ZorbakOwnsYou"