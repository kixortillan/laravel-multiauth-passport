<?php

namespace App\Lib\Verifier;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Lib\Verifier\InterfaceVerifier;

class TokenVerify_1 implements InterfaceVerifier
{

    /**
     * Header value to look for source
     * 
     */
    const HEADER_OAUTH_SOURCE = 'OAUTH-SOURCE';

    /**
     * Header value to look for token
     */
    const HEADER_AUTHORIZATION = 'AUTHORIZATION';

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
    protected $parsedUrl;

    /**
     *
     * @var type 
     */
    protected $http;

    /**
     * Source of token provided by request
     * 
     * @var string 
     */
    protected $source;

    /**
     *
     * @var type 
     */
    protected $token;

    /**
     * For mocking guzzlehttp
     * @var type 
     */
    protected $guzzleHttpHandler = null;

    /**
     * Flag for token validity
     * 
     * @var bool 
     */
    protected $isTokenValid = false;

    /**
     *
     * @var type 
     */
    protected $config;

    /**
     * 
     * @param type $handler
     */
    public function __construct(array $config, $handler = null)
    {
        $this->config = $config;
        $this->guzzleHttpHandler = $handler;
    }

    /**
     * Call OAuth API verifier URL's to validate token
     * returns true if token valid, false otherwise
     * 
     * @param Request $request
     * @return boolean
     */
    public function verify(Request $request)
    {
        //get token from header
        $this->token = $this->readToken($request->header(static::HEADER_AUTHORIZATION, null));

        //get the header specifying the source of oauth token
        $this->source = strtoupper($request->header(static::HEADER_OAUTH_SOURCE, null));

        if (empty($this->token) || empty($this->source))
        {
            //skip verify if required headers missing
            return $this->isTokenValid;
        }

        $this->initGuzzle();

        switch ($this->source)
        {
            case static::GOOGLE_AUTH_SOURCE:
                $this->isTokenValid = $this->callGoogle();
                break;
            case static::INTERNAL_AUTH_SOURCE:
            default :
                $this->isTokenValid = $this->callInternalOAuth();
                break;
        }

        return $this->isTokenValid;
    }

    /**
     * Call Google OAuth 2.0 API token validation
     * @return boolean
     */
    private function callGoogle()
    {
        $query = [
            'fields' => 'emailAddress',
        ];

        $opts = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $this->token),
            ]
        ];

        try
        {
            $url = $this->parsedUrl['path'] . "?" . http_build_query($query);
            $response = $this->http->get($url, $opts);
        }
        catch (Exception $ex)
        {
            return false;
        }

        $statusCode = $response->getStatusCode();

        if ($statusCode != \Illuminate\Http\Response::HTTP_OK)
        {
            return false;
        }

        $json = json_decode($response->getBody());

        if (isset($json->error_description))
        {
            return false;
        }

        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function callInternalOAuth()
    {
        $query = [
            'fields' => 'emailAddress',
        ];

        $opts = [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $this->token),
            ]
        ];

        try
        {
            $url = $this->parsedUrl['path'] . "?" . http_build_query($query);
            $response = $this->http->get($url, $opts);
        }
        catch (Exception $ex)
        {
            return false;
        }

        $statusCode = $response->getStatusCode();

        if ($statusCode != \Illuminate\Http\Response::HTTP_OK)
        {
            return false;
        }

        $json = json_decode($response->getBody());

        if (isset($json->error_description))
        {
            return false;
        }

        return true;
    }

    /**
     * Initialize Guzzle Http Client
     * 
     * @throws Exception
     */
    private function initGuzzle()
    {
        switch ($this->source)
        {
            case static::GOOGLE_AUTH_SOURCE:
                $this->parsedUrl = parse_url(array_get($this->config, 'google.api.verify'));
                break;
            case static::INTERNAL_AUTH_SOURCE:
            default :
                $this->parsedUrl = parse_url(array_get($this->config, 'internal.api.verify'));
                break;
        }

        if (!key_exists('scheme', $this->parsedUrl) || !key_exists('host', $this->parsedUrl))
        {
            throw new ConfigException('No url set for ' . title_case($this->source) . ' token.');
        }

        $this->http = new Client([
            'handler' => $this->guzzleHttpHandler,
            'base_uri' => "{$this->parsedUrl['scheme']}://{$this->parsedUrl['host']}",
        ]);
    }

    /**
     * Remove 'Bearer' from token
     * 
     * @param string $val
     * @return string
     */
    private function readToken($val)
    {
        return trim(str_replace('Bearer', '', $val));
    }

}
