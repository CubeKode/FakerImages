<?php

namespace CubeKode\Faker\Sources;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Arr;

abstract class UiFaces
{
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
     * Send request to the random faces endpoint
     *
     * @param  bool|null $male Male or female, or null to random result
     * @param  int|null  $from Minimum age
     * @param  int|null  $to   Maximum age
     * @return string          URL of the first face result
     */
    public static function getFace(bool $male = null, int $from = null, int $to = null): string
    {
        $response = self::request([
            'limit' => 1,
            'random' => true,
            'gender' => $male === null ? '' : [($male ? 'male' : 'female')],
            'provider' => [1],
            'from_age' => $from ?? 18,
            'to_age' => $to ?? 40,
        ]);

        return Arr::get($response, '0.photo');
    }
}
