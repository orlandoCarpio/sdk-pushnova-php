<?php

declare(strict_types=1);

namespace PushNova\Exceptions;

class RateLimitException extends PushNovaException
{
    public function __construct(string $message = 'Rate limit exceeded. Please retry later.', ?array $body = null, ?\Throwable $previous = null)
    {
        parent::__construct($message, 429, $body, $previous);
    }
}
