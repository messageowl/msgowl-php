<?php

namespace MessageOwl\Resources;

use MessageOwl\Config;
use MessageOwl\Http\HttpClient;
use MessageOwl\Responses\SenderIdResponse;

class SenderId
{
    public function __construct(private readonly HttpClient $http)
    {
    }

    /**
     * @return SenderIdResponse[]
     */
    public function all(): array
    {
        $data = $this->http->get(Config::REST_BASE_URL . '/sms_headers');

        return array_map(fn (array $item) => SenderIdResponse::fromArray($item), $data);
    }
}
