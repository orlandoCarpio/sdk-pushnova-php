<?php

declare(strict_types=1);

namespace PushNova\Exceptions;

class ValidationException extends PushNovaException
{
    /** @var array<string, string[]> */
    private array $validationErrors;

    /**
     * @param array<string, string[]> $errors
     */
    public function __construct(string $message = 'Validation failed.', array $errors = [], ?array $body = null, ?\Throwable $previous = null)
    {
        $this->validationErrors = $errors;
        parent::__construct($message, 422, $body, $previous);
    }

    /**
     * @return array<string, string[]>
     */
    public function errors(): array
    {
        return $this->validationErrors;
    }
}
