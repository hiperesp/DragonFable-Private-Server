<?php
namespace hiperesp\server\storage;

class SQLite extends Storage {

    private \PDO $pdo;

    private string $location;
    private string $prefix;

    public function __construct(array $options) {
        $this->location = $options["location"];
        $this->prefix = $options["prefix"];
        $this->pdo = new \PDO("sqlite:{$this->location}");
    }

    public function select(string $collection, array $where, ?int $limit = 1): array {
        $where['_isDeleted'] = 0;

        $data = $this->_select("{$this->prefix}{$collection}", $where, $limit);

        foreach($data as $key => $document) {
            unset($document['_isDeleted']);
            $data[$key] = $document;
        }
        return $data;
    }
    public function insert(string $collection, array $document): array {
        foreach(self::getCollectionStructure($collection) as $key => $definitions) {
            foreach($definitions as $definition) {
                if($definition === 'CREATED_DATETIME') {
                    $document[$key] = \date('c');
                    continue 2;
                }
                if($definition === 'UPDATED_DATETIME') {
                    $document[$key] = \date('c');
                    continue 2;
                }
            }
        }

        $this->_insert("{$this->prefix}{$collection}", $document);

        $where = [];

        foreach(self::getCollectionStructure($collection) as $key => $definitions) {
            foreach($definitions as $definition) {
                if($definition === 'PRIMARY_KEY') {
                    $where[$key] = $this->pdo->lastInsertId();
                    break;
                }
            }
        }

        $data = $this->select($collection, $where)[0];
        unset($data['_isDeleted']);

        return $data;
    }

    public function update(string $collection, array $document): bool {
        $where = [];
        $where['_isDeleted'] = 0;

        $newFields = [];
        foreach($document as $key => $value) {
            foreach(self::getCollectionStructure($collection)[$key] as $definition) {
                if($definition === 'PRIMARY_KEY') {
                    $where[$key] = $value;
                    break;
                }
                if($definition === 'UPDATED_DATETIME') {
                    $document[$key] = \date('c');
                    continue 2;
                }
            }
            $newFields[$key] = $value;
        }
        if(\count($where) === 0) {
            throw new \Exception("No primary key found in update document");
        }
        return $this->_update("{$this->prefix}{$collection}", $where, $newFields, 1);
    }
    public function delete(string $collection, array $document): bool {
        $realDelete = false; // real delete has problems: apparently, the id is recycled, so it's better to just mark as deleted

        $where = [];
        foreach($document as $key => $value) {
            foreach(self::getCollectionStructure($collection)[$key] as $definition) {
                if($definition === 'PRIMARY_KEY') {
                    $where[$key] = $value;
                    continue 2;
                }
            }
        }
        if(\count($where) === 0) {
            throw new \Exception("No primary key found in delete document");
        }
        if($realDelete) {
            return $this->_delete("{$this->prefix}{$collection}", $where, 1);
        }

        $updateFields = [ '_isDeleted' => 1 ];

        return $this->_update("{$this->prefix}{$collection}", $where, $updateFields, 1);
    }

    public function reset(): void {
        \array_map(function(string $table) {
            $this->_dropTable($table);
        }, self::getCollections());
        $this->setup();
    }

    protected function setup(): void {
        $mustHaveTables = \array_map(function(string $collection) {
            return $collection;
        }, self::getCollections());

        $tables = \array_map(function(array $table) {
            return \preg_replace('/^'.\preg_quote($this->prefix).'/', '', $table['name']);
        }, $this->_select('sqlite_master', [ 'type' => 'table', ], null));

        foreach($mustHaveTables as $table) {
            if(!\in_array($table, $tables)) {
                $createTableSuccess = $this->_createTable("{$table}");
                if(!$createTableSuccess) {
                    throw new \Exception("Setup error: Failed to create table {$table}");
                }

                $toInsert = self::getFullCollectionSetup()[$table]['data'];
                foreach($toInsert as $data) {
                    try {
                        $this->insert($table, $data);
                    } catch(\Exception $e) {
                        $this->_dropTable("{$table}");
                        throw new \Exception("Setup error: Failed to insert data into table {$table}");
                    }
                }
            }
        }
    }

    private function _createTable(string $table): bool {
        $afterCreateSql = "";
        $sql = "CREATE TABLE {$this->prefix}{$table} (";
        foreach(self::getCollectionStructure($table) as $field => $definitions) {
            $sql.= "`{$field}` ";
            $definitionStr = [ ];
            foreach($definitions as $def1 => $def2) {
                if(\is_numeric($def1)) {
                    $definition = $def2;
                    $params = null;
                } else {
                    $definition = $def1;
                    $params = $def2;
                }
                if($definition === 'INDEX') {
                    $afterCreateSql.= "CREATE INDEX {$this->prefix}{$table}_{$field} ON {$this->prefix}{$table} ({$field});";
                    continue;
                }
                $definitionStr[] = match($definition) {
                    'GENERATED' => '',
                    'CREATED_DATETIME' => '',
                    'UPDATED_DATETIME' => '',
                    'PRIMARY_KEY' => 'PRIMARY KEY',
                    'FOREIGN_KEY' => "", // let's ignore this for now
                    'DEFAULT' => "DEFAULT ".($params===null ? 'NULL' : "\"{$params}\" NOT NULL"),
                    'UNIQUE' => 'UNIQUE',

                    'INTEGER' => 'INTEGER',
                    'BIT' => 'INTEGER',
                    'DATE' => 'TEXT',
                    'DATE_TIME' => 'TEXT',
                    'STRING' => 'TEXT',
                    'CHAR' => 'TEXT',
                    default => throw new \Exception("Unknown definition: {$definition}"),
                };
            }
            $sql.= \implode(' ', $definitionStr).', ';
        }
        $sql.= '_isDeleted INTEGER DEFAULT 0';
        $sql.= ');';
        $sql.= $afterCreateSql;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute();
    }

    private function _dropTable(string $table): bool {
        $stmt = $this->pdo->prepare("DROP TABLE {$this->prefix}{$table};");
        return $stmt->execute();
    }

    private function _select(string $table, array $where, ?int $limit): array {
        $sqlParams = [];
        $sql = "SELECT * FROM {$table} WHERE true ";
        foreach($where as $key => $value) {
            if(\is_iterable($value)) {
                $sql .= "AND {$key} IN (";
                foreach($value as $v) {
                    $sql .= "?,";
                    $sqlParams[] = $v;
                }
                $sql = \substr($sql, 0, -1);
                $sql .= ") ";
                continue;
            }
            $sql .= "AND {$key} = ? ";
            $sqlParams[] = $value;
        }
        if($limit !== null) {
            $sql .= "LIMIT {$limit}";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($sqlParams);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function _insert(string $table, array $document): void {
        $fields = \array_keys($document);
        $sql = "INSERT INTO {$table} (".\implode(',', $fields).") VALUES (";
        $sqlParams = [];
        foreach($fields as $field) {
            $sql .= "?,";
            $sqlParams[] = $document[$field];
        }
        $sql = \substr($sql, 0, -1).');';
        $stmt = $this->pdo->prepare($sql);
        if(!$stmt->execute($sqlParams)) {
            throw new \Exception("Failed to insert into {$table}");
        }
    }

    private function _update(string $table, array $where, array $newFields, ?int $limit): bool {
        $sql = "UPDATE {$table} SET ";
        $sqlParams = [];
        foreach($newFields as $field => $value) {
            $sql .= "{$field} = ?,";
            $sqlParams[] = $value;
        }
        $sql = \substr($sql, 0, -1).' WHERE true ';
        foreach($where as $key => $value) {
            $sql .= "AND {$key} = ? ";
            $sqlParams[] = $value;
        }
        if($limit !== null) {
            $sql .= "LIMIT {$limit}";
        }
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($sqlParams);
    }

    private function _delete(string $table, array $where, ?int $limit): bool {
        $sql = "DELETE FROM {$table} WHERE true ";
        $sqlParams = [];
        foreach($where as $key => $value) {
            $sql .= "AND {$key} = ? ";
            $sqlParams[] = $value;
        }
        if($limit !== null) {
            $sql .= "LIMIT {$limit}";
        }
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($sqlParams);
    }

}