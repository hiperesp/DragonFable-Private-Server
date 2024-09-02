<?php
if(\file_exists("{$base}/.env.php")) {
    require "{$base}/.env.php";
}

if(!\getenv("DB_DRIVER")) {
    throw new \Exception("The environment variable DB_DRIVER is not defined.");
}
if(!\getenv("DB_OPTIONS")) {
    throw new \Exception("The environment variable DB_OPTIONS is not defined.");
}
if(!\getenv("DF_SETTINGS_ID")) {
    \putenv("DF_SETTINGS_ID=1");
}
if(!\getenv("DF_NINJA2_KEY")) {
    \putenv("DF_NINJA2_KEY=ZorbakOwnsYou");
}