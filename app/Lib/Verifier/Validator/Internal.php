<?php

namespace App\Lib\Verifier\Validator;

use Laravel\Passport\TokenRepository;

class Internal implements TokenValidatorInterface
{

    public function __construct()
    {
        
    }

    /**
     * 
     * @return 
     */
    public function validate()
    {
        if (!auth('api')->check())
        {
            return false;
        }

        return auth('api')->user();
    }

}
