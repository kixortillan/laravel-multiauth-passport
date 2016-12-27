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
    
    public function testInfoEmptyHeader()
    {
        $mockRequest = Mockery::mock(\Illuminate\Http\Request::class);
        $mockRequest->shouldReceive('query')
                ->once()
                ->with('provider', null)
                ->andReturnNull();

        $mockRequest->shouldReceive('header')
                ->once()
                ->with('Authorization', null)
                ->andReturnNull();

        $this->expectException(RuntimeException::class);
        $this->obj->infoFromToken($mockRequest);
    }

    public function testInfoReturnOkInternal()
    {
//        $user = factory(App\User::class)->create();
//
//        $mockRequest = Mockery::mock(\Illuminate\Http\Request::class);
//        $mockRequest->shouldReceive('header')
//                ->once()
//                ->with('OAuth-Source', null)
//                ->andReturn('Internal');
//
//        $mockRequest->shouldReceive('header')
//                ->once()
//                ->with('Authorization', null)
//                ->andReturn('Some token');
//
//        //Socialite::shouldReceive('');
//        $this->obj->infoFromToken($mockRequest);
//        //$result = $this->obj->infoFromToken($request);
    }

    public function testInfoReturnOkGoogle()
    {
        
    }

}
