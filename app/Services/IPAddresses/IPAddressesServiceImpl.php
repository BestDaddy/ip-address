<?php


namespace App\Services\IPAddresses;


use App\Models\IPAddress;
use App\Services\BaseServiceImpl;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class IPAddressesServiceImpl extends BaseServiceImpl implements IPAddressesService
{
    public function __construct(IPAddress $model)
    {
        parent::__construct($model);
    }

    public function findIP($ip)
    {
        $ip_address = $this->firstWhere(['ip' => $ip]);

        if(is_null($ip_address)) {
            $res = $this->getExternalIPInfo($ip);

            if( data_get($res, 'status') == 'fail')  // they always return status code 200
                return ;

            return $this->updateOrCreate([
                'ip'      => $ip,
            ], [
                'country' => data_get($res, 'country'),
                'city'    => data_get($res, 'city'),
            ]);
        }

        return $ip_address;
    }

    public function getExternalIPInfo(string $ip)
    {
        $response = Http::get('http://ip-api.com/json/' . $ip, []);
        return json_decode($response->body());
    }
}
