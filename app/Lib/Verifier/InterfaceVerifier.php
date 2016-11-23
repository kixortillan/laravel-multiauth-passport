<?php

namespace App\Lib\Verifier;

use App\Lib\Verifier\Validator\InterfaceTokenValidator;

interface InterfaceVerifier
{

    /**
     * Trigger call to verification
     */
    public function verify();

    /**
     * 
     * @param InterfaceTokenValidator $validator
     */
    public function setValidator(InterfaceTokenValidator $validator);
}
