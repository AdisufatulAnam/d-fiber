<?php

use App\Providers\AppServiceProvider;
use App\Providers\RepositoryServiceProvider;
use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Illuminate\Session\SessionServiceProvider;

return [
    AppServiceProvider::class,
    RepositoryServiceProvider::class,
    AuthServiceProvider::class,
    SessionServiceProvider::class,
    BaseEventServiceProvider::class,
];