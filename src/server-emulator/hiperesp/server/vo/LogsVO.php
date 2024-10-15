<?php declare(strict_types=1);
namespace hiperesp\server\vo;

use hiperesp\server\exceptions\DFException;

class LogsVO extends ValueObject {
    public readonly int $id;

    public readonly string $createdAt;

    public readonly ?int $userId;
    public readonly ?int $charId;

    public readonly string $service;
    public readonly string $method;

    public readonly string $action;
    public readonly string $description;

    public readonly string $referenceClass;
    public readonly string $referenceId;
    public readonly array $additionalData;

    public readonly ?string $severity;

    public readonly ?string $ip;
    public readonly ?string $userAgent;

    #[\Override]
    protected function patch(array $data): array {
        $data['additionalData'] = \json_decode($data['additionalData'], true);
        return $data;
    }

    public function asException(string $dfCode): DFException {
        return new DFException($dfCode);
    }
}
