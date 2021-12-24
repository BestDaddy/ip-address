<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IPAddress extends Model
{
    protected $table = 'ip_addresses';
    protected $fillable = [
        'ip',
        'country',
        'city',
    ];
}
