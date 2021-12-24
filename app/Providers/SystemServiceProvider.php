<?php


namespace App\Providers;


use App\Services\IPAddresses\IPAddressesService;
use App\Services\IPAddresses\IPAddressesServiceImpl;
use Illuminate\Support\ServiceProvider;

class SystemServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(IPAddressesService::class, IPAddressesServiceImpl::class);
    }

    public function boot()
    {

    }
}
