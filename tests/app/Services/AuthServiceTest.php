<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthServiceTest extends TestCase
{

    protected $obj;

    public function setUp()
    {
        parent::setUp();
        $this->obj = new \App\Services\AuthService(new App\Lib\Verifier\TokenVerifyFactory(new \App\Lib\Verifier\TokenVerify()));
    }

    public function testInfoReturnOk()
    {
        
    }

}
