<?php

namespace MessageOwl\Resources;

use MessageOwl\Config;
use MessageOwl\Http\HttpClient;
use MessageOwl\Responses\GroupDetailResponse;
use MessageOwl\Responses\GroupResponse;

class Group
{
    public function __construct(private readonly HttpClient $http)
    {
    }

    /**
     * @return GroupResponse[]
     */
    public function all(): array
    {
        $data = $this->http->get(Config::REST_BASE_URL . '/groups');

        return array_map(fn (array $item) => GroupResponse::fromArray($item), $data);
    }

    public function find(int $id): GroupDetailResponse
    {
        $data = $this->http->get(Config::REST_BASE_URL . '/groups/' . $id);

        return GroupDetailResponse::fromArray($data);
    }

    public function create(string $name): GroupResponse
    {
        $data = $this->http->post(Config::REST_BASE_URL . '/groups', ['name' => $name]);

        return GroupResponse::fromArray($data);
    }

    public function update(int $id, string $name): GroupResponse
    {
        $data = $this->http->put(Config::REST_BASE_URL . '/groups/' . $id, ['name' => $name]);

        return GroupResponse::fromArray($data);
    }

    public function delete(int $id): bool
    {
        return $this->http->delete(Config::REST_BASE_URL . '/groups/' . $id);
    }
}
