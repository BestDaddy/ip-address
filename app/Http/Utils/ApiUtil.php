<?php


namespace App\Http\Utils;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiUtil
{
    public static function generateToken(): string
    {
        return Str::random(42);
    }

    public static function checkUrlIsApi(Request $request)
    {
        return $request->wantsJson() || \Illuminate\Support\Facades\Request::is('api/*');
    }
}
