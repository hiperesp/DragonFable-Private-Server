<?php declare(strict_types=1);
namespace hiperesp\server\attributes;

use hiperesp\server\vo\UserVO;
use ReflectionMethod;

#[\Attribute(\Attribute::TARGET_METHOD)]
class ChatCommand {
    public function __construct(
        public string $command,
        public string $helpText = "",
        public bool $admin = false,
    ) {
        if(!$command) {
            throw new \InvalidArgumentException("Command cannot be empty");
        }
        if(!$command[0] === "/") {
            throw new \InvalidArgumentException("Command must start with /");
        }
    }

    public function call(ReflectionMethod $rMethod, UserVO $user, string $message): ?array {
        if(!$this->canCall($user)) {
            return null;
        }

        $rParameters = $rMethod->getParameters();
        // first parameter is always UserVO

        $commandParts = \preg_split('/\s+/', $message, \count($rParameters));
        if($commandParts[0] !== $this->command) {
            return null;
        }
        $requiredParameters = \array_filter($rParameters, fn($p) => !$p->isOptional());
        if(\count($commandParts) < \count($requiredParameters)) {
            throw new \InvalidArgumentException("Usage: {$this->getUsage($rMethod)}");
        }

        $arguments = [];
        foreach($rParameters as $i => $rParameter) {
            if($i === 0) {
                $arguments[] = $user;
                continue;
            }
            if($i < \count($commandParts)) {
                $arguments[] = $commandParts[$i];
            }
        }

        return $arguments;
    }

    public function getUsage(ReflectionMethod $rMethod): string {
        $rParameters = $rMethod->getParameters();
        $usage = $this->command;

        $parameterIndex = 0;
        foreach($rParameters as $rParameter) {
            if($parameterIndex++ === 0) {
                // skip UserVO
                continue;
            }
            $usage .= " " . ($rParameter->isOptional() ? "[{$rParameter->getName()}]" : "<{$rParameter->getName()}>");
        }
        return $usage;
    }

    public function canCall(UserVO $user): bool {
        return !$this->admin || $user->id == 1;
    }
}