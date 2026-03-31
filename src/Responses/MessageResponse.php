<?php

namespace MessageOwl\Responses;

class MessageResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $message,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            message: $data['message'],
        );
    }
}
