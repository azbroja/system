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
<div>
<input type="search" id="needle" class="form-control" placeholder="Podaj nazwe/symbol produktu">

<!-- <input type="search" id="filter" placeholder="Doprecyzuj wyniki"> -->

</div>
<div>
<select multiple class="select" id="id" name="q">
</select>
</div>
</div>

<div class="container">
<form method="get" id="sendProductsForm">

    <table style="border: 1px solid black;" class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Nazwa</th>
                    <th>Symbol</th>
                    <th>Cena Sprzedaży</th>
                    <th>Cena Zakupu</th>
                    <th>Cena Skupu</th>
                    <th>Usuń</th>
                </tr>
            </thead>
            <tbody id="products-list">

            </tbody>
        </table>
        <input type="submit" class="btn btn-primary" id="save" value="Zapisz">
    </form>

</div>


@endsection

@section('scripts')
<script>
const customerID = {!! $customer->id !!};
</script>
<script src="{{ asset('js/hardware.js') }}"></script>

@endsection
