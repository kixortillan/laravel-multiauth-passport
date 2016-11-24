<?php

namespace App\Lib\Verifier;

use App\Lib\Verifier\Validator\TokenValidatorInterface;

interface VerifierInterface
{

    /**
     * Trigger call to verification
     */
    public function verify();

    /**
     * 
     * @param TokenValidatorInterface $validator
     */
    public function setValidator(TokenValidatorInterface $validator);
}
