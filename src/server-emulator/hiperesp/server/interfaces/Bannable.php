<?php declare(strict_types=1);
namespace hiperesp\server\interfaces;

interface Bannable {

    public function ban(string $reason, string $action, array $additionalData): void;

}
