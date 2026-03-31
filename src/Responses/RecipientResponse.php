<?php

namespace MessageOwl\Responses;

use MessageOwl\Enums\DeliveryStatus;
use MessageOwl\Enums\SmsStatus;

class RecipientResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $phoneNumber,
        public readonly DeliveryStatus $deliveryStatus,
        public readonly SmsStatus $smsStatus,
        public readonly ?string $deliveredOn,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            phoneNumber: $data['phone_number'],
            deliveryStatus: DeliveryStatus::from($data['delivery_status']),
            smsStatus: SmsStatus::from($data['sms_status']),
            deliveredOn: $data['delivered_on'] ?? null,
        );
    }
}
