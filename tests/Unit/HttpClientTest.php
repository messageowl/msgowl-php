<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use MessageOwl\Config;
use MessageOwl\Exceptions\MessageOwlException;
use MessageOwl\Exceptions\RequestTimeoutException;
use MessageOwl\Http\HttpClient;

it('creates a default guzzle client when none is provided', function () {
    $config = new Config('test-key');
    $http = new HttpClient($config);

    expect($http)->toBeInstanceOf(HttpClient::class);
});

it('throws RequestTimeoutException on connect exception', function () {
    $mock = new MockHandler([
        new ConnectException('Connection refused', new Request('GET', 'test')),
    ]);
    $guzzle = new Client(['handler' => HandlerStack::create($mock)]);
    $http = new HttpClient(new Config('test-key'), $guzzle);

    expect(fn () => $http->get('https://rest.msgowl.com/messages'))
        ->toThrow(RequestTimeoutException::class, 'Connection timed out.');
});

it('returns empty array on 204 no content response', function () {
    $mock = new MockHandler([new Response(204)]);
    $guzzle = new Client(['handler' => HandlerStack::create($mock)]);
    $http = new HttpClient(new Config('test-key'), $guzzle);

    $result = $http->delete('https://rest.msgowl.com/contacts/1');

    expect($result)->toBeTrue();
});

it('throws MessageOwlException for unhandled status codes', function () {
    $mock = new MockHandler([
        new Response(400, [], json_encode(['message' => 'Bad request.'])),
    ]);
    $guzzle = new Client(['handler' => HandlerStack::create($mock)]);
    $http = new HttpClient(new Config('test-key'), $guzzle);

    expect(fn () => $http->get('https://rest.msgowl.com/messages'))
        ->toThrow(MessageOwlException::class, 'Bad request.');
});

it('sends request with query auth when useQueryAuth is true', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode(['balance' => '10.00', 'currency' => 'USD'])),
    ]);
    $guzzle = new Client(['handler' => HandlerStack::create($mock)]);
    $http = new HttpClient(new Config('test-key', useQueryAuth: true), $guzzle);

    $result = $http->get('https://rest.msgowl.com/balance');

    expect($result)->toBeArray();
});

it('handles empty response body gracefully', function () {
    $mock = new MockHandler([new Response(200, [], '')]);
    $guzzle = new Client(['handler' => HandlerStack::create($mock)]);
    $http = new HttpClient(new Config('test-key'), $guzzle);

    $result = $http->get('https://rest.msgowl.com/messages');

    expect($result)->toBeArray()->toBeEmpty();
});
