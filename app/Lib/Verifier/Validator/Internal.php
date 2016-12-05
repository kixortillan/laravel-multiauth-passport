<?php

namespace App\Lib\Verifier\Validator;

use Illuminate\Contracts\Auth\Guard;

class Internal implements TokenValidatorInterface
{

    /**
     *
     * @var type 
     */
    protected $guard;

    /**
     * 
     * @param Guard $guard
     */
    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * 
     * @return 
     */
    public function validate()
    {
        if (!$this->guard->check())
        {
            return false;
        }

        return $this->guard->user();
    }

}
