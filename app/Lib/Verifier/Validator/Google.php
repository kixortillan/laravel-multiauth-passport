<?php

namespace App\Lib\Verifier\Validator;

use Socialite;
use App\Repositories\UserRepository;

class Google implements InterfaceTokenValidator
{

    /**
     *
     * @var type 
     */
    protected $repo;

    /**
     *
     * @var type 
     */
    protected $token;

    public function __construct(UserRepository $repo, $token)
    {
        $this->repo = $repo;
        $this->token = str_replace('Bearer', '', $token);
    }

    public function validate()
    {
        $googleUser = Socialite::driver('google')->userFromToken($this->token);

        $user = $this->repo->findByEmail($googleUser->email);

        if ($user)
        {
            return $user;
        }

        return false;
        /* $query = [
          'fields' => 'emailAddress',
          ];

          $opts = [
          'headers' => [
          'Authorization' => sprintf('Bearer %s', $this->token),
          ]
          ];

          try
          {
          $url = $this->path . "?" . http_build_query($query);
          $response = $this->http->get($url, $opts);
          }
          catch (Exception $ex)
          {
          dd($ex->getMessage());
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

          return with(new GoogleToken())->user(); */
    }

}
