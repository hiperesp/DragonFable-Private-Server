<?php declare(strict_types=1);
namespace hiperesp\server\storage;

abstract class Storage {

    protected readonly string $prefix;

    public function __construct(string $prefix) {
        $this->prefix = $prefix;
    }

    protected abstract function _select(string $prefix, string $collection, array $where, ?int $limit, int $skip): array;
    protected abstract function _insert(string $prefix, string $collection, array $document): void;
    protected abstract function _update(string $prefix, string $collection, array $where, array $document, ?int $limit): bool;

    protected abstract function _lastInsertId(): int;
    protected abstract function existsCollection(string $prefix, string $collection): bool;
    protected abstract function createCollection(string $prefix, string $collection): bool;
    protected abstract function dropCollection(string $prefix, string $collection): bool;
    protected abstract function renameCollection(string $oldPrefix, string $oldCollectionName, string $newPrefix, string $newCollectionName): bool;

    protected string $dateFormat = "Y-m-d";
    protected string $dateTimeFormat = "Y-m-d H:i:s";

    final public function select(string $collection, array $where, ?int $limit = 1, int $skip = 0): array {
        return $this->selectPrefix($this->prefix, $collection, $where, $limit, $skip);
    }
    final public function insert(string $collection, array $document): array {
        return $this->insertPrefix($this->prefix, $collection, $document);
    }
    final public function update(string $collection, array $document): bool {
        return $this->updatePrefix($this->prefix, $collection, $document);
    }
    final public function delete(string $collection, array $document): bool {
        return $this->deletePrefix($this->prefix, $collection, $document);
    }

    private function _createCollection(string $prefix, string $collection): bool {
        if(!$this->createCollection($prefix, $collection)) {
            return false;
        }
        if(!$this->existsCollection($prefix, $collection)) {
            // throw new \Exception("Failed to create collection {$collection}: Collection not found after creation");
            return false;
        }
        return true;
    }

    private function selectPrefix(string $prefix, string $collection, array $where, ?int $limit = 1, int $skip = 0): array {
        $where['_isDeleted'] = 0;

        $data = $this->_select($prefix, $collection, $where, $limit, $skip);

        foreach($data as $key => $document) {
            unset($document['_isDeleted']);
            $data[$key] = $document;
        }
        return $data;
    }

    private function insertPrefix(string $prefix, string $collection, array $document): array {
        foreach(Setup::getStructure($collection) as $key => $definitions) {
            if(\in_array('CREATED_DATETIME', $definitions)) {
                $document[$key] = \date('c');
            }
            if(\in_array('UPDATED_DATETIME', $definitions)) {
                $document[$key] = \date('c');
            }
            if(\array_key_exists($key, $document)) {
                if(\in_array('DATETIME', $definitions)) {
                    if($document[$key] !== null) {
                        $document[$key] = \date($this->dateTimeFormat, \strtotime($document[$key]));
                    }
                }
                if(\in_array('DATE', $definitions)) {
                    if($document[$key] !== null) {
                        $document[$key] = \date($this->dateFormat, \strtotime($document[$key]));
                    }
                }
            }
        }

        $this->_insert($prefix, $collection, $document);

        $where = [];
        foreach(Setup::getStructure($collection) as $key => $definitions) {
            if(\in_array('PRIMARY_KEY', $definitions)) {
                if(\array_key_exists($key, $document)) {
                    $where[$key] = $document[$key];
                    break;
                }
                $where[$key] = $this->_lastInsertId();
                break;
            }
        }

        $data = $this->selectPrefix($prefix, $collection, $where)[0];
        unset($data['_isDeleted']);

        return $data;
    }

    private function updatePrefix(string $prefix, string $collection, array $document): bool {
        $where = [];

        $newFields = [];
        foreach(Setup::getStructure($collection) as $key => $definitions) {
            if(\in_array('UPDATED_DATETIME', $definitions)) {
                $document[$key] = \date('c');
            }
            if(\array_key_exists($key, $document)) {
                if(\in_array('PRIMARY_KEY', $definitions)) {
                    $where[$key] = $document[$key];
                    continue;
                }
                if(\in_array('DATETIME', $definitions)) {
                    if($document[$key] !== null) {
                        $document[$key] = \date($this->dateTimeFormat, \strtotime($document[$key]));
                    }
                }
                if(\in_array('DATE', $definitions)) {
                    if($document[$key] !== null) {
                        $document[$key] = \date($this->dateFormat, \strtotime($document[$key]));
                    }
                }
                $newFields[$key] = $document[$key];
            }
        }
        if(\count($where) === 0) {
            throw new \Exception("No primary key found in update document");
        }
        $where['_isDeleted'] = 0;
        return $this->_update($prefix, $collection, $where, $newFields, 1);
    }

    private function deletePrefix(string $prefix, string $collection, array $document): bool {
        $where = [];
        $updateFields = [];
        foreach(Setup::getStructure($collection) as $key => $definitions) {
            if(\in_array('PRIMARY_KEY', $definitions)) {
                $where[$key] = $document[$key];
                continue;
            }
            if(\in_array('UPDATED_DATETIME', $definitions)) {
                $updateFields[$key] = \date('c');
                if(\in_array('DATETIME', $definitions)) {
                    $updateFields[$key] = \date($this->dateTimeFormat, \strtotime($updateFields[$key]));
                } else if(\in_array('DATE', $definitions)) {
                    $updateFields[$key] = \date($this->dateFormat, \strtotime($updateFields[$key]));
                }
            }
        }
        if(\count($where) === 0) {
            throw new \Exception("No primary key found in delete document");
        }
        $where['_isDeleted'] = 0;
        $updateFields['_isDeleted'] = $document[\array_keys($where)[0]];

        return $this->_update($prefix, $collection, $where, $updateFields, 1);
    }

    private static Storage $instance;
    final public static function getStorage(): Storage {
        $driver = \getenv("DB_DRIVER");
        $options = \json_decode(\getenv("DB_OPTIONS"), true);

        if(!isset(self::$instance)) {
            self::$instance = new $driver($options);
        }
        return self::$instance;
    }

    final public function setup(): void {
        global $base;

        if(\file_exists("{$base}/setup.lock")) {
            throw new \Exception("Setup error: Setup already done (or in progress). If you want to run setup again, remove the 'setup.lock' file");
        }
        \file_put_contents("{$base}/setup.lock", 'UPGRADING');

        $key = \date('YmdHis');

        $migrationPrefix  = "{$this->prefix}migration{$key}_";
        $backupOldPrefix  = "{$this->prefix}backup{$key}_";
        $productionPrefix = "{$this->prefix}";

        // Step 1: Create new collections and insert new data and migrate old data simultaneously
        foreach(Setup::getCollections() as $collection) {
            if($this->existsCollection($migrationPrefix, $collection)) {
                continue;
            }
            if(!$this->_createCollection($migrationPrefix, $collection)) {
                throw new \Exception("Setup error: Failed to create collection {$collection}");
            }
            $collectionStructure = Setup::getStructure($collection);
            if($this->existsCollection($productionPrefix, $collection) && Setup::canMigrateOldDataFromCollection($collection)) {
                $dataToInsert = $this->select($collection, [], null);
                foreach($dataToInsert as $key => $data) {
                    $newData = [];
                    foreach($collectionStructure as $field => $definitions) {
                        if(\array_key_exists($field, $data)) {
                            $newData[$field] = $data[$field];
                        } else if(\array_key_exists("DEFAULT", $definitions)) {
                            continue;
                        } else {
                            throw new \Exception("Setup error: Failed to migrate data from collection {$collection}: Field {$field} not found in data");
                        }
                    }
                    $dataToInsert[$key] = $newData;
                }
                if(Setup::canReplaceFieldsWithNewData($collection)) {
                    $fieldsToReplace = Setup::getReplaceFieldsWithNewData($collection);
                    $dataToReplace = Setup::getData($collection);
                    foreach($dataToInsert as $key => $data) {
                        foreach($dataToReplace as $replaceData) {
                            if($replaceData['id'] === $data['id']) {
                                foreach($fieldsToReplace as $replaceField) {
                                    $dataToInsert[$key][$replaceField] = $replaceData[$replaceField];
                                }
                                break;
                            }
                        }
                    }
                }
            } else {
                $dataToInsert = Setup::getData($collection);
            }
            foreach($dataToInsert as $data) {
                try {
                    $this->insertPrefix($migrationPrefix, $collection, $data);
                } catch(\Exception $e) {
                    throw new \Exception("Setup error: Failed to insert data into collection {$collection}: {$e->getMessage()}.\nData: ".\json_encode($data));
                }
            }
        }

        // Step 2: Create backup of old collections + move new collections to production
        foreach(Setup::getCollections() as $collection) {
            if($this->existsCollection($productionPrefix, $collection)) {
                $this->renameCollection($productionPrefix, $collection, $backupOldPrefix, $collection);
            }
            $this->renameCollection($migrationPrefix, $collection, $productionPrefix, $collection);
        }

        \file_put_contents("{$base}/setup.lock", 'DONE');
    }
}