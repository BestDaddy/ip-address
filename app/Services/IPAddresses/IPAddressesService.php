<?php


namespace App\Services\IPAddresses;


interface IPAddressesService
{
    public function findIP($ip);

    public function getExternalIPInfo(string $ip);
}
