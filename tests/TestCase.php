<?php

namespace Opdavies\Glassboxx\Tests;

use Opdavies\Glassboxx\Config;
use Opdavies\Glassboxx\Request\AuthTokenRequestInterface;
use Opdavies\Glassboxx\ValueObject\CustomerInterface;
use Opdavies\Glassboxx\ValueObject\OrderInterface;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /** @var Config */
    protected $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = $this->getMockBuilder(Config::class)
            ->onlyMethods([])
            ->setConstructorArgs(
                [
                    'vendor_id' => 123,
                    'username' => 'opdavies',
                    'password' => 'secret',
                ]
            )
            ->getMock();
    }

    protected function getMockAuthTokenRequest(): AuthTokenRequestInterface
    {
        $authTokenRequest = $this->getMockBuilder(AuthTokenRequestInterface::class)
            ->getMock();

        $authTokenRequest->expects($this->any())
            ->method('getToken')
            ->willReturn('testtoken');

        return $authTokenRequest;
    }

    protected function getMockCustomer(): CustomerInterface
    {
        $customer = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $customer->method('getFirstName')->willReturn('Oliver');
        $customer->method('getLastName')->willReturn('Davies');
        $customer->method('getEmailAddress')->willReturn('oliver@oliverdavies.uk');

        return $customer;
    }

    protected function getMockOrder(): OrderInterface
    {
        $order = $this->getMockBuilder(OrderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $order->method('getCurrencyCode')->willReturn('GBP');
        $order->method('getCustomer')->willReturn($this->getMockCustomer());
        $order->method('getOrderNumber')->willReturn('abc123');

        return $order;
    }
}
