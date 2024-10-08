<?php declare(strict_types=1);
namespace hiperesp\server\storage;

abstract class SQL extends Storage {

    private readonly \PDO $pdo;
    protected readonly string $prefix;

    abstract protected function getFieldDefinition(string $field, array $definitions, string $collection, array &$afterCreateSql): string;

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
        $this->prefix = $options["prefix"];

        $username = isset($options['username']) ? $options['username'] : null;
        $password = isset($options['password']) ? $options['password'] : null;

        $pdoOptions = isset($options['pdoOptions']) ? $options['pdoOptions'] : [];

        $this->pdo = new \PDO($dsn, $username, $password, $pdoOptions);
    }

    #[\Override]
    final protected function _lastInsertId(): int {
        return (int)$this->pdo->lastInsertId();
    }

    #[\Override]
    final protected function _select(string $collection, array $where, ?int $limit, int $skip): array {
        $sqlParams = [];
        $sql = "SELECT * FROM {$this->prefix}{$collection} WHERE true ";
        foreach($where as $key => $value) {
            if(\is_iterable($value)) {
                if(!$value) {
                    return []; // field must be equals ony of the values, but there are no values, so no results, no need to query
                }
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
            $sql .= "LIMIT {$limit} ";
            if($skip > 0) {
                $sql .= "OFFSET {$skip} ";
            }
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($sqlParams);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    #[\Override]
    final protected function _insert(string $collection, array $document): void {
        $fields = \array_keys($document);
        $sql = "INSERT INTO {$this->prefix}{$collection} (".\implode(',', $fields).") VALUES (";
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
    final protected function _update(string $collection, array $where, array $newFields, ?int $limit): bool {
        $sql = "UPDATE {$this->prefix}{$collection} SET ";
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

    #[\Override]
    protected function createCollection(string $collection): bool {
        $afterCreateSql = [];
        $sql = "CREATE TABLE {$this->prefix}{$collection} (";

        $tableFieldsDefinitions = [];
        foreach(CollectionSetup::getStructure($collection) as $field => $definitions) {
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
            $tableFieldsDefinitions[] = $this->getFieldDefinition($field, $parsedDefinitions, $collection, $afterCreateSql);
        }
        $tableFieldsDefinitions[] = $this->getFieldDefinition('_isDeleted', [ 'INTEGER' => NULL, 'INDEX' => NULL, 'DEFAULT' => '0'], $collection, $afterCreateSql);
        $sql.= \implode(",\n", $tableFieldsDefinitions);
        $sql.= ");\n";
        $sql.= \implode("\n", $afterCreateSql);
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute();
    }

    #[\Override]
    protected function dropCollection(string $collection): bool {
        $stmt = $this->pdo->prepare("DROP TABLE {$this->prefix}{$collection}");
        return $stmt->execute();
    }

}