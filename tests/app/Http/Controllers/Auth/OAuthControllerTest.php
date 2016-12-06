<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OAuthControllerTest extends TestCase
{

    public function testInfoReturnGoogleUserInfo()
    {
        //Authenticate via Google
        
        $this->get(url('api/info'), [
            'OAuthSource' => 'Google',
            'Authorization' => 'Bearer InvalidToken'
        ])->seeJson([
            'email',
            'token'
        ]);
    }

    public function testInfoInvalidGoogleToken()
    {
        $this->get(url('api/info'), [
            'Authorization' => 'Bearer InvalidToken'
        ]);
    }

}
