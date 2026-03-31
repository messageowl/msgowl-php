<?php

namespace MessageOwl;

class Config
{
    public const REST_BASE_URL = 'https://rest.msgowl.com';
    public const OTP_BASE_URL = 'https://otp.msgowl.com';

    public function __construct(
        public readonly string $apiKey,
        public readonly int $timeout = 30,
        public readonly bool $useQueryAuth = false,
    ) {
    }
}
