@extends('layouts.main')

@section('content')
    <div class="card" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title">{{$ip_address->ip}}</h5>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">Страна: {{$ip_address->country}}</li>
            <li class="list-group-item">Город:  {{$ip_address->city}}</li>
        </ul>
    </div>
@endsection
