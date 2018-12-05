<?php

namespace IntecPhp\Middleware\Validation;

use IntecPhp\Validator\Config\Password;
use IntecPhp\Validator\Config\Hash;

class PasswordChangeValidation extends Validation
{
    public function getInputFilterSpecification()
    {
        $hash = new Hash();
        $password = new Password();
        return [
            'hash' => $hash(),
            'password' => $password()
        ];
    }
}
