<?php

namespace App\Providers;

use Laravel\Sanctum\Sanctum;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        Validator::extend('gmail_domain', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/@gmail\.com$/i', $value) || preg_match('/@googlemail\.com$/i', $value);
        });
    
        Validator::replacer('gmail_domain', function ($message, $attribute, $rule, $parameters) {
            return 'The email must be a Gmail address.';
        });

    }
}
