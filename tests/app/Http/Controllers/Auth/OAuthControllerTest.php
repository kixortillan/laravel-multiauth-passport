<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OAuthControllerTest extends TestCase
{

    public function testInfoReturnGoogleUserInfo()
    {
        //Authenticate via Google
        $result = $this->visit(url('oauth/google'));
        
        dd($result);
        
//        $this->get(url('api/info'), [
//            'Authorization' => 'Bearer InvalidToken'
//        ]);
    }

    public function testInfoInvalidGoogleToken()
    {
        $this->get(url('api/info'), [
            'Authorization' => 'Bearer InvalidToken'
        ]);
    }

}
