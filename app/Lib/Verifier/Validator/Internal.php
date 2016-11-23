<?php

namespace App\Lib\Verifier\Validator;

use Laravel\Passport\TokenRepository;

class Internal implements InterfaceTokenValidator
{

    protected $user;

    public function __construct()
    {
        $this->user = auth('api')->user();
    }

    /**
     * 
     * @return 
     */
    public function validate()
    {
        if ($this->user)
        {
            return $this->user;
        }

        return false;
    }

}
