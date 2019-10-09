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

                                            <input type="hidden" name="_method" value="PUT">

               <div class="form-group row">
    <table border="1" cellspacing="2" cellpadding="10">
<tr>

<th>Nazwa Produktu </th>
<th>Symbol Produktu </th>
<th>Cena Sprzedaży</th>
<th>Cena Zakupu Zużytej</th>
<th>Usuń Produkt</th>

</tr>

@foreach ($customer->products as $product)

<div> <p>
</div>

<tr>
    <td>
<b> {{ $product->name }}  </b></td>

    <td>
<b> {{ $product->symbol }}  </b></td>
<td>

                   <label for="selling_customer_price"></label>

                                <input type="text" class="form-control{{ $errors->has('selling_customer_price') ? ' is-invalid' : '' }}" name="customer_prices[{{ $product->id }}][selling_customer_price]" value="{{ $product->pivot->selling_customer_price }}" required autofocus>

                                @if ($errors->has('selling_customer_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('selling_customer_price') }}</strong>
                                    </span>
                                @endif
                            </div>
</td>

<td>
                 <label for="consumed_customer_price"></label>

                                <input type="text" class="form-control{{ $errors->has('consumed_customer_price') ? ' is-invalid' : '' }}" name="customer_prices[{{ $product->id }}][consumed_customer_price]" value="{{ $product->pivot->consumed_customer_price }}" required autofocus>

                                @if ($errors->has('consumed_customer_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('consumed_customer_price') }}</strong>
                                    </span>
                                @endif
                            </div>
</td>
<td> Klik </td>
</tr>

@endforeach

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

                                </table>
</div>
</div>
</form>
</div>
             </b>
             </b>




@endsection
