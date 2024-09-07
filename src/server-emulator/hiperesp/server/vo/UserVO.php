<?php
namespace hiperesp\server\vo;

class UserVO extends ValueObject {

    private SettingsVO $settings;

    public readonly int $id;

    public readonly string $createdAt;
    public readonly string $updatedAt;

    public readonly string $username;
    public readonly string $email;
    public readonly string $password;

    public readonly string $birthdate;

    public readonly ?string $sessionToken;

    public readonly int $upgraded;
    public readonly int $special;
    public readonly int $activated;
    public readonly int $optIn;

    public readonly bool $banned;
    public readonly ?string $lastLogin;

    protected function patch(array $user): array {
        $user['password'] = "";

        return $user;
    }

    public function isBirthday(string $today): bool {
        $birthdate = \date('m-d', \strtotime($this->birthdate));
        $today = \date('m-d', \strtotime($today));
        return $birthdate === $today;
    }

    public function getAccessLevel(): int {
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

    public function getCharsAllowed(): int {
        if($this->getAccessLevel() > 0) {
            return $this->settings->upgradedChars;
        }
        return $this->settings->nonUpgradedChars;
    }

}