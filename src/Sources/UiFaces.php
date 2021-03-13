<?php

namespace CubeKode\Faker\Sources;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;

abstract class UiFaces
{
    /**
     * Cached requests
     *
     * @var array
     */
    protected static $cached = [];

    /**
     * Get HTTP client for Pixabay requests
     *
     * @return GuzzleClient Guzzle client instance
     */
    protected static function getClient(): GuzzleClient
    {
        return new GuzzleClient([
            'base_uri' => 'https://uifaces.co',
            'headers' => [
                'X-API-KEY' => config('faker-images.uifaces.key'),
            ],
        ]);
    }

    /**
     * Send get request to server
     *
     * @param  array    $options Query params to send
     * @return Response          PSR7 reponse object
     */
    protected static function sendRequest(array $options): Response
    {
        return self::getClient()->get('/api', ['query' => $options]);
    }

    /**
     * Send API request and get response
     *
     * @param  array  $options API params
     * @return array           API response
     */
    protected static function request(array $options): array
    {
        return json_decode((string) self::sendRequest($options)->getBody(), true);
    }

    /**
     * Get cache key identifier for a given request
     *
     * @param boolean $male
     * @param integer $from
     * @param integer $to
     * @return string
     */
    protected static function getCachedKey(bool $male = null, int $from = null, int $to = null): string
    {
        return implode('-', [$male ? 1 : 0, $from, $to]);
    }

    /**
     * Store cache
     *
     * @param string $key
     * @param array $values
     * @return void
     */
    protected static function setCache(string $key, array $values): void
    {
        self::$cached[$key] = $values;
    }

    /**
     * Check if a given request is cached
     *
     * @param string $key
     * @return boolean
     */
    protected static function isCached(string $key): bool
    {
        return count(self::$cached[$key] ?? []) > 0;
    }

    /**
     * Send request to the random faces endpoint
     *
     * @param  bool|null $male Male or female, or null to random result
     * @param  int|null  $from Minimum age
     * @param  int|null  $to   Maximum age
     * @return string          URL of the first face result
     */
    public static function getFace(bool $male = null, int $from = null, int $to = null): string
    {
        $key = self::getCachedKey($male, $from, $to);

        if (!self::isCached($key)) {
            $response = self::request([
                'limit' => 30,
                'random' => true,
                'gender' => $male === null ? '' : [($male ? 'male' : 'female')],
                'provider' => [1],
                'from_age' => $from ?? 18,
                'to_age' => $to ?? 40,
            ]);

            self::setCache($key, $response);
        }

        return array_pop(self::$cached[$key])['photo'];
    }
}
