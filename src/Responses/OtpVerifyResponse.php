<?php

namespace MessageOwl\Responses;

class OtpVerifyResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $phoneNumber,
        public readonly bool $status,
        public readonly string $timestamp,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            phoneNumber: $data['phone_number'],
            status: $data['status'],
            timestamp: $data['timestamp'],
        );
    }
}
