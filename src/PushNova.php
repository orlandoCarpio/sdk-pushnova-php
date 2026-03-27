<?php

declare(strict_types=1);

namespace PushNova;

use PushNova\Resources\AnalyticsResource;
use PushNova\Resources\AppResource;
use PushNova\Resources\CampaignsResource;
use PushNova\Resources\NotificationsResource;
use PushNova\Resources\SegmentsResource;
use PushNova\Resources\SubscribersResource;

class PushNova
{
    public readonly NotificationsResource $notifications;
    public readonly SubscribersResource $subscribers;
    public readonly SegmentsResource $segments;
    public readonly CampaignsResource $campaigns;
    public readonly AnalyticsResource $analytics;
    public readonly AppResource $app;

    /**
     * @param string $apiKey Your PushNova API key (e.g. "pn_live_xxxx_xxxxxxxx")
     * @param array{base_url?: string, timeout?: int, retries?: int} $options
     */
    public function __construct(string $apiKey, array $options = [])
    {
        $baseUrl = $options['base_url'] ?? 'https://api.pushnova.com';
        $timeout = $options['timeout'] ?? 30;
        $retries = $options['retries'] ?? 2;

        $httpClient = new HttpClient($apiKey, $baseUrl, $timeout, $retries);

        $this->notifications = new NotificationsResource($httpClient);
        $this->subscribers = new SubscribersResource($httpClient);
        $this->segments = new SegmentsResource($httpClient);
        $this->campaigns = new CampaignsResource($httpClient);
        $this->analytics = new AnalyticsResource($httpClient);
        $this->app = new AppResource($httpClient);
    }
}
