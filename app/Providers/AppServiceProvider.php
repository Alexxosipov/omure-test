<?php

namespace App\Providers;

use App\Services\Contacts\Providers\ContactProviderInterface;
use App\Services\Contacts\Providers\SalesforceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application Services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ContactProviderInterface::class, SalesforceProvider::class);
    }

    /**
     * Bootstrap any application Services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
