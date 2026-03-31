<?php

namespace MessageOwl\Exceptions;

class RateLimitException extends MessageOwlException
{
    public function __construct(
        string $message,
        public readonly int $retryAfter,
        public readonly ?int $rateLimitLimit = null,
        public readonly ?int $rateLimitRemaining = null,
        public readonly ?int $rateLimitReset = null,
    ) {
        parent::__construct($message);
    }
}
