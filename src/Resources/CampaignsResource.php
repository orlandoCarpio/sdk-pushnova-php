<?php

declare(strict_types=1);

namespace PushNova\Resources;

use PushNova\HttpClient;

class CampaignsResource
{
    public function __construct(private readonly HttpClient $http)
    {
    }

    /**
     * List campaigns with optional filters.
     *
     * @param array{
     *     status?: string,
     *     per_page?: int,
     *     page?: int
     * } $params
     * @return array{data: array<int, array<string, mixed>>, meta: array{current_page: int, per_page: int, total: int, last_page: int}}
     */
    public function list(array $params = []): array
    {
        return $this->http->get('sdk/campaigns', $params);
    }

    /**
     * Create a new campaign.
     *
     * @param array{
     *     name: string,
     *     title: string,
     *     body: string,
     *     icon_url?: string,
     *     image_url?: string,
     *     launch_url?: string,
     *     data?: array<string, mixed>,
     *     audience_type: string,
     *     audience_config?: array<string, mixed>,
     *     scheduled_at?: string
     * } $params
     * @return array<string, mixed>
     */
    public function create(array $params): array
    {
        return $this->http->post('sdk/campaigns', $params);
    }

    /**
     * Get a single campaign by ID.
     *
     * @return array<string, mixed>
     */
    public function get(string $id): array
    {
        return $this->http->get("sdk/campaigns/{$id}");
    }

    /**
     * Update a campaign by ID.
     *
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function update(string $id, array $params): array
    {
        return $this->http->put("sdk/campaigns/{$id}", $params);
    }

    /**
     * Delete a campaign by ID.
     */
    public function delete(string $id): void
    {
        $this->http->delete("sdk/campaigns/{$id}");
    }

    /**
     * Send a campaign immediately or schedule it.
     *
     * @param array{scheduled_at?: string} $params
     * @return array{id: string, status: string, scheduled_at: ?string, message: string}
     */
    public function send(string $id, array $params = []): array
    {
        return $this->http->post("sdk/campaigns/{$id}/send", $params);
    }

    /**
     * Cancel a scheduled or sending campaign.
     *
     * @return array<string, mixed>
     */
    public function cancel(string $id): array
    {
        return $this->http->post("sdk/campaigns/{$id}/cancel");
    }
}
