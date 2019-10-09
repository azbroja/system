@extends('layouts.app')
@section('content')







<h1>Wyszukaj produkty</h1>
<div class="list">
        <div class="search">
        <form method="GET" action="/products/customers/search">
            <button type="submit" class="fas fa-search"></button>
            <br>
        <input class="search-input" name="q-name" value="{{$needle ?? ''}}" placeholder="Wyszukaj Produkt" style="width: 300px; height: 45px">
        <br>


    </form>
        </div>
</div>



@endsection
