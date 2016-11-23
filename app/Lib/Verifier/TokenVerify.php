<?php

namespace App\Lib\Verifier;

use Exception;
use App\Lib\Verifier\InterfaceVerifier;
use App\Lib\Verifier\Exception\OAuthException;
use App\Lib\Verifier\Validator\InterfaceTokenValidator;

class TokenVerify implements InterfaceVerifier
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
     * @param InterfaceTokenValidator $validator
     * @return $this
     */
    public function setValidator(InterfaceTokenValidator $validator)
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
