<?php

declare(strict_types=1);

namespace PushNova\Resources;

use PushNova\HttpClient;

class AppResource
{
    public function __construct(private readonly HttpClient $http)
    {
    }

    /**
     * Get the current app details.
     *
     * @return array<string, mixed>
     */
    public function get(): array
    {
        return $this->http->get('sdk/app');
    }

    /**
     * Update the current app.
     *
     * @param array{name?: string, website_url?: string} $params
     * @return array<string, mixed>
     */
    public function update(array $params): array
    {
        return $this->http->put('sdk/app', $params);
    }
}
