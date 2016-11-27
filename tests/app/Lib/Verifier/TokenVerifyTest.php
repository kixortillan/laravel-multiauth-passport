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
        $this->config = [
            'google' => [
                'api' => [
                    /**
                     * the Google OAuth 2.0 API endpoint for validating token
                     * returns email on success
                     */
                    'verify' => 'https://www.googleapis.com/gmail/v2/users/me/profile',
                ]
            ],
            'internal' => [
                'api' => [
                    /**
                     * the Internal OAuth 2.0 API endpoint for validating token
                     * returns email on success
                     */
                    'verify' => 'http://localhost:8000/oauth/token/email',
                ]
            ]
        ];
    }

    public function testUnknownValidator()
    {
        $this->obj = new App\Lib\Verifier\TokenVerify();

        $mock = $this->createMock(\App\Lib\Verifier\Validator\TokenValidatorInterface::class);

        $this->obj->setValidator($mock);

        $this->expectException(Exception::class);
        $this->obj->verify();
    }

//    public function testNoEmptyConfig()
//    {
//        $urlInfo = parse_url('');
//
//        $handler = new MockHandler([
//            new GuzzleHttp\Psr7\Response(200)
//        ]);
//
//        $http = new \GuzzleHttp\Client([
//            'base_uri' => $urlInfo['scheme'] . '?' . $urlInfo['host'],
//            'handler' => $handler,
//        ]);
//
//        $this->obj = new App\Lib\Verifier\TokenVerify();
//        $this->obj->setValidator(new App\Lib\Verifier\Validator\Google($http, $urlInfo['path']));
//        $this->obj->setToken('test invalid token');
//        $this->obj->setSource(App\Lib\Verifier\TokenVerify::GOOGLE_AUTH_SOURCE);
//
//        $this->expectException(App\Lib\Middleware\Verifier\ConfigException::class);
//        $this->obj->verify();
//    }
//
//    public function testNoAuthorizationHeader()
//    {
//        $urlInfo = parse_url($this->config['google']['api']['verify']);
//
//        $handler = new MockHandler([
//            new GuzzleHttp\Psr7\Response(200)
//        ]);
//
//        $http = new \GuzzleHttp\Client([
//            'base_uri' => $urlInfo['scheme'] . '?' . $urlInfo['host'],
//            'handler' => $handler,
//        ]);
//
//        $this->obj = new App\Lib\Verifier\TokenVerify();
//        $this->obj->setValidator(new App\Lib\Verifier\Validator\Google($http, $urlInfo['path']));
//        $this->obj->setSource(App\Lib\Verifier\TokenVerify::GOOGLE_AUTH_SOURCE);
//
//        $this->assertNotTrue($this->obj->verify());
//    }
//
//    public function testNoOAuthSourceHeader()
//    {
//        $urlInfo = parse_url($this->config['google']['api']['verify']);
//
//        $handler = new MockHandler([
//            new GuzzleHttp\Psr7\Response(200)
//        ]);
//
//        $http = new \GuzzleHttp\Client([
//            'base_uri' => $urlInfo['scheme'] . '?' . $urlInfo['host'],
//            'handler' => $handler,
//        ]);
//
//        $this->obj = new App\Lib\Verifier\TokenVerify($this->config, $handler);
//
//        $this->obj = new App\Lib\Verifier\TokenVerify();
//        $this->obj->setValidator(new App\Lib\Verifier\Validator\Google($http, $urlInfo['path']));
//        $this->obj->setAuthorizationHeader('test invalid token');
//
//        $this->assertNotTrue($this->obj->verify());
//    }
//
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

        /* Socialite::shouldReceive('driver->userFromToken')
          ->with('some token')
          ->once()
          ->andReturn($mockGoogleUser); */

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
        $handler = new MockHandler([
            new GuzzleHttp\Psr7\Response(400, [], json_encode(["error_description" => "Invalid Value"])),
        ]);

        $http = new \GuzzleHttp\Client([
            'handler' => $handler,
        ]);

        $this->obj = new App\Lib\Verifier\TokenVerify();
        
        $mockSocialite = Mockery::mock(Laravel\Socialite\Two\GoogleProvider::class);

        $mockSocialite->setHttpClient($http);
        
        $mockSocialite->shouldReceive('driver->userFromToken')
                ->with('some token')
                ->once()
                ->andReturn($mockGoogleUser);
        
        $mockRepo = Mockery::mock(\App\Repositories\UserRepository::class);

        $this->obj->setValidator(new App\Lib\Verifier\Validator\Google($mockSocialite, $mockRepo, 'some token'));
        $this->obj->setAuthorizationHeader('test invalid token');
        $this->obj->setSource(App\Lib\Verifier\TokenVerify::GOOGLE_AUTH_SOURCE);

        $this->assertNotTrue($this->obj->verify());
    }

//
//    public function testVerifyFailGoogle500()
//    {
//        $urlInfo = parse_url($this->config['google']['api']['verify']);
//
//        $handler = new MockHandler([
//            new GuzzleHttp\Psr7\Response(500),
//        ]);
//
//        $http = new \GuzzleHttp\Client([
//            'base_uri' => $urlInfo['scheme'] . '?' . $urlInfo['host'],
//            'handler' => $handler,
//        ]);
//
//        $this->obj = new App\Lib\Verifier\TokenVerify($this->config, $handler);
//
//        $this->obj = new App\Lib\Verifier\TokenVerify();
//        $this->obj->setValidator(new App\Lib\Verifier\Validator\Google($http, $urlInfo['path']));
//        $this->obj->setAuthorizationHeader('test invalid token');
//        $this->obj->setSource(App\Lib\Verifier\TokenVerify::GOOGLE_AUTH_SOURCE);
//
//        $this->assertNotTrue($this->obj->verify());
//    }
//
//    public function testVerifyFailGoogleWithException()
//    {
//        $urlInfo = parse_url($this->config['google']['api']['verify']);
//
//        $handler = new MockHandler([
//            new GuzzleHttp\Exception\RequestException("Not Found", new \GuzzleHttp\Psr7\Request('GET', ''))
//        ]);
//
//        $http = new \GuzzleHttp\Client([
//            'base_uri' => $urlInfo['scheme'] . '?' . $urlInfo['host'],
//            'handler' => $handler,
//        ]);
//
//        $this->obj = new App\Lib\Verifier\TokenVerify($this->config, $handler);
//
//        $this->obj = new App\Lib\Verifier\TokenVerify();
//        $this->obj->setValidator(new App\Lib\Verifier\Validator\Google($http, $urlInfo['path']));
//        $this->obj->setAuthorizationHeader('test invalid token');
//        $this->obj->setSource(App\Lib\Verifier\TokenVerify::GOOGLE_AUTH_SOURCE);
//
//        $this->assertNotTrue($this->obj->verify());
//    }
//
//    public function testVerifySuccessInternal()
//    {
//        $handler = new MockHandler([
//            new GuzzleHttp\Psr7\Response(200)
//        ]);
//
//        $this->obj = new App\Lib\Middleware\Verifier\TokenVerify($this->config, $handler);
//
//        $mockRequest = $this->createMock(Illuminate\Http\Request::class);
//        $mockRequest->expects($this->at(0))
//                ->method('header')
//                ->with(App\Lib\Middleware\Verifier\TokenVerify::HEADER_AUTHORIZATION)
//                ->willReturn('test valid token');
//        $mockRequest->expects($this->at(1))
//                ->method('header')
//                ->with(App\Lib\Middleware\Verifier\TokenVerify::HEADER_OAUTH_SOURCE)
//                ->willReturn(App\Lib\Middleware\Verifier\TokenVerify::INTERNAL_AUTH_SOURCE);
//
//        $urlInfo = parse_url($this->config['google']['api']['verify']);
//
//        $this->obj = new App\Lib\Verifier\TokenVerify();
//        $this->obj->setValidator(new App\Lib\Verifier\Validator\Internal());
//        $this->obj->setAuthorizationHeader('test invalid token');
//        $this->obj->setSource(App\Lib\Verifier\TokenVerify::INTERNAL_AUTH_SOURCE);
//
//        $this->assertTrue($this->obj->verify());
//    }
//
//    public function testVerifyFailInternal400()
//    {
//        $handler = new MockHandler([
//            new GuzzleHttp\Psr7\Response(400, [], json_encode(["error_description" => "Invalid Value"])),
//        ]);
//
//        $this->obj = new App\Lib\Middleware\Verifier\TokenVerify($this->config, $handler);
//
//        $mockRequest = $this->createMock(Illuminate\Http\Request::class);
//        $mockRequest->expects($this->at(0))
//                ->method('header')
//                ->with(App\Lib\Middleware\Verifier\TokenVerify::HEADER_AUTHORIZATION)
//                ->willReturn('test valid token');
//        $mockRequest->expects($this->at(1))
//                ->method('header')
//                ->with(App\Lib\Middleware\Verifier\TokenVerify::HEADER_OAUTH_SOURCE)
//                ->willReturn(App\Lib\Middleware\Verifier\TokenVerify::INTERNAL_AUTH_SOURCE);
//
//        $this->assertNotTrue($this->obj->verify($mockRequest));
//    }
//
//    public function testVerifyFailInternal500()
//    {
//        $handler = new MockHandler([
//            new GuzzleHttp\Psr7\Response(500),
//        ]);
//
//        $this->obj = new App\Lib\Middleware\Verifier\TokenVerify($this->config, $handler);
//
//        $mockRequest = $this->createMock(Illuminate\Http\Request::class);
//        $mockRequest->expects($this->at(0))
//                ->method('header')
//                ->with(App\Lib\Middleware\Verifier\TokenVerify::HEADER_AUTHORIZATION)
//                ->willReturn('test valid token');
//        $mockRequest->expects($this->at(1))
//                ->method('header')
//                ->with(App\Lib\Middleware\Verifier\TokenVerify::HEADER_OAUTH_SOURCE)
//                ->willReturn(App\Lib\Middleware\Verifier\TokenVerify::INTERNAL_AUTH_SOURCE);
//
//        $this->assertNotTrue($this->obj->verify($mockRequest));
//    }
//
//    public function testVerifyFailInternalWithException()
//    {
//        $handler = new MockHandler([
//            new GuzzleHttp\Exception\RequestException("Not Found", new \GuzzleHttp\Psr7\Request('GET', ''))
//        ]);
//
//        $this->obj = new App\Lib\Middleware\Verifier\TokenVerify($this->config, $handler);
//
//        $mockRequest = $this->createMock(Illuminate\Http\Request::class);
//        $mockRequest->expects($this->at(0))
//                ->method('header')
//                ->with(App\Lib\Middleware\Verifier\TokenVerify::HEADER_AUTHORIZATION)
//                ->willReturn('test invalid token');
//        $mockRequest->expects($this->at(1))
//                ->method('header')
//                ->with(App\Lib\Middleware\Verifier\TokenVerify::HEADER_OAUTH_SOURCE)
//                ->willReturn(App\Lib\Middleware\Verifier\TokenVerify::INTERNAL_AUTH_SOURCE);
//
//        $this->assertNotTrue($this->obj->verify($mockRequest));
//    }
}
