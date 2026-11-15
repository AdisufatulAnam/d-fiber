<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        $this->forceHttpsWhenAppUrlIsHttps();

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    /**
     * Paksa skema https untuk semua URL yang dihasilkan Laravel
     * (route, asset, redirect) bila APP_URL memakai https dan host
     * request sama dengan host APP_URL.
     *
     * Berguna saat aplikasi berada di balik reverse proxy sehingga
     * request asli terlihat sebagai http oleh PHP. Host dicek agar
     * development lokal (http://localhost) tidak ikut dipaksa https.
     */
    protected function forceHttpsWhenAppUrlIsHttps(): void
    {
        $appUrl = (string) config('app.url');

        if (! str_starts_with($appUrl, 'https://')) {
            return;
        }

        $appHost = parse_url($appUrl, PHP_URL_HOST);

        if ($appHost !== null && request()->getHost() === $appHost) {
            URL::forceScheme('https');
        }
    }
}
