<?php

namespace MessageOwl\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use MessageOwl\Config;
use MessageOwl\Exceptions\AuthenticationException;
use MessageOwl\Exceptions\MethodNotAllowedException;
use MessageOwl\Exceptions\MessageOwlException;
use MessageOwl\Exceptions\NotFoundException;
use MessageOwl\Exceptions\RateLimitException;
use MessageOwl\Exceptions\RequestTimeoutException;
use MessageOwl\Exceptions\ServerException;
use MessageOwl\Exceptions\ValidationException;
use Psr\Http\Message\ResponseInterface;

class HttpClient
{
    private Client $client;

    public function __construct(private readonly Config $config, ?Client $client = null)
    {
        $this->client = $client ?? new Client([
            'timeout' => $config->timeout,
        ]);
    }

    public function get(string $url, array $query = []): array
    {
        return $this->request('GET', $url, query: $query);
    }

    public function post(string $url, array $body = []): array
    {
        return $this->request('POST', $url, body: $body);
    }

    public function put(string $url, array $body = []): array
    {
        return $this->request('PUT', $url, body: $body);
    }

    public function delete(string $url): bool
    {
        $this->request('DELETE', $url);

        return true;
    }

    private function request(string $method, string $url, array $body = [], array $query = []): array
    {
        $options = $this->buildOptions($body, $query);

        try {
            $response = $this->client->request($method, $url, $options);
        } catch (BadResponseException $e) {
            $this->handleErrorResponse($e->getResponse());
        } catch (ConnectException) {
            throw new RequestTimeoutException('Connection timed out.');
        }

        $statusCode = $response->getStatusCode();

        if ($statusCode === 204) {
            return [];
        }

        return $this->decodeJson($response);
    }

    private function buildOptions(array $body, array $query): array
    {
        $options = [];

        if ($this->config->useQueryAuth) {
            $query['access_key'] = $this->config->apiKey;
        } else {
            $options['headers']['Authorization'] = 'AccessKey ' . $this->config->apiKey;
        }

        if ($query) {
            $options['query'] = $query;
        }

        if ($body) {
            $options['json'] = $body;
        }

        return $options;
    }

    private function handleErrorResponse(ResponseInterface $response): never
    {
        $statusCode = $response->getStatusCode();
        $data = $this->decodeJson($response);
        $message = $data['message'] ?? 'An error occurred.';

        $rateLimitLimit     = $response->hasHeader('RateLimit-Limit') ? (int) $response->getHeaderLine('RateLimit-Limit') : null;
        $rateLimitRemaining = $response->hasHeader('RateLimit-Remaining') ? (int) $response->getHeaderLine('RateLimit-Remaining') : null;
        $rateLimitReset     = $response->hasHeader('RateLimit-Reset') ? (int) $response->getHeaderLine('RateLimit-Reset') : null;

        throw match (true) {
            $statusCode === 401 => new AuthenticationException($message),
            $statusCode === 404 => new NotFoundException($message),
            $statusCode === 405 => new MethodNotAllowedException($message),
            $statusCode === 408 => new RequestTimeoutException($message),
            $statusCode === 422 => new ValidationException($message, isset($data['bulk_limit']) ? (int) $data['bulk_limit'] : null),
            $statusCode === 429 => new RateLimitException(
                $message,
                retryAfter: (int) ($data['retry_after'] ?? $response->getHeaderLine('Retry-After') ?? 0),
                rateLimitLimit: $rateLimitLimit,
                rateLimitRemaining: $rateLimitRemaining,
                rateLimitReset: $rateLimitReset,
            ),
            $statusCode >= 500  => new ServerException($message),
            default             => new MessageOwlException($message),
        };
    }

    private function decodeJson(ResponseInterface $response): array
    {
        $body = (string) $response->getBody();

        if ($body === '') {
            return [];
        }

        return json_decode($body, true) ?? [];
    }
}
