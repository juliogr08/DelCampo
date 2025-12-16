<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mime\MimeTypes;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Force register MIME guesser to fix "no guessers available" error
        if (class_exists(MimeTypes::class)) {
            $mimeTypes = new MimeTypes();
            MimeTypes::setDefault($mimeTypes);
        }
    }
}

