<?php

namespace MessageOwl\Responses;

class GroupDetailResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $accountId,
        public readonly int $userId,
        public readonly string $createdAt,
        public readonly string $updatedAt,
        /** @var GroupContactResponse[] */
        public readonly array $contacts,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            accountId: $data['account_id'],
            userId: $data['user_id'],
            createdAt: $data['created_at'],
            updatedAt: $data['updated_at'],
            contacts: array_map(
                fn (array $c) => GroupContactResponse::fromArray($c),
                $data['contacts'] ?? [],
            ),
        );
    }
}
