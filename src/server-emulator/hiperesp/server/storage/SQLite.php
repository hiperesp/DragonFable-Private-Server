<?php
namespace hiperesp\server\storage;

class SQLite extends Storage {

    private \PDO $pdo;

    public function __construct(array $options) {
        $this->pdo = new \PDO("sqlite:{$options["location"]}");
    }

    public function insert(string $collection, array $document): array {

    }
    public function update(string $collection, array $where, array $newFields, ?int $limit = 1): bool {

    }
    public function delete(string $collection, array $where, ?int $limit = 1): bool {

    }
    public function select(string $collection, array $document, ?int $limit = 1): array {

    }

    public function setup(): void {
        // $isReady = $this->pdo->exec('SELECT name FROM sqlite_master WHERE type="table"')
        // if($isReady) {
        //     return;
        // }
        // create tables and insert default data
    }
}