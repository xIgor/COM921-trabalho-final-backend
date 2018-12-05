<?php

namespace IntecPhp\Middleware\Validation;

use IntecPhp\Validator\Config\Email;
use IntecPhp\Validator\Config\Password;
use IntecPhp\Validator\Config\Name;

class UserInfoUpdateValidation extends Validation
{
    public function getInputFilterSpecification()
    {
        $nick = new Name();
        $email = new Email();
        $password = new Password();
        return [
            'nick' => $nick(),
            'username' => $email(),
            'password' => $password()
        ];
    }
}
