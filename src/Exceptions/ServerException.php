<?php

declare(strict_types=1);

namespace PushNova\Exceptions;

class ServerException extends PushNovaException
{
    public function __construct(string $message = 'Server error.', int $statusCode = 500, ?array $body = null, ?\Throwable $previous = null)
    {
        parent::__construct($message, $statusCode, $body, $previous);
    }
}
