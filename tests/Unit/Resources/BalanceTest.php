<?php

use MessageOwl\Responses\BalanceResponse;

it('returns balance response', function () {
    $client = $this->mockClient(200, ['balance' => '130.6347']);

    $response = $client->balance();

    expect($response)->toBeInstanceOf(BalanceResponse::class)
        ->and($response->balance)->toBe('130.6347');
});
