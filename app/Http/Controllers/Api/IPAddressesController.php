<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Http\Request\Api\IPAddressStoreApiRequest;
use App\Models\IPAddress;
use App\Services\IPAddresses\IPAddressesService;
use Illuminate\Http\Request;

class IPAddressesController extends ApiBaseController
{
    private $ipAddressesService;

    public function __construct(IPAddressesService $ipAddressesService)
    {
        $this->ipAddressesService = $ipAddressesService;
    }

    public function index()
    {
        $ip_address = $this->ipAddressesService->all();
        return $this->successResponse($ip_address);
    }

    public function show($ip)
    {
        return $this->successResponse($this->ipAddressesService->findIP($ip));
    }

    public function store(IPAddressStoreApiRequest $request)
    {
        $ip_address = $this->ipAddressesService->updateOrCreate(['id' => $request->input('id')], $request->toArray());
        return $this->successResponse($ip_address);
    }

    public function edit($id)
    {
        return $this->successResponse($this->ipAddressesService->find($id));
    }
}
