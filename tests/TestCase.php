<?php

namespace MessageOwl\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MessageOwl\Config;
use MessageOwl\Http\HttpClient;
use MessageOwl\MessageOwl;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    protected function mockClient(int $status, array $body, array $headers = []): MessageOwl
    {
        $http = $this->mockHttp($status, $body, $headers);

        return new MessageOwl('test-key', http: $http);
    }

    protected function mockHttp(int $status, array $body, array $headers = []): HttpClient
    {
        $mock = new MockHandler([
            new Response($status, $headers, json_encode($body)),
        ]);

        $guzzle = new Client(['handler' => HandlerStack::create($mock)]);
        $config = new Config('test-key');

        return new HttpClient($config, $guzzle);
    }
}
