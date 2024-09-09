<?php
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

    protected function getFieldDefinition(string $field, array $definitions, string $collection, array &$afterCreateSql): string {
        $sql = "`{$field}` ";
        $definitionStr = [ ];
        foreach($definitions as $definition => $params) {
            if($definition === 'INDEX') {
                $afterCreateSql[] = "CREATE INDEX {$this->prefix}{$collection}_{$field} ON {$this->prefix}{$collection} ({$field});";
                continue;
            }
            if($definition === 'UNIQUE') {
                $afterCreateSql[] = "CREATE UNIQUE INDEX {$this->prefix}{$collection}_{$field} ON {$this->prefix}{$collection} ({$field}, `_isDeleted`);";
                continue;
            }
            if($definition === 'FOREIGN_KEY') {
                $afterCreateSql[] = "ALTER TABLE {$this->prefix}{$collection} ADD FOREIGN KEY (`{$field}`) REFERENCES {$this->prefix}{$params['collection']} (`{$params['field']}`);";
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
        $sql.= \implode(" ", $definitionStr);
        return $sql;
    }

}