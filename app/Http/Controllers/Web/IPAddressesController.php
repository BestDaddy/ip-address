<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\IPAddress;
use App\Services\IPAddresses\IPAddressesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IPAddressesController extends Controller
{
    private $ipAddressesService;

    public function __construct(IPAddressesService $ipAddressesService)
    {
        $this->ipAddressesService = $ipAddressesService;
    }

    public function myAddress(Request $request): ?string
    {
        return $this->show($request->ip());
    }

    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(IPAddress::query())
                ->addColumn('edit', function($data){
                    return  '<button
                        class=" btn btn-primary btn-sm btn-block "
                        data-id="'.$data->id.'"
                        onclick="editModel(event.target)">
                        <i class="fas fa-edit" data-id="'.$data->id.'"></i> Изменить</button>';
                })
                ->addColumn('more', function ($data) {
                    return '<a
                        class="text-decoration-none"
                        href="'.route('ip-addresses.show', $data->ip).'">
                        <button class="btn btn-primary btn-sm btn-block">Подробнее</button></a>';
                })
                ->rawColumns(['more', 'edit'])
                ->make(true);
        }
        return view('ip-addresses.index');
    }

    public function show($ip)
    {
        $ip_address = $this->ipAddressesService->findIP($ip);
        return view('ip-addresses.show', compact('ip_address'));
    }

    public function store(Request $request)
    {
        $error = Validator::make($request->all(), array(
            'ip'        => ['required', 'unique:ip_addresses,ip,'.$request->input('id'), 'ipv4'],
            'country'   => ['required'],
            'city'      => ['required'],
        ));

        if($error->fails())
            return response()->json(['errors' => $error->errors()->all()]);

        $ip_address = $this->ipAddressesService->updateOrCreate(['id' => $request->input('id')], $request->toArray());

        return response()->json(['code'=>200, 'message'=>'Model saved successfully', 'data' => $ip_address]);
    }

    public function edit($id)
    {
        return response()->json($this->ipAddressesService->find($id));
    }

    public function destroy($id)
    {
        $ip_address = $this->ipAddressesService->delete($id);
        return response()->json(['code'=>200, 'message'=>'Model deleted successfully', 'data' => $ip_address]);
    }
}
