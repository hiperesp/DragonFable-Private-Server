<?php declare(strict_types=1);
if(\file_exists("{$base}/.env.php")) {
    throw new \Exception("The .env.php file is not allowed due to security reasons. Please use environment variables instead or .config.php file.");
}
if(\file_exists("{$base}/.config.php")) {
    require "{$base}/.config.php";
}

if(!isset($config['DB_DRIVER'])) {
    $config['DB_DRIVER'] = \getenv("DB_DRIVER");
}
if(!isset($config['DB_OPTIONS'])) {
    $config['DB_OPTIONS'] = \getenv("DB_OPTIONS");
}
if(!isset($config['DF_SETTINGS_ID'])) {
    $config['DF_SETTINGS_ID'] = \getenv("DF_SETTINGS_ID");
}
if(!isset($config['DF_NINJA2_KEY'])) {
    $config['DF_NINJA2_KEY'] = \getenv("DF_NINJA2_KEY");
}
if(!isset($config['GIT_REV'])) {
    $config['GIT_REV'] = \getenv("GIT_REV");
}

if(!$config["DB_DRIVER"]) {
    throw new \Exception("The environment variable DB_DRIVER is not defined.");
}
if(!$config["DB_OPTIONS"]) {
    throw new \Exception("The environment variable DB_OPTIONS is not defined.");
}
if(!$config["DF_SETTINGS_ID"]) {
    $config["DF_SETTINGS_ID"] = 1;
}
if(!$config["DF_NINJA2_KEY"]) {
    $config["DF_NINJA2_KEY"] = "ZorbakOwnsYou";
}
