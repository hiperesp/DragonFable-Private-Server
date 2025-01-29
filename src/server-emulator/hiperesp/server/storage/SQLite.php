<?php declare(strict_types=1);
namespace hiperesp\server\storage;

class SQLite extends SQL {

    protected bool $useForeignKeys = false;

    public function __construct(array $options) {
        if(!isset($options["location"])) {
            throw new \Exception("Missing Storage location");
        }
        if(!\is_dir(\dirname($options["location"]))) {
            \mkdir(\dirname($options["location"]), 0777, true);
        }

        $options['driver'] = 'sqlite';
        $options['dsn'] = "sqlite:{$options['location']}";

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
                // we don't support foreign keys in SQLite due to complexity to manage them
                continue;
            }
            if($definition === 'PRIMARY_KEY') {
                $definitionStr['IS_PRIMARY_KEY'] = 'PRIMARY KEY';
                continue;
            }
            if($definition === 'GENERATED') {
                // by default, SQLite use auto increment for primary keys
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
        $sql.= \implode(" ", $definitionStr);
        return $sql;
    }

    #[\Override]
    protected function getRenameTableDefinition(string $oldName, string $newName): string {
        return "ALTER TABLE {$oldName} RENAME TO {$newName}";
    }

}