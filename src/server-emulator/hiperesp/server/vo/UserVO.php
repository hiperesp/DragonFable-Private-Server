<?php
namespace hiperesp\server\vo;

class UserVO extends ValueObject {

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

    public function __construct(array $user) {
        $user["password"] = "";
        parent::__construct($user);
    }

    public function isBirthday(string $today): bool {
        $birthdate = \date('m-d', \strtotime($this->birthdate));
        $today = \date('m-d', \strtotime($today));
        return $birthdate === $today;
    }

    public function getCharsAllowed(SettingsVO $settings): int {
        return $this->upgraded || $this->special ? $settings->upgradedChars : $settings->nonUpgradedChars;
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

}