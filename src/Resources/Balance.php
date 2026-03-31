<?php

namespace MessageOwl\Resources;

use MessageOwl\Config;
use MessageOwl\Http\HttpClient;
use MessageOwl\Responses\BalanceResponse;

class Balance
{
    public function __construct(private readonly HttpClient $http)
    {
    }

    public function get(): BalanceResponse
    {
        $data = $this->http->get(Config::REST_BASE_URL . '/balance');

        return BalanceResponse::fromArray($data);
    }
}
