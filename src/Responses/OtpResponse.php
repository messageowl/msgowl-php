<?php

namespace MessageOwl\Responses;

class OtpResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $phoneNumber,
        public readonly string $timestamp,
        public readonly int $messageId,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            phoneNumber: $data['phone_number'],
            timestamp: $data['timestamp'],
            messageId: $data['message_id'],
        );
    }
}
