<?php

declare(strict_types=1);

namespace Opdavies\Glassboxx\Tests\Request;

use Opdavies\Glassboxx\Request\AuthTokenRequestInterface;
use Opdavies\Glassboxx\Request\CustomerRequest;
use Opdavies\Glassboxx\Tests\TestCase;
use Opdavies\Glassboxx\Traits\UsesCreatedAtTrait;
use Opdavies\Glassboxx\ValueObject\CustomerInterface;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class CustomerRequestTest extends TestCase
{
    public function testThatItCreatesACustomer(): void
    {
        $authTokenRequest = $this->getMockAuthTokenRequest();

        $response = $this->getMockBuilder(ResponseInterface::class)
            ->getMock();
        $response->method('getContent')->willReturn('"Success"');

        $client = $this->getMockBuilder(MockHttpClient::class)->getMock();
        $client->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                CustomerRequest::ENDPOINT,
                [
                    'auth_bearer' => $authTokenRequest->getToken(),
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => json_encode([
                        'customer' => [
                            'created_in' => 123,
                            'email' => 'oliver@oliverdavies.uk',
                            'firstname' => 'Oliver',
                            'lastname' => 'Davies',
                        ],
                    ]),
                ]
            )
            ->willReturn($response);

        $request = (new CustomerRequest($client))
            ->forCustomer($this->getMockCustomer())
            ->withAuthToken($authTokenRequest->getToken())
            ->withConfig($this->config);

        $this->assertSame('Success', $request->execute());
    }
}
