<?php declare(strict_types=1);
namespace hiperesp\server\storage;

abstract class SQL extends Storage {

    protected readonly \PDO $pdo;
    protected bool $useForeignKeys = true;

    abstract protected function getFieldDefinition(string $field, array $definitions, string $prefix, string $collection, array &$afterCreateSql): string;
    abstract protected function getRenameTableDefinition(string $oldName, string $newName): string;

    protected function __construct(array $options) {
        if(!isset($options["driver"])) {
            throw new \Exception("Missing driver");
        }
        $driver = $options["driver"];
        if(!\in_array($driver, \PDO::getAvailableDrivers())) {
            throw new \Exception("{$driver} driver not found");
        }

        if(!isset($options["dsn"])) {
            throw new \Exception("Missing DSN");
        }
        $dsn = $options["dsn"];

        if(!isset($options["prefix"])) {
            throw new \Exception("Missing prefix");
        }
        $prefix = $options["prefix"];

        $username = isset($options['username']) ? $options['username'] : null;
        $password = isset($options['password']) ? $options['password'] : null;

        $pdoOptions = isset($options['pdoOptions']) ? $options['pdoOptions'] : [];

        $this->pdo = new \PDO($dsn, $username, $password, $pdoOptions);

        parent::__construct($prefix);
    }

    #[\Override]
    final protected function _lastInsertId(): int {
        return (int)$this->pdo->lastInsertId();
    }

    #[\Override]
    final protected function _select(string $prefix, string $collection, array $where, ?int $limit, int $skip): array {
        $sqlParams = [];
        $sql = "SELECT * FROM {$prefix}{$collection} WHERE true ";
        foreach($where as $key => $value) {
            if (is_array($value)) {
                // Handle BETWEEN
                if (isset($value['BETWEEN']) && is_array($value['BETWEEN']) && count($value['BETWEEN']) === 2) {
                    $sql .= "AND `{$key}` BETWEEN ? AND ? ";
                    $sqlParams[] = $value['BETWEEN'][0];
                    $sqlParams[] = $value['BETWEEN'][1];
                    continue;
                }

                // Handle other comparisons like ['>' => 10], ['<' => 20], etc.
                foreach (['>', '<', '>=', '<=', '!='] as $op) {
                    if (isset($value[$op])) {
                        $sql .= "AND `{$key}` {$op} ? ";
                        $sqlParams[] = $value[$op];
                        continue 2; // Skip to next $where key
                    }
                }

                // Fallback to IN
                if ($value) {
                    $sql .= "AND `{$key}` IN (";
                    foreach ($value as $v) {
                        $sql .= "?,";
                        $sqlParams[] = $v;
                    }
                    $sql = substr($sql, 0, -1) . ") ";
                } else {
                    return [];
                }
            } else {
                $sql .= "AND `{$key}` = ? ";
                $sqlParams[] = $value;
            }
        }
        if($limit !== null) {
            $sql .= "LIMIT {$limit} ";
            if($skip > 0) {
                $sql .= "OFFSET {$skip} ";
            }
        }
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($sqlParams);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch(\Exception $e) {
            return [];
        }
    }

    #[\Override]
    final protected function _insert(string $prefix, string $collection, array $document): void {
        $fields = \array_keys($document);
        $sql = "INSERT INTO {$prefix}{$collection} (`".\implode('`,`', $fields)."`) VALUES (";
        $sqlParams = [];
        foreach($fields as $field) {
            $sql .= "?,";
            $sqlParams[] = $document[$field];
        }
        $sql = \substr($sql, 0, -1).');';
        $stmt = $this->pdo->prepare($sql);
        if(!$stmt->execute($sqlParams)) {
            throw new \Exception("Failed to insert into {$collection}");
        }
    }

    #[\Override]
    final protected function _update(string $prefix, string $collection, array $where, array $newFields): bool {
        $sql = "UPDATE {$prefix}{$collection} SET ";
        $sqlParams = [];
        foreach($newFields as $field => $value) {
            $sql .= "`{$field}` = ?,";
            $sqlParams[] = $value;
        }
        $sql = \substr($sql, 0, -1).' WHERE true ';
        foreach($where as $key => $value) {
            $sql .= "AND {$key} = ? ";
            $sqlParams[] = $value;
        }
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($sqlParams);
    }

    #[\Override]
    protected function existsCollection(string $prefix, string $collection): bool {
        try {
            $stmt = $this->pdo->prepare("SELECT 1 FROM {$prefix}{$collection} LIMIT 1");
            return $stmt->execute();
        } catch(\Exception $e) {
            return false;
        }
    }

    private function getCreateCollectionSQL(string $prefix, string $collection): array {
        $afterCreateSql = [];

        $tableFieldsDefinitions = [];
        foreach(Setup::getStructure($collection) as $field => $definitions) {
            $parsedDefinitions = [];
            foreach($definitions as $key => $value) {
                if(\is_numeric($key)) {
                    $definition = $value;
                    $params = NULL;
                } else {
                    $definition = $key;
                    $params = $value;
                }
                $parsedDefinitions[$definition] = $params;
            }
            $tableFieldsDefinitions[] = $this->getFieldDefinition(
                field: $field,
                definitions: $parsedDefinitions,
                prefix: $prefix,
                collection: $collection,
                afterCreateSql: $afterCreateSql
            );
        }
        $tableFieldsDefinitions[] = $this->getFieldDefinition(
            field: '_isDeleted',
            definitions: [ 'INTEGER' => NULL, 'INDEX' => NULL, 'DEFAULT' => '0'],
            prefix: $prefix,
            collection: $collection,
            afterCreateSql: $afterCreateSql
        );
        $createSql = "CREATE TABLE {$prefix}{$collection} (".\implode(",\n", $tableFieldsDefinitions).");\n";
        $afterCreateSql = \implode("\n", $afterCreateSql);

        return [
            'create' => $createSql,
            'afterCreate' => $afterCreateSql,
        ];
    }

    #[\Override]
    protected function createCollection(string $prefix, string $collection): bool {
        $sqlInfo = $this->getCreateCollectionSQL($prefix, $collection);
        $sql = $sqlInfo['create'];
        $sql.= $sqlInfo['afterCreate'];

        if($this->useForeignKeys) {
            $sql = "SET FOREIGN_KEY_CHECKS=0;{$sql};SET FOREIGN_KEY_CHECKS=1;";
        }

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute();
    }

    #[\Override]
    protected function dropCollection(string $prefix, string $collection): bool {
        $stmt = $this->pdo->prepare("DROP TABLE {$prefix}{$collection}");
        return $stmt->execute();
    }

    #[\Override]
    protected function renameCollection(string $oldPrefix, string $oldCollectionName, string $newPrefix, string $newCollectionName): bool {
        $afterCreateSql = $this->getCreateCollectionSQL($newPrefix, $newCollectionName)['afterCreate'];
        $stmt = $this->pdo->prepare($this->getRenameTableDefinition("{$oldPrefix}{$oldCollectionName}", "{$newPrefix}{$newCollectionName}").";{$afterCreateSql}");
        return $stmt->execute();
    }

}