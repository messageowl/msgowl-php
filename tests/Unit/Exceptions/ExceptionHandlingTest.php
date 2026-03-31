<?php

use MessageOwl\Exceptions\AuthenticationException;
use MessageOwl\Exceptions\MethodNotAllowedException;
use MessageOwl\Exceptions\NotFoundException;
use MessageOwl\Exceptions\RateLimitException;
use MessageOwl\Exceptions\RequestTimeoutException;
use MessageOwl\Exceptions\ServerException;
use MessageOwl\Exceptions\ValidationException;

it('throws AuthenticationException on 401', function () {
    $client = $this->mockClient(401, ['message' => 'Request not allowed (incorrect access_key)']);

    expect(fn () => $client->balance())->toThrow(AuthenticationException::class, 'Request not allowed (incorrect access_key)');
});

it('throws NotFoundException on 404', function () {
    $client = $this->mockClient(404, ['message' => 'Resource not found.']);

    expect(fn () => $client->messages()->find(9999))->toThrow(NotFoundException::class);
});

it('throws MethodNotAllowedException on 405', function () {
    $client = $this->mockClient(405, ['message' => 'Method not allowed.']);

    expect(fn () => $client->balance())->toThrow(MethodNotAllowedException::class);
});

it('throws RequestTimeoutException on 408', function () {
    $client = $this->mockClient(408, ['message' => 'Request timeout.']);

    expect(fn () => $client->balance())->toThrow(RequestTimeoutException::class);
});

it('throws ValidationException on 422', function () {
    $client = $this->mockClient(422, ['message' => 'Unprocessable entity.']);

    expect(fn () => $client->message()->to('9609848571')->from('App')->body('Hi')->send())
        ->toThrow(ValidationException::class);
});

it('throws ValidationException with bulkLimit on bulk limit 422', function () {
    $client = $this->mockClient(422, [
        'message'    => 'Recipients exceeds allowed limit of 25000',
        'bulk_limit' => 25000,
    ]);

    try {
        $client->message()->to('9609848571')->from('App')->body('Hi')->send();
        $this->fail('Expected ValidationException');
    } catch (ValidationException $e) {
        expect($e->bulkLimit)->toBe(25000)
            ->and($e->getMessage())->toContain('25000');
    }
});

it('throws RateLimitException on 429 with retryAfter', function () {
    $client = $this->mockClient(429, [
        'message'     => 'Too many requests. Please try again later',
        'retry_after' => 26,
    ]);

    try {
        $client->balance();
        $this->fail('Expected RateLimitException');
    } catch (RateLimitException $e) {
        expect($e->retryAfter)->toBe(26)
            ->and($e->getMessage())->toContain('Too many requests');
    }
});

it('throws RateLimitException with rate limit headers', function () {
    $client = $this->mockClient(
        429,
        ['message' => 'Too many requests.', 'retry_after' => 30],
        [
            'RateLimit-Limit'     => '50',
            'RateLimit-Remaining' => '0',
            'RateLimit-Reset'     => '1700000000',
        ]
    );

    try {
        $client->balance();
    } catch (RateLimitException $e) {
        expect($e->rateLimitLimit)->toBe(50)
            ->and($e->rateLimitRemaining)->toBe(0)
            ->and($e->rateLimitReset)->toBe(1700000000);
    }
});

it('throws ServerException on 500', function () {
    $client = $this->mockClient(500, ['message' => 'Internal server error.']);

    expect(fn () => $client->balance())->toThrow(ServerException::class);
});

it('throws ServerException on 503', function () {
    $client = $this->mockClient(503, ['message' => 'Service unavailable.']);

    expect(fn () => $client->balance())->toThrow(ServerException::class);
});
