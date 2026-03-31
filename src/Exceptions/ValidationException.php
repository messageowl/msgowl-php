<?php

namespace MessageOwl\Exceptions;

class ValidationException extends MessageOwlException
{
    public function __construct(
        string $message,
        public readonly ?int $bulkLimit = null,
    ) {
        parent::__construct($message);
    }
}
