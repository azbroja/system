@extends('layouts.app')

@section('content')
<nav class="customer-menu">

<a href="{{ url('product/create/') }}"><button class="btn btn-dark">Dodaj Produkt</button></a> <br>
@can('update_invoices')

<a href="{{ url('/products/customers/form_search/') }}"><button class="btn btn-dark">Wyszukaj Produkty Klientów</button></a>
@endcan
</nav><br>

<h1>Wyszukaj produkty</h1>
<div class="list">
        <div class="search">
        <form method="GET" action="/products/search">
            <button type="submit" class="fas fa-search"></button>
            <br>
        <input class="search-input" name="q-name" value="{{$needle ?? ''}}" placeholder="Szukaj Produkt po nazwie" style="width: 300px; height: 45px">
        <br>
        <input class="search-input" name="q-symbol" value="{{$needleStreet ?? ''}}" placeholder="Szukaj Produkt po symbolu" style="width: 300px; height: 45px">
        <br>

    </form>
        </div>
</div>

@endsection
