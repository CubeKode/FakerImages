<?php

namespace CubeKode\Faker\Providers;

use CubeKode\Faker\Sources\Pixabay;
use Faker\Provider\Base as BaseProvider;

class FakerPicture extends BaseProvider
{
    /**
     * Get random picture url
     *
     * @param  string|null $query    Search for files
     * @param  string|null $category Picture category
     * @param  int|null    $width    Minimum width
     * @param  int|null    $height   Minimum height
     * @return string                File path
     */
    public static function pictureUrl(
        string $query = null,
        string $category = null,
        int $width = null,
        int $height = null
    ): string {
        return Pixabay::getPicture($query, $category, $width, $height);
    }

    /**
     * Get random picture file
     *
     * @param  string|null $query    Search for files
     * @param  string|null $category Picture category
     * @param  int|null    $width    Minimum width
     * @param  int|null    $height   Minimum height
     * @return string                File path
     */
    public static function picture(
        string $query = null,
        string $category = null,
        int $width = null,
        int $height = null
    ): string {
        file_put_contents(
            $filename = tempnam(sys_get_temp_dir(), 'picture'),
            file_get_contents(
                self::pictureUrl($query, $category, $width, $height)
            )
        );

        return $filename;
    }
}
