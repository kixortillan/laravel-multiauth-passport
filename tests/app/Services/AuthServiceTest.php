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
        $this->obj = new \App\Services\AuthService(new App\Lib\Verifier\TokenVerifyFactory());
    }

    public function testInfoReturnOk()
    {
        $mockRequest = Mockery::mock(\Illuminate\Http\Request::class);
        $mockRequest->shouldReceive('header')
                ->once()
                ->with('OAuth-Source')
                ->andReturn('Internal');

        $mockRequest->shouldReceive('header')
                ->once()
                ->with('Authorization')
                ->andReturn('Some token');

        $this->obj->infoFromToken($mockRequest);
        //$result = $this->obj->infoFromToken($request);
        
    }

}
