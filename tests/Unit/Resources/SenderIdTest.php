<?php

use MessageOwl\Responses\SenderIdResponse;

it('returns a list of sender ids', function () {
    $client = $this->mockClient(200, [
        [
            'id'         => 1,
            'name'       => 'MSGOWL',
            'purpose'    => 'organization',
            'status'     => 'pending verification',
            'created_at' => '2022-12-14T12:46:41.519+05:00',
            'updated_at' => '2022-12-14T12:49:18.203+05:00',
            'remarks'    => 'Please upload a photo',
            'handled_at' => null,
        ],
        [
            'id'         => 2,
            'name'       => 'OXIQA',
            'purpose'    => null,
            'status'     => 'approved',
            'created_at' => '2022-05-10T14:40:15.408+05:00',
            'updated_at' => '2023-01-07T12:00:39.123+05:00',
            'remarks'    => null,
            'handled_at' => '2022-05-10T14:40:33.901+05:00',
        ],
    ]);

    $senderIds = $client->senderIds();

    expect($senderIds)->toHaveCount(2)
        ->and($senderIds[0])->toBeInstanceOf(SenderIdResponse::class)
        ->and($senderIds[0]->name)->toBe('MSGOWL')
        ->and($senderIds[0]->purpose)->toBe('organization')
        ->and($senderIds[0]->status)->toBe('pending verification')
        ->and($senderIds[0]->remarks)->toBe('Please upload a photo')
        ->and($senderIds[0]->handledAt)->toBeNull()
        ->and($senderIds[1]->name)->toBe('OXIQA')
        ->and($senderIds[1]->purpose)->toBeNull()
        ->and($senderIds[1]->status)->toBe('approved')
        ->and($senderIds[1]->handledAt)->toBe('2022-05-10T14:40:33.901+05:00');
});
