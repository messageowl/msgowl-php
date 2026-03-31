<?php

namespace MessageOwl\Responses;

class SenderIdResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $purpose,
        public readonly string $status,
        public readonly string $createdAt,
        public readonly string $updatedAt,
        public readonly ?string $remarks,
        public readonly ?string $handledAt,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            purpose: $data['purpose'] ?? null,
            status: $data['status'],
            createdAt: $data['created_at'],
            updatedAt: $data['updated_at'],
            remarks: $data['remarks'] ?? null,
            handledAt: $data['handled_at'] ?? null,
        );
    }
}
