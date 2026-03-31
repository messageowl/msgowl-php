<?php

namespace MessageOwl\Resources;

use MessageOwl\Config;
use MessageOwl\Http\HttpClient;
use MessageOwl\Responses\ContactListResponse;
use MessageOwl\Responses\ContactResponse;

class Contact
{
    public function __construct(private readonly HttpClient $http)
    {
    }

    public function all(int $page = 1): ContactListResponse
    {
        $data = $this->http->get(Config::REST_BASE_URL . '/contacts', ['page' => $page]);

        return ContactListResponse::fromArray($data);
    }

    public function create(string $name, string $number, array $groupNames = []): ContactResponse
    {
        $body = [
            'name'   => $name,
            'number' => $number,
        ];

        if ($groupNames !== []) {
            $body['group_names'] = $groupNames;
        }

        $data = $this->http->post(Config::REST_BASE_URL . '/contacts', $body);

        return ContactResponse::fromArray($data);
    }

    public function update(int $id, string $name, string $number, array $groupNames = []): ContactResponse
    {
        $body = [
            'name'   => $name,
            'number' => $number,
        ];

        if ($groupNames !== []) {
            $body['group_names'] = $groupNames;
        }

        $data = $this->http->put(Config::REST_BASE_URL . '/contacts/' . $id, $body);

        return ContactResponse::fromArray($data);
    }

    public function delete(int $id): bool
    {
        return $this->http->delete(Config::REST_BASE_URL . '/contacts/' . $id);
    }
}
