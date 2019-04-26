<?php

namespace CubeKode\Faker\Sources;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Arr;

abstract class Pixabay
{
    /**
     * Get HTTP client for Pixabay requests
     *
     * @return GuzzleClient Guzzle client instance
     */
    protected static function getClient(): GuzzleClient
    {
        return new GuzzleClient([
            'base_uri' => 'https://pixabay.com/',
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
     * Send request to picture search endpoint
     *
     * @param  string|null $query    Query to search category
     * @param  string|null $category Picture category to filter
     * @param  int|null    $width    Minimum picture width
     * @param  int|null    $height   Minimum picture height
     * @return string                URL of the first picture result
     */
    public static function getPicture(
        string $query = null,
        string $category = null,
        int $width = null,
        int $height = null
    ): string {
        $response = self::request([
            'q' => $query ?? '',
            'key' => config('faker-images.pixabay.key'),
            'image_type' => 'photo',
            'safesearch' => true,
            'per_page' => 3,
            'min_width' => $width ?? 0,
            'min_height' => $height ?? 0,
            'category' => $category ?? '',
        ]);

        return Arr::get($response, 'hits.0.largeImageURL');
    }
}
