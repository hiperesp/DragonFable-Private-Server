<?php declare(strict_types=1);
namespace hiperesp\server\storage;

final class Setup {

    private static array $data = [];

    public static function getCollections(): array {
        return \array_keys(Definition::$definition);
    }

    public static function getStructure(string $collection): array {
        return Definition::$definition[$collection]["structure"];
    }

    public static function getData(string $collection): array {
        global $base;

        if(!isset(self::$data[$collection])) {
            $data = Definition::$definition[$collection]["data"];

            if(\is_string($data)) {
                $fileOrDirectory = "{$base}/hiperesp/data/{$data}";
                $filesToParse = [];

                if(\is_file($fileOrDirectory)) {
                    $filesToParse[] = $fileOrDirectory;
                } else if(\is_dir($fileOrDirectory)) {
                    $files = \scandir($fileOrDirectory);
                    foreach($files as $file) {
                        if($file == "." || $file == "..") continue;
                        $filesToParse[] = "{$fileOrDirectory}{$file}";
                    }
                } else {
                    throw new \Exception("Invalid data source for collection '{$collection}'");
                }

                $data = [];
                foreach($filesToParse as $file) {
                    $data = \array_merge($data, \json_decode(\file_get_contents($file), true));
                }
            }

            self::$data[$collection] = $data;
        }
        return self::$data[$collection];
    }

    public static function canMigrateOldDataFromCollection(string $collection): bool {
        return Definition::$definition[$collection]["migrateOldData"];
    }

    public static function canReplaceFieldsWithNewData(string $collection): bool {
        return !!self::getReplaceFieldsWithNewData($collection);
    }

    public static function getReplaceFieldsWithNewData(string $collection): ?array {
        if(!isset(Definition::$definition[$collection]["replaceFieldsWithNewData"])) {
            return null;
        }
        return Definition::$definition[$collection]["replaceFieldsWithNewData"];
    }

}
