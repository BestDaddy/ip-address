<?php


namespace App\Http\Request\Api;


use App\Http\Request\ApiBaseRequest;

class IPAddressStoreApiRequest extends ApiBaseRequest
{
    public function injectedRules()
    {
        return [
            'ip'        => ['required', 'unique:ip_addresses,ip,'.$this->id, 'ipv4'],
            'country'   => ['required'],
            'city'      => ['required'],
        ];
    }
}
