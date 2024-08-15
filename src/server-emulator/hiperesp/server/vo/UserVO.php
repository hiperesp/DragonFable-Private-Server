<?php
namespace hiperesp\server\vo;

class UserVO extends ValueObject {

    private int $id;

    private int $charsAllowed;

    private int $accessLevel;
    private int $upgrade;
    private int $activationFlag;
    private int $optin;
    private int $adFlag;

    private string $username;
    private string $email;
    private string $password;

    private string $authToken;

}
