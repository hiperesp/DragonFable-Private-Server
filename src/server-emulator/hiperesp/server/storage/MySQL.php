<?php declare(strict_types=1);
namespace hiperesp\server\storage;

class MySQL extends SQL {

    public function __construct(array $options) {
        if(!isset($options["database"])) {
            throw new \Exception("Missing Storage database");
        }
        if(!isset($options["host"])) {
            throw new \Exception("Missing Storage host");
        }
        if(!isset($options["username"])) {
            throw new \Exception("Missing Storage username");
        }
        if(!isset($options["password"])) {
            throw new \Exception("Missing Storage password");
        }

        $options['driver'] = 'mysql';
        $options['dsn'] = "mysql:host={$options['host']};dbname={$options['database']}";

        parent::__construct($options);
    }

    #[\Override]
    protected function getFieldDefinition(string $field, array $definitions, string $prefix, string $collection, array &$afterCreateSql): string {
        $sql = "`{$field}` ";
        $definitionStr = [ ];
        foreach($definitions as $definition => $params) {
            if($definition === 'INDEX') {
                $afterCreateSql[] = "DROP INDEX IF EXISTS {$prefix}{$collection}_{$field};";
                $afterCreateSql[] = "CREATE INDEX {$prefix}{$collection}_{$field} ON {$prefix}{$collection} ({$field});";
                continue;
            }
            if($definition === 'UNIQUE') {
                $afterCreateSql[] = "DROP INDEX IF EXISTS {$prefix}{$collection}_{$field};";
                $afterCreateSql[] = "CREATE UNIQUE INDEX {$prefix}{$collection}_{$field} ON {$prefix}{$collection} ({$field}, `_isDeleted`);";
                continue;
            }
            if($definition === 'FOREIGN_KEY') {
                $afterCreateSql[] = "ALTER TABLE {$prefix}{$collection} ADD FOREIGN KEY (`{$field}`) REFERENCES {$prefix}{$params['collection']} (`{$params['field']}`);";
                continue;
            }
            if($definition === 'PRIMARY_KEY') {
                $definitionStr['IS_PRIMARY_KEY'] = 'PRIMARY KEY';
                continue;
            }
            if($definition === 'GENERATED') {
                $definitionStr['IS_GENERATED'] = 'AUTO_INCREMENT';
                continue;
            }
            if($definition === 'DEFAULT') {
                $value = $params===null ? 'NULL' : "\"{$params}\" NOT NULL";
                $definitionStr['DEFAULT'] = "DEFAULT {$value}";
                continue;
            }
            if(\in_array($definition, ['CREATED_DATETIME', 'UPDATED_DATETIME', 'DATETIME'])) {
                $definitionStr['FIELD_TYPE'] = 'DATETIME';
                continue;
            }
            if(\in_array($definition, ['DATE'])) {
                $definitionStr['FIELD_TYPE'] = 'DATE';
                continue;
            }
            if(\in_array($definition, ['INTEGER', 'FLOAT'])) {
                $definitionStr['FIELD_TYPE'] = $definition;
                continue;
            }
            if($definition === 'BIT') {
                $definitionStr['FIELD_TYPE'] = 'TINYINT(1)';
                continue;
            }
            if($definition === 'CHAR') {
                if($params === null) {
                    throw new \Exception("CHAR definition requires a length");
                }
                if($params > 255) {
                    $definitionStr['FIELD_TYPE'] = "VARCHAR({$params})";
                } else {
                    $definitionStr['FIELD_TYPE'] = "CHAR({$params})";
                }
                continue;
            }
            if($definition === 'STRING') {
                if($params === null) {
                    $definitionStr['FIELD_TYPE'] = "TEXT";
                } else {
                    $definitionStr['FIELD_TYPE'] = "VARCHAR({$params})";
                }
                continue;
            }

            throw new \Exception("Unknown definition: {$definition}(".\json_encode($params).")");
        }
        if(isset($definitionStr['FIELD_TYPE']) && \in_array($definitionStr['FIELD_TYPE'], ['BLOB', 'TEXT', 'GEOMETRY', 'JSON'])) {
            unset($definitionStr['DEFAULT']);
        }
        $sql.= \implode(" ", $definitionStr);
        return $sql;
    }

    #[\Override]
    protected function getRenameTableDefinition(string $oldName, string $newName): string {
        return "RENAME TABLE {$oldName} TO {$newName}";
    }

}