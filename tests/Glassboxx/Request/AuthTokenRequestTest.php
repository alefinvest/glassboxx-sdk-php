<?php

declare(strict_types=1);

namespace Opdavies\Glassboxx\Tests\Glassboxx\Request;

use Opdavies\Glassboxx\ConfigInterface;
use Opdavies\Glassboxx\Request\AuthTokenRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AuthTokenRequestTest extends TestCase
{
    public function testThatItGetsAnAuthCode(): void
    {
        $config = $this->getMockBuilder(ConfigInterface::class)
            ->onlyMethods([])
            ->setConstructorArgs(
                [
                    'vendor_id' => 123,
                    'username' => 'opdavies',
                    'password' => 'secret',
                ]
            )
            ->getMock();

        $mockRepsonse = $this->getMockBuilder(ResponseInterface::class)
            ->getMock();
        $mockRepsonse->method('getContent')->willReturn('"abc123"');

        $client = $this->getMockBuilder(MockHttpClient::class)->getMock();
        $client->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                AuthTokenRequest::BASE_URL
                .AuthTokenRequest::ENDPOINT,
                [
                    'query' => [
                        'password' => 'secret',
                        'username' => 'opdavies',
                    ],
                ]
            )
            ->willReturn($mockRepsonse);

        $token = (new AuthTokenRequest($client))
            ->withConfig($config)
            ->getToken();

        $this->assertSame('abc123', $token);
    }
}
