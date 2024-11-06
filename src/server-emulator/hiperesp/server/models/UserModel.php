<?php declare(strict_types=1);
namespace hiperesp\server\models;

use hiperesp\server\exceptions\DFException;
use hiperesp\server\vo\CharacterVO;
use hiperesp\server\vo\UserVO;

class UserModel extends Model {

    public const COLLECTION = 'user';

    public function getById(int $id): UserVO {
        $user = $this->storage->select(self::COLLECTION, ['id' => $id]);
        if(isset($user[0]) && $user = $user[0]) {
            return new UserVO($user);
        }
        throw new DFException(DFException::USER_NOT_FOUND);
    }

    public function login(string $username, string $password): UserVO {
        $user = $this->storage->select(self::COLLECTION, ['username' => $username]);
        if(isset($user[0]) && $user = $user[0]) {
            if(\password_verify($password, $user['password'])) {
                if($user['banned']) {
                    throw new DFException(DFException::USER_BANNED);
                }
                $user['sessionToken'] = $this->_generateUniqueSessionToken();
                $user['lastLogin'] = \date('c');
                $this->storage->update(self::COLLECTION, $user);
                return new UserVO($user);
            }
        }
        throw new DFException(DFException::USER_NOT_FOUND);
    }

    public function signup(string $username, #[\SensitiveParameter] string $password, string $email, string $birthdate): UserVO {
        $data = [];

        $data['birthdate'] = \date('Y-m-d', \strtotime($birthdate)); // from mm/dd/yyyy to yyyy-mm-dd

        $data['username'] = \trim($username);
        $data['email'] = \trim($email);

        $user = $this->storage->select(self::COLLECTION, ['username' => $data['username']]);
        if(isset($user[0]) && $user = $user[0]) {
            throw new DFException(DFException::USERNAME_ALREADY_EXISTS);
        }
        $user = $this->storage->select(self::COLLECTION, ['email' => $data['email']]);
        if(isset($user[0]) && $user = $user[0]) {
            throw new DFException(DFException::EMAIL_ALREADY_EXISTS);
        }

        $data['password'] = \password_hash($password, \PASSWORD_DEFAULT);
        $data['sessionToken'] = $this->_generateUniqueSessionToken();
        $data['birthdate'] = \date('Y-m-d', \strtotime($birthdate));

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

    public function getByChar(CharacterVO $char): UserVO {
        $user = $this->storage->select(self::COLLECTION, ['id' => $char->userId]);
        if(isset($user[0]) && $user = $user[0]) {
            return new UserVO($user);
        }
        throw new DFException(DFException::USER_NOT_FOUND);
    }

    public function ban(UserVO $user): void {
        $this->storage->update(self::COLLECTION, [ 'id' => $user->id, 'banned' => 1 ]);
    }

    /**
     * Currently only used for test-clean/delete-test-user
     */
    public function delete(UserVO $user): void {
        $this->storage->delete(self::COLLECTION, ['id' => $user->id]);
    }

}