<?php

namespace MessageOwl\Responses;

class ContactListResponse
{
    public function __construct(
        /** @var ContactListItemResponse[] */
        public readonly array $contacts,
        public readonly int $currentPage,
        public readonly ?int $nextPage,
        public readonly ?int $previousPage,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            contacts: array_map(
                fn (array $c) => ContactListItemResponse::fromArray($c),
                $data['contacts'] ?? [],
            ),
            currentPage: $data['current_page'],
            nextPage: $data['next_page'] ?? null,
            previousPage: $data['previous_page'] ?? null,
        );
    }
}
