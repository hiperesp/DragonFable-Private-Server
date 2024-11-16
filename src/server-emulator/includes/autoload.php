<?php declare(strict_types=1);
\spl_autoload_register(function (string $className) use($base) {
    $classFile = $base . DIRECTORY_SEPARATOR . \str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
    if (!\file_exists($classFile)) {
        throw new \Exception("The file {$classFile} does not exist or could not be included. See {$className}");
    }

    require_once $classFile;

    if(\class_exists($className, false)) return;
    if(\interface_exists($className, false)) return;
    if(\trait_exists($className, false)) return;

    throw new \Exception("The class/interface/trait {$className} was not found in the file {$classFile}");
});