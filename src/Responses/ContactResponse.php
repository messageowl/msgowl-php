<?php

namespace MessageOwl\Responses;

class ContactResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $number,
        public readonly string $createdAt,
        public readonly string $updatedAt,
        /** @var ContactGroupResponse[] */
        public readonly array $groups,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            number: $data['number'],
            createdAt: $data['created_at'],
            updatedAt: $data['updated_at'],
            groups: array_map(
                fn (array $g) => ContactGroupResponse::fromArray($g),
                $data['groups'] ?? [],
            ),
        );
    }
}
