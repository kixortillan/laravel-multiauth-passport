<?php

namespace App\Lib\Verifier;

use Exception;
use App\Lib\Verifier\VerifierInterface;
use App\Lib\Verifier\Exception\OAuthException;
use App\Lib\Verifier\Validator\TokenValidatorInterface;

class TokenVerify implements VerifierInterface
{

    /**
     * Header value to look for source
     * 
     */
    const HEADER_OAUTH_SOURCE = 'OAuth-Source';

    /**
     * Header value to look for token
     */
    const HEADER_AUTHORIZATION = 'Authorization';

    /**
     * Google source of token
     * 
     */
    const GOOGLE_AUTH_SOURCE = 'GOOGLE';

    /**
     * Google source of token
     * 
     */
    const INTERNAL_AUTH_SOURCE = 'INTERNAL';

    /**
     *
     * @var type 
     */
    protected $validator;

    /**
     * 
     */
    public function __construct()
    {
        
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
