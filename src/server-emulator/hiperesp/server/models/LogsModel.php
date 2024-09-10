<?php
namespace hiperesp\server\models;

use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\LogsVO;
use hiperesp\server\vo\UserVO;
use hiperesp\server\vo\ValueObject;

class LogsModel extends Model {

    public const COLLECTION = 'logs';

    public const SEVERITY_ALLOWED = 'allowed';
    public const SEVERITY_BLOCKED = 'blocked';
    public const SEVERITY_INFO    = 'info';

    public function register(string $severity, string $action, string $description, UserVO|CharacterVO|null $userOrChar, ValueObject $reference, array $additionalData): LogsVO {
        $ip = @$_SERVER['REMOTE_ADDR'] ?: '';
        $userAgent = @$_SERVER['HTTP_USER_AGENT'] ?: '';

        if($userOrChar instanceof CharacterVO) {
            $userId = $userOrChar->userId;
            $charId = $userOrChar->id;
        } else if($userOrChar instanceof UserVO) {
            $userId = $userOrChar->id;
            $charId = null;
        } else {
            $userId = null;
            $charId = null;
        }

        if(!\in_array($severity, [self::SEVERITY_ALLOWED, self::SEVERITY_BLOCKED, self::SEVERITY_INFO])) {
            throw new \Exception("Invalid severity");
        }

        $backtrace1 = \debug_backtrace()[1];
        $service = @$backtrace1['class'] ?: 'unknown';
        $method = @$backtrace1['function'] ?: 'unknown';

        return new LogsVO($this->storage->insert(self::COLLECTION, [
            'userId' => $userId,
            'charId' => $charId,
            'service' => $service,
            'method' => $method,
            'action' => $action,
            'description' => $description,
            'referenceClass' => $reference::class,
            'referenceId' => $reference->id,
            'additionalData' => \json_encode($additionalData),
            'severity' => $severity,
            'ip' => $ip,
            'userAgent' => $userAgent,
        ]));
    }

}