<?php

namespace CubeKode\Faker;

use Faker\Generator as FakerGenerator;
use Illuminate\Support\ServiceProvider;

class FakerImagesProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/configs/faker-images.php',
            'faker-images'
        );

        $this->app->extend(FakerGenerator::class, function ($service) {
            $service->addProvider(new Providers\FakerFace($service));
            $service->addProvider(new Providers\FakerPicture($service));
            return $service;
        });
    }
}
