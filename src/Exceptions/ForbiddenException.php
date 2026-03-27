<?php

declare(strict_types=1);

namespace PushNova\Exceptions;

class ForbiddenException extends PushNovaException
{
    public function __construct(string $message = 'Access denied.', ?array $body = null, ?\Throwable $previous = null)
    {
        parent::__construct($message, 403, $body, $previous);
    }
}
