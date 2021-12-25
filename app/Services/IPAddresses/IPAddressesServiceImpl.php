<?php


namespace App\Services\IPAddresses;


use App\Models\IPAddress;
use App\Services\BaseServiceImpl;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class IPAddressesServiceImpl extends BaseServiceImpl implements IPAddressesService
{
    public function __construct(IPAddress $model)
    {
        parent::__construct($model);
    }

    public function findIP($ip)
    {
        $error = Validator::make(['ip' => $ip], array(
            'ip'        => ['ipv4'],
        ));

        if($error->fails())
            abort(404);

        $ip_address = $this->firstWhere(['ip' => $ip]);

        if(is_null($ip_address)) {
            $res = $this->getExternalIPInfo($ip);

            return $this->updateOrCreate([
                'ip'      => $ip,
            ], [
                'country' => data_get($res, 'country', 'Not found'),
                'city'    => data_get($res, 'city', 'Not found'),
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
