@extends('layouts.app')

@section('content')
<h1>Wyszukaj klientów</h1>
<div class="list">
        <div class="search">
        <form method="GET" action="/customer/search">
            <button type="submit" class="fas fa-search"></button>
            <br>
        <input class="search-input" name="q" value="{{$needle ?? ''}}" placeholder="Szukaj Klienta po nazwie" style="width: 300px; height: 45px">
        <br>
        <input class="search-input" name="q-street" value="{{$needleStreet ?? ''}}" placeholder="Szukaj Klienta po ulicy" style="width: 300px; height: 45px">
        <br>
        <input class="search-input" name="q-city" value="{{$needleCity ?? ''}}" placeholder="Szukaj Klienta po mieście" style="width: 300px; height: 45px">
        <br>
        <input class="search-input" name="q-nip" value="{{$needleNip ?? ''}}" placeholder="Szukaj Klienta po numerze NIP" style="width: 300px; height: 45px">
        <br>
        <input class="search-input" name="q-email" value="{{$needleEmail ?? ''}}" placeholder="Szukaj Klienta po adresie email" style="width: 300px; height: 45px">
        <br>
        <input class="search-input" name="q-delivery" value="{{$needleDelivery ?? ''}}" placeholder="Szukaj Klienta po adresie dostawy" style="width: 300px; height: 45px">
        <br>
        <input class="search-input" name="q-purchaser" value="{{$needlePurchaser ?? ''}}" placeholder="Szukaj Klienta po adresie nabywcy" style="width: 300px; height: 45px">
        <br>
        <input class="search-input" name="q-person" value="{{$needlePerson ?? ''}}" placeholder="Szukaj Klienta po Osobie Kontaktowej" style="width: 300px; height: 45px">
    </form>
        </div>
</div>

@endsection
