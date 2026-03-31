<?php

namespace MessageOwl;

use MessageOwl\Http\HttpClient;
use MessageOwl\Resources\Balance;
use MessageOwl\Resources\Contact;
use MessageOwl\Resources\Group;
use MessageOwl\Resources\Message;
use MessageOwl\Resources\Otp;
use MessageOwl\Resources\SenderId;
use MessageOwl\Responses\BalanceResponse;

class MessageOwl
{
    private readonly HttpClient $http;

    public function __construct(
        string $apiKey,
        int $timeout = 30,
        bool $useQueryAuth = false,
        ?HttpClient $http = null,
    ) {
        $config = new Config($apiKey, $timeout, $useQueryAuth);
        $this->http = $http ?? new HttpClient($config);
    }

    public function message(): Message
    {
        return new Message($this->http);
    }

    public function messages(): Message
    {
        return new Message($this->http);
    }

    public function otp(): Otp
    {
        return new Otp($this->http);
    }

    public function groups(): Group
    {
        return new Group($this->http);
    }

    public function contacts(): Contact
    {
        return new Contact($this->http);
    }

    public function balance(): BalanceResponse
    {
        return (new Balance($this->http))->get();
    }

    public function senderIds(): array
    {
        return (new SenderId($this->http))->all();
    }
}
