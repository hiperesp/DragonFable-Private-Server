<?php
\spl_autoload_register(function ($className) use($base) {
    $classFile = \preg_replace('/\\\\/', DIRECTORY_SEPARATOR, "{$base}/{$className}").".php";
    if(!\file_exists($classFile)) {
        throw new \Exception("The file {$classFile} does not exists. See {$className}");
    }
    include $classFile;
});