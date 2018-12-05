<?php

namespace IntecPhp\Middleware\Validation;

use IntecPhp\Validator\Config\Email;
use IntecPhp\Validator\Config\Password;
use IntecPhp\Validator\Config\Name;
use IntecPhp\Validator\Config\UserType;

class AccountCreateValidation extends Validation
{
    public function getInputFilterSpecification()
    {
        $type = new UserType();
        $nick = new Name();
        $email = new Email();
        $password = new Password();
        return [
            'nick' => $nick(),
            'username' => $email(),
            'password' => $password(),
            'type' => $type()
        ];
    }
}
