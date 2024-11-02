<?php declare(strict_types=1);
\spl_autoload_register(function ($className) use($base) {
    $classFile = \preg_replace('/\\\\/', DIRECTORY_SEPARATOR, "{$base}/{$className}").".php";
    if(!\file_exists($classFile)) {
        throw new \Exception("The file {$classFile} does not exists. See {$className}");
    }

    include_once $classFile;

    $validations = [
        'class'     => \class_exists($className, false),
        'interface' => \interface_exists($className, false),
        'trait'     => \trait_exists($className, false)
    ];
    if(!\in_array(true, $validations)) {
        throw new \Exception("The class/interface/trait {$className} was not found in the file {$classFile}");
    }
});

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