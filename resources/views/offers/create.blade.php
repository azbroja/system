@extends('layouts.customer')

@section('content')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

<style>

select {
    font: inherit;
    height: 125px;
    width: 100%;
}
</style>
</head>

<div class="container">

            <div class="card">
                <div class="card-header">{{ $updateOffer ? 'Edytuj ofertę sprzedaży' : 'Dodaj ofertę sprzedaży' }}


</div>
</div>
</div>





<div class="container">
<div>
<input type="search" id="filter" class="form-control" placeholder="Podaj nazwe/symbol produktu">


</div>
<div>
<select multiple class="select" id="id" name="q">
</select>
</div>
</div>

<div class="container">
<form method="post" id="sendProductsForm">

    <table style="border: 1px solid black;" class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Nazwa</th>
                    <th>Symbol</th>
                    <th>Cena Sprzedaży</th>
                    <th>Cena Skupu</th>
                    <th>Usuń</th>
                </tr>
            </thead>
            <tbody id="products-list">

            </tbody>
        </table>
        <input type="button" class="btn btn-dark" id="addAll" value="Dodaj Wszystkie">

        <input type="submit" class="btn btn-primary" id="save" value="{{ $updateOffer ? 'Zaktualizuj ofertę' : 'Generuj Ofertę' }}">

    </form>


</div>

@endsection

@section('scripts')
<script>
const customerID = {!! $customer->id !!};
const customerProducts = {!! $customer_products !!};
const offerProducts = {!! $offerProducts !!};
const updateOffer = {!! $updateOffer !!};
const offerId = {!! $offerId !!};


</script>
<script src="{{ asset('js/hardwareOffer.js') }}"></script>

@endsection
