<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OAuthControllerTest extends TestCase
{

    public function testInfoGoogleReturnsHttp401()
    {
        $this->get(url('api/info'), [
            'OAuth-Source' => 'Google',
            'Authorization' => 'Bearer InvalidToken'
        ])->assertResponseStatus(\Illuminate\Http\Response::HTTP_UNAUTHORIZED);
    }

    public function testInfoInternalReturnsHttp401()
    {
        $this->get(url('api/info'), [
            'OAuth-Source' => 'Internal',
            'Authorization' => 'Bearer InvalidToken'
        ])->assertResponseStatus(\Illuminate\Http\Response::HTTP_UNAUTHORIZED);
    }

}
