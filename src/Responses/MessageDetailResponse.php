<?php

namespace MessageOwl\Responses;

class MessageDetailResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $smsHeader,
        public readonly int $status,
        public readonly string $createdAt,
        public readonly int $accountId,
        public readonly ?string $body,
        /** @var RecipientResponse[] */
        public readonly array $recipients,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            smsHeader: $data['sms_header'],
            status: $data['status'],
            createdAt: $data['created_at'],
            accountId: $data['account_id'],
            body: $data['body'] ?? null,
            recipients: array_map(
                fn (array $r) => RecipientResponse::fromArray($r),
                $data['recipients'] ?? [],
            ),
        );
    }
}
