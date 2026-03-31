<?php

use MessageOwl\Enums\DeliveryStatus;
use MessageOwl\Enums\SmsStatus;
use MessageOwl\Responses\MessageDetailResponse;
use MessageOwl\Responses\MessageListItemResponse;
use MessageOwl\Responses\MessageResponse;

it('sends a message to a single recipient', function () {
    $client = $this->mockClient(201, [
        'id'      => 8848,
        'message' => 'Message has been sent successfully.',
    ]);

    $response = $client->message()->to('9609848571')->from('MessageOwl')->body('Hello')->send();

    expect($response)->toBeInstanceOf(MessageResponse::class)
        ->and($response->id)->toBe(8848)
        ->and($response->message)->toBe('Message has been sent successfully.');
});

it('sends a message to multiple recipients as array', function () {
    $client = $this->mockClient(201, [
        'id'      => 8849,
        'message' => 'Message has been sent successfully.',
    ]);

    $response = $client->message()
        ->to(['9609848571', '9609876543'])
        ->from('MessageOwl')
        ->body('Hello')
        ->send();

    expect($response->id)->toBe(8849);
});

it('lists latest 100 messages', function () {
    $client = $this->mockClient(200, [
        [
            'id'         => 8848,
            'sms_header' => 'MessageOwl',
            'status'     => 4,
            'created_at' => '2020-01-12T15:07:32.594+05:00',
            'body'       => 'TEST MESSAGE',
        ],
    ]);

    $messages = $client->messages()->all();

    expect($messages)->toHaveCount(1)
        ->and($messages[0])->toBeInstanceOf(MessageListItemResponse::class)
        ->and($messages[0]->id)->toBe(8848)
        ->and($messages[0]->smsHeader)->toBe('MessageOwl')
        ->and($messages[0]->body)->toBe('TEST MESSAGE');
});

it('returns null body when message.body.read scope is absent', function () {
    $client = $this->mockClient(200, [
        [
            'id'         => 8848,
            'sms_header' => 'MessageOwl',
            'status'     => 4,
            'created_at' => '2020-01-12T15:07:32.594+05:00',
        ],
    ]);

    $messages = $client->messages()->all();

    expect($messages[0]->body)->toBeNull();
});

it('fetches a message by id with recipients', function () {
    $client = $this->mockClient(200, [
        'id'         => 8848,
        'sms_header' => 'MessageOwl',
        'status'     => 4,
        'created_at' => '2020-01-12T15:07:32.594+05:00',
        'account_id' => 1,
        'body'       => 'TEST MESSAGE THREE',
        'recipients' => [
            [
                'id'              => 78963,
                'phone_number'    => '9609999999',
                'delivery_status' => 1,
                'sms_status'      => 1,
                'delivered_on'    => '2020-01-12T15:07:33.454+05:00',
            ],
        ],
    ]);

    $response = $client->messages()->find(8848);

    expect($response)->toBeInstanceOf(MessageDetailResponse::class)
        ->and($response->id)->toBe(8848)
        ->and($response->accountId)->toBe(1)
        ->and($response->body)->toBe('TEST MESSAGE THREE')
        ->and($response->recipients)->toHaveCount(1)
        ->and($response->recipients[0]->phoneNumber)->toBe('9609999999')
        ->and($response->recipients[0]->deliveryStatus)->toBe(DeliveryStatus::Delivered)
        ->and($response->recipients[0]->smsStatus)->toBe(SmsStatus::Delivered)
        ->and($response->recipients[0]->deliveredOn)->toBe('2020-01-12T15:07:33.454+05:00');
});
