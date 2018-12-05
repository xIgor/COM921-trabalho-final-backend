<?php

namespace IntecPhp\Middleware\Validation;

use IntecPhp\Validator\Config\Password;
use IntecPhp\Validator\Config\Email;

class LoginValidation extends Validation
{
    /**
     * {@inheritdoc}
     */
    public function getInputFilterSpecification()
    {
        $username = new Email();
        $password = new Password();

        return [
            'username' => $username(),
            'password' => $password()
        ];
    }
}
