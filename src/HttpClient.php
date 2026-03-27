<?php

declare(strict_types=1);

namespace PushNova;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use PushNova\Exceptions\AuthenticationException;
use PushNova\Exceptions\ForbiddenException;
use PushNova\Exceptions\NotFoundException;
use PushNova\Exceptions\PushNovaException;
use PushNova\Exceptions\RateLimitException;
use PushNova\Exceptions\ServerException;
use PushNova\Exceptions\ValidationException;

class HttpClient
{
    private Client $client;
    private int $retries;

    public function __construct(string $apiKey, string $baseUrl, int $timeout, int $retries)
    {
        $this->retries = $retries;
        $this->client = new Client([
            'base_uri' => rtrim($baseUrl, '/') . '/',
            'timeout' => $timeout,
            'headers' => [
                'X-PushNova-Key' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * @return array<string, mixed>
     * @throws PushNovaException
     */
    public function get(string $path, array $query = []): array
    {
        return $this->request('GET', $path, ['query' => $query]);
    }

    /**
     * @return array<string, mixed>
     * @throws PushNovaException
     */
    public function post(string $path, array $data = []): array
    {
        return $this->request('POST', $path, ['json' => $data]);
    }

    /**
     * @return array<string, mixed>
     * @throws PushNovaException
     */
    public function put(string $path, array $data = []): array
    {
        return $this->request('PUT', $path, ['json' => $data]);
    }

    /**
     * @throws PushNovaException
     */
    public function delete(string $path): void
    {
        $this->request('DELETE', $path);
    }

    /**
     * @return array<string, mixed>
     * @throws PushNovaException
     */
    private function request(string $method, string $path, array $options = []): array
    {
        $attempt = 0;
        $maxAttempts = $this->retries + 1;

        while (true) {
            $attempt++;
            try {
                $response = $this->client->request($method, $path, $options);
                $statusCode = $response->getStatusCode();

                if ($statusCode === 204) {
                    return [];
                }

                $body = $response->getBody()->getContents();

                return json_decode($body, true, 512, JSON_THROW_ON_ERROR) ?? [];
            } catch (ConnectException $e) {
                if ($attempt >= $maxAttempts) {
                    throw new PushNovaException(
                        'Connection failed: ' . $e->getMessage(),
                        0,
                        null,
                        $e
                    );
                }
                $this->sleep($attempt);
            } catch (RequestException $e) {
                $response = $e->getResponse();

                if ($response === null) {
                    if ($attempt >= $maxAttempts) {
                        throw new PushNovaException(
                            'Request failed: ' . $e->getMessage(),
                            0,
                            null,
                            $e
                        );
                    }
                    $this->sleep($attempt);
                    continue;
                }

                $statusCode = $response->getStatusCode();
                $bodyContents = $response->getBody()->getContents();
                $body = [];

                try {
                    $body = json_decode($bodyContents, true, 512, JSON_THROW_ON_ERROR) ?? [];
                } catch (\JsonException) {
                    // Response body is not JSON
                }

                $message = $body['message'] ?? $e->getMessage();

                // Retry on 5xx
                if ($statusCode >= 500 && $attempt < $maxAttempts) {
                    $this->sleep($attempt);
                    continue;
                }

                throw $this->mapException($statusCode, $message, $body, $e);
            } catch (\JsonException $e) {
                throw new PushNovaException(
                    'Failed to decode response JSON: ' . $e->getMessage(),
                    0,
                    null,
                    $e
                );
            }
        }
    }

    private function mapException(int $statusCode, string $message, array $body, \Throwable $previous): PushNovaException
    {
        return match ($statusCode) {
            401 => new AuthenticationException($message, $body, $previous),
            403 => new ForbiddenException($message, $body, $previous),
            404 => new NotFoundException($message, $body, $previous),
            422 => new ValidationException($message, $body['errors'] ?? [], $body, $previous),
            429 => new RateLimitException($message, $body, $previous),
            default => $statusCode >= 500
                ? new ServerException($message, $statusCode, $body, $previous)
                : new PushNovaException($message, $statusCode, $body, $previous),
        };
    }

    private function sleep(int $attempt): void
    {
        $delayMs = (int) (min(100 * (2 ** ($attempt - 1)), 5000));
        usleep($delayMs * 1000);
    }
}
