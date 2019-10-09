@extends('layouts.customer')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-header">{{ __('Zaktualizuj Produkt') }} </div>

                <div class="card-body">
                            <form method="post">
                            <input type="hidden" name="_method" value="PUT">

                        @csrf

               <div class="form-group row ">


    <table class="offers-table" border="1" cellspacing="2" cellpadding="10">
        <thead>
            <tr>

            <th>Nazwa Produktu </th>
            <th>Symbol Produktu </th>
            <th>Cena Sprzedaży</th>
            <th>Cena Zakupu</th>
            <th>Cena Skupu Zużytej</th>
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
                                    {{ __('Zapisz') }}
                                </button>
                                <button type="button" class="add-offer btn btn-primary">+</button>

                                </table>
</div>
</div>
</form>
</div>
             </b>
             </b>


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


</div>
</div>


@endsection

@section('scripts')

<script>
const customerProducts = {!! $customer_products !!};
const customerId = {!! $customer->id !!};

</script>

<script src="{{ asset('js/hardware1.js') }}"></script>

@endsection
