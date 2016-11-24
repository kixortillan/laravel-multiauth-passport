<?php

namespace App\Lib\Verifier;

use App\Lib\Verifier\VerifierInterface;
use App\Lib\Verifier\Validator\Google;
use App\Lib\Verifier\Validator\Internal;

class TokenVerifyFactory
{

    /**
     *
     * @var type 
     */
    protected $verifier;

    /**
     * 
     * @param VerifierInterface $verifier
     */
    public function __construct(VerifierInterface $verifier)
    {
        $this->verifier = $verifier;
    }

    /**
     * 
     * @param type $source
     * @param type $token
     */
    public function getVerifier($source, $token = null)
    {
        switch ($source)
        {
            case 'GOOGLE':
                $this->verifier->setValidator(new Google($token));
                break;
            case 'INTERNAL':
            default :
                $this->verifier->setValidator(new Internal());
                break;
        }

        return $this->verifier;
    }

}
