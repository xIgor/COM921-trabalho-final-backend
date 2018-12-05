<?php

namespace IntecPhp\Middleware\Validation;

use IntecPhp\Validator\Config\Email;



class PasswordRecoveryValidation extends Validation
{
    public function getInputFilterSpecification()
    {
        $email = new Email();
        return [
            'email' => $email()
        ];
    }
}
