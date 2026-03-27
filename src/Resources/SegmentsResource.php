<?php

declare(strict_types=1);

namespace PushNova\Resources;

use PushNova\HttpClient;

class SegmentsResource
{
    public function __construct(private readonly HttpClient $http)
    {
    }

    /**
     * List all segments.
     *
     * @return array{data: array<int, array<string, mixed>>}
     */
    public function list(): array
    {
        return $this->http->get('sdk/segments');
    }

    /**
     * Create a new segment.
     *
     * @param array{
     *     name: string,
     *     description?: string,
     *     type: string,
     *     rules?: array<int, array<string, mixed>>
     * } $params
     * @return array<string, mixed>
     */
    public function create(array $params): array
    {
        return $this->http->post('sdk/segments', $params);
    }

    /**
     * Get a single segment by ID.
     *
     * @return array<string, mixed>
     */
    public function get(string $id): array
    {
        return $this->http->get("sdk/segments/{$id}");
    }

    /**
     * Update a segment by ID.
     *
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function update(string $id, array $params): array
    {
        return $this->http->put("sdk/segments/{$id}", $params);
    }

    /**
     * Delete a segment by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("sdk/segments/{$id}");
    }
}
