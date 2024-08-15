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
        return $this->_select("{$this->prefix}{$collection}", $where, $limit);
    }
    public function insert(string $collection, array $document): array {
        foreach($document as $key => $value) {
            if(self::$collectionSetup[$collection]['structure'][$key] === 'GENERATED') {
                unset($document[$key]);
            }
        }
        return $this->_insert("{$this->prefix}{$collection}", $document);
    }
    public function update(string $collection, array $where, array $newFields, ?int $limit = 1): bool {
        return $this->_update("{$this->prefix}{$collection}", $where, $newFields, $limit);
    }
    public function delete(string $collection, array $where, ?int $limit = 1): bool {
        return $this->_delete("{$this->prefix}{$collection}", $where, $limit);
    }

    public function reset(): void {
        \array_map(function(string $table) {
            $this->_dropTable($table);
        }, \array_keys(self::$collectionSetup));
    }

    protected function setup(): void {
        $mustHaveTables = \array_map(function(string $collection) {
            return $collection;
        }, \array_keys(self::$collectionSetup));

        $tables = \array_map(function(array $table) {
            return \preg_replace('/^'.\preg_quote($this->prefix).'/', '', $table['name']);
        }, $this->_select('sqlite_master', [ 'type' => 'table', ], null));

        foreach($mustHaveTables as $table) {
            if(!\in_array($table, $tables)) {
                $createTableSuccess = $this->_createTable("{$table}");
                if(!$createTableSuccess) {
                    throw new \Exception("Setup error: Failed to create table {$table}");
                }

                $toInsert = self::$collectionSetup[$table]['data'];
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
        $tableSetup = self::$collectionSetup[$table];
        $sql = "CREATE TABLE {$this->prefix}{$table} (";
        foreach($tableSetup['structure'] as $field => $definitions) {
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
                $definitionStr[] = match($definition) {
                    'UUID' => 'INTEGER',
                    'GENERATED' => '', // by default, SQLite will autoincrement
                    'PRIMARY_KEY' => 'PRIMARY KEY',
                    'FOREIGN_KEY' => "", // let's ignore this for now
                    'DEFAULT' => "DEFAULT ".($params===null ? 'NULL' : "\"{$params}\""),
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
        $sql = \substr($sql, 0, -2).');';
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

    private function _insert(string $table, array $document): array {
        $fields = \array_keys($document);
        $sql = "INSERT INTO {$table} (".\implode(',', $fields).") VALUES (";
        $sqlParams = [];
        foreach($fields as $field) {
            $sql .= "?,";
            $sqlParams[] = $document[$field];
        }
        $sql = \substr($sql, 0, -1).');';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($sqlParams);
        $document['id'] = $this->pdo->lastInsertId();
        return $this->_select($table, $document, 1);
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