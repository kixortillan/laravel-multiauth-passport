<?php

use GuzzleHttp\Handler\MockHandler;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TokenVerifyTest extends TestCase
{

    protected $obj;
    protected $config;

    public function setUp()
    {
        parent::setUp();
    }

    public function testUnknownValidator()
    {
        $this->obj = new App\Lib\Verifier\TokenVerify();

        $mock = $this->createMock(\App\Lib\Verifier\Validator\TokenValidatorInterface::class);

        $this->obj->setValidator($mock);

        $this->expectException(Exception::class);
        $this->obj->verify();
    }

    public function testVerifySuccessGoogle()
    {
        $this->obj = new App\Lib\Verifier\TokenVerify();

        $mockGoogleUser = Mockery::mock(Laravel\Socialite\Two\User::class);

        $mockGoogleUser->shouldReceive('getId')
                ->andReturn(rand())
                ->shouldReceive('getName')
                ->andReturn(str_random(10))
                ->shouldReceive('getEmail')
                ->andReturn(str_random(10) . '@gmail.com')
                ->shouldReceive('getAvatar')
                ->andReturn('https://en.gravatar.com/userimage');

        $mockSocialite = Mockery::mock(Laravel\Socialite\Two\GoogleProvider::class);

        $mockSocialite->shouldReceive('driver->userFromToken')
                ->with('some token')
                ->once()
                ->andReturn($mockGoogleUser);

        $mockUser = Mockery::mock('App\User');

        $mockRepo = Mockery::mock(\App\Repositories\UserRepository::class);

        $mockRepo->shouldReceive('findByEmail')
                ->once()
                ->andReturn($mockUser);

        $this->obj->setValidator(new App\Lib\Verifier\Validator\Google($mockSocialite, $mockRepo, 'some token'));

        $this->assertInstanceOf(App\User::class, $this->obj->verify());
    }

    public function testVerifyFailGoogle400()
    {
        $this->obj = new App\Lib\Verifier\TokenVerify();

        $mockGoogleUser = Mockery::mock(Laravel\Socialite\Two\User::class);

        $mockGoogleUser->shouldReceive('getId')
                ->andReturn(rand())
                ->shouldReceive('getName')
                ->andReturn(str_random(10))
                ->shouldReceive('getEmail')
                ->andReturn(str_random(10) . '@gmail.com')
                ->shouldReceive('getAvatar')
                ->andReturn('https://en.gravatar.com/userimage');

        $mockSocialite = Mockery::mock(Laravel\Socialite\Two\GoogleProvider::class);

        $mockSocialite->shouldReceive('driver->userFromToken')
                ->with('some token')
                ->once()
                ->andThrow(new GuzzleHttp\Exception\ClientException('Error', new \GuzzleHttp\Psr7\Request('GET', '')));

        $mockRepo = Mockery::mock(\App\Repositories\UserRepository::class);

        $this->obj->setValidator(new App\Lib\Verifier\Validator\Google($mockSocialite, $mockRepo, 'some token'));

        $this->expectException(App\Lib\Verifier\Exception\OAuthException::class);
        $this->obj->verify();
    }

    public function testVerifyFailGoogle500()
    {
        $this->obj = new App\Lib\Verifier\TokenVerify();

        $mockGoogleUser = Mockery::mock(Laravel\Socialite\Two\User::class);

        $mockGoogleUser->shouldReceive('getId')
                ->andReturn(rand())
                ->shouldReceive('getName')
                ->andReturn(str_random(10))
                ->shouldReceive('getEmail')
                ->andReturn(str_random(10) . '@gmail.com')
                ->shouldReceive('getAvatar')
                ->andReturn('https://en.gravatar.com/userimage');

        $mockSocialite = Mockery::mock(Laravel\Socialite\Two\GoogleProvider::class);

        $mockSocialite->shouldReceive('driver->userFromToken')
                ->with('some token')
                ->once()
                ->andThrow(new GuzzleHttp\Exception\ServerException('Error', new \GuzzleHttp\Psr7\Request('GET', '')));

        $mockRepo = Mockery::mock(\App\Repositories\UserRepository::class);

        $this->obj->setValidator(new App\Lib\Verifier\Validator\Google($mockSocialite, $mockRepo, 'some token'));

        $this->expectException(App\Lib\Verifier\Exception\OAuthException::class);
        $this->obj->verify();
    }

    public function testVerifyFailGoogleWithException()
    {
        $this->obj = new App\Lib\Verifier\TokenVerify();

        $mockSocialite = Mockery::mock(Laravel\Socialite\Two\GoogleProvider::class);

        $mockSocialite->shouldReceive('driver->userFromToken')
                ->with('some token')
                ->once()
                ->andThrow(new GuzzleHttp\Exception\RequestException("Not Found", new \GuzzleHttp\Psr7\Request('GET', '')));

        $mockRepo = Mockery::mock(\App\Repositories\UserRepository::class);

        $this->obj->setValidator(new App\Lib\Verifier\Validator\Google($mockSocialite, $mockRepo, 'some token'));

        $this->expectException(\App\Lib\Verifier\Exception\OAuthException::class);
        $this->obj->verify();
    }

    public function testVerifyNullGoogleUser()
    {
        $this->obj = new App\Lib\Verifier\TokenVerify();

        $mockSocialite = Mockery::mock(Laravel\Socialite\Two\GoogleProvider::class);

        $mockSocialite->shouldReceive('driver->userFromToken')
                ->with('some token')
                ->once()
                ->andReturnNull();

        $mockRepo = Mockery::mock(\App\Repositories\UserRepository::class);

        $this->obj->setValidator(new App\Lib\Verifier\Validator\Google($mockSocialite, $mockRepo, 'some token'));

        $this->expectException(\App\Lib\Verifier\Exception\OAuthException::class);
        $this->obj->verify();
    }

    public function testVerifySuccessInternal()
    {
        $this->obj = new App\Lib\Verifier\TokenVerify();

        $mockUser = Mockery::mock('App\User');

        $mockGuard = Mockery::mock(Illuminate\Contracts\Auth\Guard::class);

        $mockGuard->shouldReceive('check')
                ->once()
                ->andReturn(true);

        $mockGuard->shouldReceive('user')
                ->once()
                ->andReturn($mockUser);

        $this->obj->setValidator(new App\Lib\Verifier\Validator\Internal($mockGuard));

        $this->assertInstanceOf(App\User::class, $this->obj->verify());
    }

    public function testVerifyInternalCheckReturnFalse()
    {
        $this->obj = new App\Lib\Verifier\TokenVerify();

        $mockUser = Mockery::mock('App\User');

        $mockGuard = Mockery::mock(Illuminate\Contracts\Auth\Guard::class);

        $mockGuard->shouldReceive('check')
                ->once()
                ->andReturn(false);

        $mockGuard->shouldReceive('user')
                ->never();

        $this->obj->setValidator(new App\Lib\Verifier\Validator\Internal($mockGuard));

        $this->expectException(\App\Lib\Verifier\Exception\OAuthException::class);
        $this->obj->verify();
    }

}
