<?php

namespace IntecPhp\Service;

use IntecPhp\Model\User;

class Auth
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function validate(string $email, string $password)
    {
        $usr = $this->user->searchByEmail($email);

        if(!$usr) {
            return;
        }

        if($this->verifyPassword($password, $usr['senha'])) {
            return $usr;
        }
    }

    public function generate($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    private function verifyPassword(string $pass, string $encryptedPass)
    {
        return password_verify($pass, $encryptedPass);
    }
}
