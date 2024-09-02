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

$storage = [
    "driver" => \getenv("DB_DRIVER"),
    "options" => \json_decode(\getenv("DB_OPTIONS"), true),
];
