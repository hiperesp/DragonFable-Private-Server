<?php
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\UserVO;

class UserModel extends Model {

    public const COLLECTION = 'user';

    public function login(string $username, string $password): UserVO {
        $user = $this->storage->select(self::COLLECTION, ['username' => $username]);
        if(isset($user[0]) && $user = $user[0]) {
            if(\password_verify($password, $user['password'])) {
                $user['sessionToken'] = $this->_generateUniqueSessionToken();
                $user['lastLogin'] = \date('c');
                $this->storage->update(self::COLLECTION, $user);
                return new UserVO($user);
            }
        }
        throw DFException::fromCode(DFException::USER_NOT_FOUND);
    }

    public function getBySessionToken(string $sessionToken): UserVO {
        $user = $this->storage->select(self::COLLECTION, ['sessionToken' => $sessionToken]);
        if(isset($user[0]) && $user = $user[0]) {
            return new UserVO($user);
        }
        throw DFException::fromCode(DFException::USER_NOT_FOUND);
    }

    private function _generateUniqueSessionToken(): string {
        $token = \bin2hex(\random_bytes(16));
        if($this->storage->select(self::COLLECTION, ['sessionToken' => $token])) {
            return $this->_generateUniqueSessionToken();
        }
        return $token;
    }

}