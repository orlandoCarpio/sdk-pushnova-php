<?php

declare(strict_types=1);

namespace PushNova\Resources;

use PushNova\HttpClient;

class AnalyticsResource
{
    public function __construct(private readonly HttpClient $http)
    {
    }

    /**
     * Get analytics overview for a given period.
     *
     * @param string $period One of: "7d", "30d", "90d"
     * @return array<string, mixed>
     */
    public function overview(string $period = '7d'): array
    {
        return $this->http->get('sdk/analytics/overview', ['period' => $period]);
    }

    /**
     * Get per-campaign analytics.
     *
     * @param array{per_page?: int, page?: int} $params
     * @return array{data: array<int, array<string, mixed>>, meta: array{current_page: int, per_page: int, total: int, last_page: int}}
     */
    public function campaigns(array $params = []): array
    {
        return $this->http->get('sdk/analytics/campaigns', $params);
    }
}
