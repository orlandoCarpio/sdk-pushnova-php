<?php

declare(strict_types=1);

namespace PushNova\Resources;

use PushNova\HttpClient;

class SubscribersResource
{
    public function __construct(private readonly HttpClient $http)
    {
    }

    /**
     * List subscribers with optional filters.
     *
     * @param array{
     *     platform?: string,
     *     is_subscribed?: bool,
     *     search?: string,
     *     tag_key?: string,
     *     tag_value?: string,
     *     per_page?: int,
     *     page?: int
     * } $params
     * @return array{data: array<int, array<string, mixed>>, meta: array{current_page: int, per_page: int, total: int, last_page: int}}
     */
    public function list(array $params = []): array
    {
        return $this->http->get('sdk/subscribers', $params);
    }

    /**
     * Get a single subscriber by ID.
     *
     * @return array<string, mixed>
     */
    public function get(string $id): array
    {
        return $this->http->get("sdk/subscribers/{$id}");
    }

    /**
     * Delete a subscriber by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("sdk/subscribers/{$id}");
    }
}
