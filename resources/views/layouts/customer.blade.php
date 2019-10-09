@extends('layouts.app')

@section('before')
<nav class="customer-menu">
@include('blocks.menu', ['items'  => [
    'customer-update' => ['label' => 'Dane', 'args' => ['customer' => $customer]],
    'contact-person-list' => ['label' => 'Osoby kontaktowe', 'args' => ['customer' => $customer]],
    'update-customer-product' => ['label' => 'Produkty', 'args' => ['customer' => $customer]],
    'offers-list' => ['label' => 'Oferty', 'args' => ['customer' => $customer]],
    'orders-list' => ['label' => 'Zlecenia', 'args' => ['customer' => $customer]],
    'invoices-list' => ['label' => 'Faktury', 'args' => ['customer' => $customer]],
    ],       'active' => $menu_item ?? null
])
</nav>
<div class="card-header"><b>{{ $customer->name }} </b>, Tel. {{ $customer->telephone1 }}

@if ($customer->contact_people()->count() > 0)
<b>{{ $customer->contact_people()->first()->name }}
{{ $customer->contact_people()->first()->surname }} </b>
{{ $customer->contact_people()->first()->telephone1 }}
{{ $customer->contact_people()->first()->email }}

@endif

</div>

@endsection
