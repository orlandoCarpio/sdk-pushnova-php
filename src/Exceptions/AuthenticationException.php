<?php

declare(strict_types=1);

namespace PushNova\Exceptions;

class AuthenticationException extends PushNovaException
{
    public function __construct(string $message = 'Authentication failed. Check your API key.', ?array $body = null, ?\Throwable $previous = null)
    {
        parent::__construct($message, 401, $body, $previous);
    }
}
