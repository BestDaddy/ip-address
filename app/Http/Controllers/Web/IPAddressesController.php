<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IPAddressesController extends Controller
{
    public function index(Request $request): ?string
    {
        return $request->ip();
    }
}
