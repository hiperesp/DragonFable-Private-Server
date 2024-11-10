<?php declare(strict_types=1);
namespace hiperesp\server\vo;

use hiperesp\server\attributes\Inject;
use hiperesp\server\models\CharacterModel;

class UserVO extends ValueObject implements Bannable {

    #[Inject] private CharacterModel $characterModel;
    #[Inject] private SettingsVO $settings;

    public readonly string $createdAt;
    public readonly string $updatedAt;

    public readonly string $username;
    public readonly string $email;
    public readonly string $password;

    public readonly string $birthdate;

    public readonly ?string $sessionToken;

    public readonly int $upgraded;
    public readonly int $special;
    public readonly int $activated; // is account activated by email?
    public readonly int $optIn;     // opt in for newsletter: used at https://github.com/hiperesp/DragonFable-Private-Server/issues/49#issuecomment-2406559101

    public readonly bool $banned;
    public readonly ?string $lastLogin;

    public readonly string $recoveryCode;
    public readonly string $recoveryExpires;

    #[\Override]
    protected function patch(array $user): array {
        $user['password'] = "";

        return $user;
    }

    public int $accessLevel {
        get {
            //   From game.swf:
            //     < 0 = Disabled,
            //     0 or 2 = Normal (Free or Upgraded),
            //     Any other value = Special
            //   What I think about the values:
            //     -1 = disabled,
            //     0 = free,
            //     1 = special,
            //     2 = upgraded
            if($this->banned) {
                return -1;
            }
            if($this->special) {
                return 1;
            }
            if($this->upgraded) {
                return 2;
            }

            return 0;
        }
    }

    public int $upgradedFlag { // this flag is how many chars is upgraded, but 2 to 5 is same as 0
        get {
            if($this->upgraded) {
                if($this->settings->canDeleteUpgradedChar) {
                    return 6; // user upgraded entire account, all chars can be deleted and new chars will be upgraded
                }
                return 1; // user upgraded a single char, user cant delete upgraded chars
            }
            return 0; // user not upgraded, can delete all chars
        }
    }

    public int $charsAllowed {
        get {
            if($this->accessLevel > 0) {
                return $this->settings->upgradedChars;
            }
            return $this->settings->nonUpgradedChars;
        }
    }

    public function isBirthday(): bool {
        $birthdate = \date('m-d', \strtotime($this->birthdate));
        $today = \date('m-d', \strtotime(\date('c')));
        return $birthdate === $today;
    }

    public function getChars(): array {
        return $this->characterModel->getByUser($this);
    }

}