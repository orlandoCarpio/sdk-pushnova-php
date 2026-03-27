<?php

declare(strict_types=1);

namespace PushNova\Resources;

use PushNova\HttpClient;

class NotificationsResource
{
    public function __construct(private readonly HttpClient $http)
    {
    }

    /**
     * Send a notification to specific users.
     *
     * @param array{
     *     title: string,
     *     body: string,
     *     icon_url?: string,
     *     image_url?: string,
     *     launch_url?: string,
     *     data?: array<string, mixed>,
     *     priority?: string,
     *     ttl?: int,
     *     subscriber_ids?: string[],
     *     external_ids?: string[],
     *     segment_id?: string,
     *     tags?: string[]
     * } $params
     * @return array{id: string, recipients: int, status: string}
     */
    public function send(array $params): array
    {
        return $this->http->post('sdk/notifications', $params);
    }
}
