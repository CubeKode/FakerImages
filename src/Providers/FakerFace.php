<?php

namespace CubeKode\Faker\Providers;

use CubeKode\Faker\Sources\UiFaces;
use Faker\Provider\Base as BaseProvider;
use Intervention\Image\ImageManagerStatic as Image;

class FakerFace extends BaseProvider
{
    /**
     * Get random avatar url
     *
     * @param  bool|null $male  Male of Female avatar, or null for random
     * @param  int|null  $from  Minimum age
     * @param  int|null  $to    Maximum age
     * @return string           Avatar url
     */
    public static function avatarUrl(
        bool $male = null,
        int $from = null,
        int $to = null
    ): string {
        return UiFaces::getFace($male, $from, $to);
    }

    /**
     * Get random avatar file
     *
     * @param  bool|null $male  Male of Female avatar, or null for random
     * @param  int|null  $from  Minimum age
     * @param  int|null  $to    Maximum age
     * @return string           Avatar file
     */
    public static function avatar(
        bool $male = null,
        int $from = null,
        int $to = null
    ): string {
        file_put_contents(
            $filename = tempnam(sys_get_temp_dir(), 'avatar'),
            file_get_contents(self::avatarUrl($male, $from, $to))
        );

        return $filename;
    }

    /**
     * Get random avatar file with square format
     *
     * @param  bool|null $male  Male of Female avatar, or null for random
     * @param  int|null  $from  Minimum age
     * @param  int|null  $to    Maximum age
     * @return string           Avatar file
     */
    public static function avatarSquare(
        bool $male = null,
        int $from = null,
        int $to = null
    ): string {
        $callback = function ($image) {
            $image->upsize();
        };

        Image::make($filename = self::avatar($male, $from, $to))
            ->fit(1080, 1080, $callback, 'top')
            ->save($filename);

        return $filename;
    }
}
