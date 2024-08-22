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
        throw new DFException(DFException::USER_NOT_FOUND);
    }

    public function signup(array $data): UserVO {
        $data['birthdate'] = \date('Y-m-d', \strtotime($data['birthdate'])); // from mm/dd/yyyy to yyyy-mm-dd

        $data['username'] = \trim($data['username']);
        $data['email'] = \trim($data['email']);

        $user = $this->storage->select(self::COLLECTION, ['username' => $data['username']]);
        if(isset($user[0]) && $user = $user[0]) {
            throw new DFException(DFException::USERNAME_ALREADY_EXISTS);
        }
        $user = $this->storage->select(self::COLLECTION, ['email' => $data['email']]);
        if(isset($user[0]) && $user = $user[0]) {
            throw new DFException(DFException::EMAIL_ALREADY_EXISTS);
        }

        $data['password'] = \password_hash($data['password'], \PASSWORD_DEFAULT);
        $data['sessionToken'] = $this->_generateUniqueSessionToken();
        $data['birthdate'] = \date('Y-m-d', \strtotime($data['birthdate']));

        $user = $this->storage->insert(self::COLLECTION, $data);

        return new UserVO($user);
    }

    public function getBySessionToken(string $sessionToken): UserVO {
        $user = $this->storage->select(self::COLLECTION, ['sessionToken' => $sessionToken]);
        if(isset($user[0]) && $user = $user[0]) {
            return new UserVO($user);
        }
        throw new DFException(DFException::USER_NOT_FOUND);
    }

    private function _generateUniqueSessionToken(): string {
        $token = \bin2hex(\random_bytes(16));
        if($this->storage->select(self::COLLECTION, ['sessionToken' => $token])) {
            return $this->_generateUniqueSessionToken();
        }
        return $token;
    }

}