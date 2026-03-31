<?php

namespace MessageOwl\Resources;

use MessageOwl\Config;
use MessageOwl\Http\HttpClient;
use MessageOwl\Responses\MessageDetailResponse;
use MessageOwl\Responses\MessageListItemResponse;
use MessageOwl\Responses\MessageResponse;

class Message
{
    private string|array|null $recipients = null;
    private ?string $senderId = null;
    private ?string $body = null;

    public function __construct(private readonly HttpClient $http)
    {
    }

    public function to(string|array $recipients): static
    {
        $this->recipients = $recipients;

        return $this;
    }

    public function from(string $senderId): static
    {
        $this->senderId = $senderId;

        return $this;
    }

    public function body(string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function send(): MessageResponse
    {
        $data = $this->http->post(Config::REST_BASE_URL . '/messages', [
            'recipients' => $this->recipients,
            'sender_id'  => $this->senderId,
            'body'       => $this->body,
        ]);

        return MessageResponse::fromArray($data);
    }

    /**
     * @return MessageListItemResponse[]
     */
    public function all(): array
    {
        $data = $this->http->get(Config::REST_BASE_URL . '/messages');

        return array_map(fn (array $item) => MessageListItemResponse::fromArray($item), $data);
    }

    public function find(int $id): MessageDetailResponse
    {
        $data = $this->http->get(Config::REST_BASE_URL . '/messages/' . $id);

        return MessageDetailResponse::fromArray($data);
    }
}
