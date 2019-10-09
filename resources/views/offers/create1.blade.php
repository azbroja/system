@extends('layouts.customer')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-header">{{ __('Dodaj ofertę sprzedaży dla klienta ') }} <br><b> {{ $customer->name.', '.$customer->street.', '.$customer->postal_code.' '.$customer->city.', NIP: '.$customer->nip }} <b></div>
                <div class="card-body">
                    <form method="post">
                        @csrf





               <div class="form-group row">
    <table class="offers-table" border="1" cellspacing="2" cellpadding="10">
        <thead>
            <tr>

            <th>Nazwa Produktu </th>
            <th>Symbol Produktu </th>
            <th>Cena Sprzedaży</th>
            <th>Cena ???</th>
            <th>Cena Zakupu Zużytej</th>
            <th>Usuń Produkt</th>

            </tr>
        </thead>
        <tbody></tbody>
</table>

</div>

</p>
</div>
</table>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Generuj ofertę') }}
                                </button>
                                <button type="button" class="add-offer btn btn-primary">+</button>

                                </table>
</div>
</div>
</form>
</div>
             </b>
             </b>




@endsection

@section('scripts')
<script src="{{ asset('js/hardware.js') }}"></script>

<script>
const customerProducts = {!! $customer_products !!};
</script>
@endsection
