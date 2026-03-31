<?php

namespace MessageOwl\Responses;

class MessageListItemResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $smsHeader,
        public readonly int $status,
        public readonly string $createdAt,
        public readonly ?string $body,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            smsHeader: $data['sms_header'],
            status: $data['status'],
            createdAt: $data['created_at'],
            body: $data['body'] ?? null,
        );
    }
}
