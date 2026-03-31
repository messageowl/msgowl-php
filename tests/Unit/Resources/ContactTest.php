<?php

use MessageOwl\Responses\ContactListResponse;
use MessageOwl\Responses\ContactResponse;

$contactData = [
    'id'         => 1232,
    'name'       => 'Customer',
    'number'     => '9609999999',
    'created_at' => '2020-01-12T15:05:32.594+05:00',
    'updated_at' => '2020-01-12T15:05:32.594+05:00',
    'groups'     => [
        ['id' => 22, 'name' => 'The Group'],
        ['id' => 24, 'name' => 'Oxiqa'],
    ],
];

it('lists contacts with pagination', function () {
    $client = $this->mockClient(200, [
        'contacts'      => [
            [
                'id'         => 1232,
                'name'       => 'Customer',
                'number'     => '9609999999',
                'account_id' => 1,
                'user_id'    => 1,
                'created_at' => '2020-01-12T15:05:32.594+05:00',
                'updated_at' => '2020-01-12T15:05:32.594+05:00',
            ],
        ],
        'current_page'  => 1,
        'next_page'     => 2,
        'previous_page' => null,
    ]);

    $list = $client->contacts()->all();

    expect($list)->toBeInstanceOf(ContactListResponse::class)
        ->and($list->contacts)->toHaveCount(1)
        ->and($list->contacts[0]->id)->toBe(1232)
        ->and($list->contacts[0]->accountId)->toBe(1)
        ->and($list->currentPage)->toBe(1)
        ->and($list->nextPage)->toBe(2)
        ->and($list->previousPage)->toBeNull();
});

it('creates a contact with groups', function () use ($contactData) {
    $client = $this->mockClient(201, $contactData);

    $contact = $client->contacts()->create('Customer', '9609999999', ['The Group', 'Oxiqa']);

    expect($contact)->toBeInstanceOf(ContactResponse::class)
        ->and($contact->id)->toBe(1232)
        ->and($contact->groups)->toHaveCount(2)
        ->and($contact->groups[0]->id)->toBe(22)
        ->and($contact->groups[0]->name)->toBe('The Group');
});

it('creates a contact without groups', function () use ($contactData) {
    $withoutGroups = array_merge($contactData, ['groups' => []]);
    $client = $this->mockClient(201, $withoutGroups);

    $contact = $client->contacts()->create('Customer', '9609999999');

    expect($contact->groups)->toBeEmpty();
});

it('updates a contact', function () use ($contactData) {
    $updated = array_merge($contactData, ['name' => 'Customer Updated']);
    $client = $this->mockClient(200, $updated);

    $contact = $client->contacts()->update(1232, 'Customer Updated', '9609999999', ['The Group']);

    expect($contact->name)->toBe('Customer Updated');
});

it('deletes a contact', function () {
    $client = $this->mockClient(200, ['message' => 'Contact destroyed successfully.']);

    $result = $client->contacts()->delete(1232);

    expect($result)->toBeTrue();
});
