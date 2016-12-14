<?php

namespace App\Lib\Verifier;

use Exception;
use App\Lib\Verifier\VerifierInterface;
use App\Lib\Verifier\Exception\OAuthException;
use App\Lib\Verifier\Validator\TokenValidatorInterface;

class TokenVerify implements VerifierInterface
{

    /**
     * Header value to look for token
     */
    const HEADER_AUTHORIZATION = 'Authorization';

    /**
     *
     * @var type 
     */
    protected $validator;

    /**
     * 
     */
    public function __construct(TokenValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * 
     * @param TokenValidatorInterface $validator
     * @return $this
     */
    public function setValidator(TokenValidatorInterface $validator)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Call OAuth API verifier URL's to validate token
     * returns true if token valid, false otherwise
     * 
     * @return type
     * @throws Exception\OAuthException
     */
    public function verify()
    {
        $result = $this->validator->validate();

        if (!$result)
        {
            throw new OAuthException();
        }

        return $result;
    }

}
