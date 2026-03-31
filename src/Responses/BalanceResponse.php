<?php

namespace MessageOwl\Responses;

class BalanceResponse
{
    public function __construct(
        public readonly string $balance,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            balance: $data['balance'],
        );
    }
}
