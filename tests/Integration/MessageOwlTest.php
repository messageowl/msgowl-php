<?php

use MessageOwl\MessageOwl;

$apiKey = getenv('MESSAGEOWL_API_KEY');

if (! $apiKey) {
    return;
}

$client = new MessageOwl($apiKey);

it('fetches real account balance', function () use ($client) {
    $response = $client->balance();

    expect($response->balance)->toBeString();
})->group('integration');

it('lists real sender ids', function () use ($client) {
    $senderIds = $client->senderIds();

    expect($senderIds)->toBeArray();
})->group('integration');

it('lists real messages', function () use ($client) {
    $messages = $client->messages()->all();

    expect($messages)->toBeArray();
})->group('integration');

it('lists real groups', function () use ($client) {
    $groups = $client->groups()->all();

    expect($groups)->toBeArray();
})->group('integration');

it('lists real contacts', function () use ($client) {
    $contacts = $client->contacts()->all();

    expect($contacts->contacts)->toBeArray();
})->group('integration');
