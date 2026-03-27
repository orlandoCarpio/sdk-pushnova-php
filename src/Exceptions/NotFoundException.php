<?php

declare(strict_types=1);

namespace PushNova\Exceptions;

class NotFoundException extends PushNovaException
{
    public function __construct(string $message = 'Resource not found.', ?array $body = null, ?\Throwable $previous = null)
    {
        parent::__construct($message, 404, $body, $previous);
    }
}
