<?php

use MessageOwl\Responses\OtpResponse;
use MessageOwl\Responses\OtpVerifyResponse;

it('sends an otp', function () {
    $client = $this->mockClient(200, [
        'id'           => 1,
        'phone_number' => '9609999999',
        'timestamp'    => '2020-03-03T08:32:50.231979481Z',
        'message_id'   => 1234,
    ]);

    $response = $client->otp()->send('9609999999');

    expect($response)->toBeInstanceOf(OtpResponse::class)
        ->and($response->id)->toBe(1)
        ->and($response->phoneNumber)->toBe('9609999999')
        ->and($response->messageId)->toBe(1234);
});

it('sends an otp with custom code and length', function () {
    $client = $this->mockClient(200, [
        'id'           => 2,
        'phone_number' => '9609999999',
        'timestamp'    => '2020-03-03T08:32:50.231979481Z',
        'message_id'   => 1235,
    ]);

    $response = $client->otp()->send('9609999999', code: '235311', codeLength: 6);

    expect($response->id)->toBe(2);
});

it('sends an otp with a custom timestamp', function () {
    $client = $this->mockClient(200, [
        'id'           => 3,
        'phone_number' => '9609999999',
        'timestamp'    => '2020-03-03T09:00:00.000000000Z',
        'message_id'   => 1236,
    ]);

    $response = $client->otp()->send('9609999999', timestamp: '2020-03-03T09:00:00.000000000Z');

    expect($response->id)->toBe(3);
});

it('resends an otp', function () {
    $client = $this->mockClient(200, [
        'id'           => 8,
        'phone_number' => '9609999999',
        'timestamp'    => '2020-03-03T08:32:50.231979481Z',
        'message_id'   => 1234,
    ]);

    $response = $client->otp()->resend('9609999999', 8);

    expect($response)->toBeInstanceOf(OtpResponse::class)
        ->and($response->id)->toBe(8);
});

it('verifies an otp', function () {
    $client = $this->mockClient(200, [
        'id'           => 1,
        'phone_number' => '9609999999',
        'status'       => true,
        'timestamp'    => '2020-03-03T08:32:50.231979481Z',
    ]);

    $response = $client->otp()->verify('9609999999', '352682');

    expect($response)->toBeInstanceOf(OtpVerifyResponse::class)
        ->and($response->status)->toBeTrue()
        ->and($response->phoneNumber)->toBe('9609999999');
});

it('returns false status when otp verification fails', function () {
    $client = $this->mockClient(200, [
        'id'           => 1,
        'phone_number' => '9609999999',
        'status'       => false,
        'timestamp'    => '2020-03-03T08:32:50.231979481Z',
    ]);

    $response = $client->otp()->verify('9609999999', 'wrong');

    expect($response->status)->toBeFalse();
});
