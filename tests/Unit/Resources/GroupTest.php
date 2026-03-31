<?php

use MessageOwl\Responses\GroupDetailResponse;
use MessageOwl\Responses\GroupResponse;

$groupData = [
    'id'         => 22,
    'name'       => 'The Group',
    'account_id' => 1,
    'user_id'    => 1,
    'created_at' => '2020-01-12T15:05:32.594+05:00',
    'updated_at' => '2020-01-12T15:05:32.594+05:00',
];

it('lists all groups', function () use ($groupData) {
    $client = $this->mockClient(200, [$groupData]);

    $groups = $client->groups()->all();

    expect($groups)->toHaveCount(1)
        ->and($groups[0])->toBeInstanceOf(GroupResponse::class)
        ->and($groups[0]->id)->toBe(22)
        ->and($groups[0]->name)->toBe('The Group')
        ->and($groups[0]->accountId)->toBe(1)
        ->and($groups[0]->userId)->toBe(1);
});

it('fetches a group by id with contacts', function () use ($groupData) {
    $client = $this->mockClient(200, array_merge($groupData, [
        'contacts' => [
            ['id' => 1232, 'name' => 'Customer', 'number' => '9609999999'],
        ],
    ]));

    $group = $client->groups()->find(22);

    expect($group)->toBeInstanceOf(GroupDetailResponse::class)
        ->and($group->contacts)->toHaveCount(1)
        ->and($group->contacts[0]->id)->toBe(1232)
        ->and($group->contacts[0]->name)->toBe('Customer')
        ->and($group->contacts[0]->number)->toBe('9609999999');
});

it('fetches a group with empty contacts', function () use ($groupData) {
    $client = $this->mockClient(200, array_merge($groupData, ['contacts' => []]));

    $group = $client->groups()->find(22);

    expect($group->contacts)->toBeEmpty();
});

it('creates a group', function () use ($groupData) {
    $client = $this->mockClient(201, $groupData);

    $group = $client->groups()->create('The Group');

    expect($group)->toBeInstanceOf(GroupResponse::class)
        ->and($group->name)->toBe('The Group');
});

it('updates a group', function () use ($groupData) {
    $updated = array_merge($groupData, ['name' => 'The New Group']);
    $client = $this->mockClient(200, $updated);

    $group = $client->groups()->update(22, 'The New Group');

    expect($group->name)->toBe('The New Group');
});

it('deletes a group', function () {
    $client = $this->mockClient(200, ['message' => 'Group destroyed successfully.']);

    $result = $client->groups()->delete(22);

    expect($result)->toBeTrue();
});
