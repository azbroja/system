@extends('layouts.customer')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                @if ($correction)
                    <div class="card-header">{{ __('Dodaj Fakturę korygującą') }} </div>
                @else
                    <div class="card-header">{{ __('Dodaj Fakturę sprzedaży') }} </div>
                @endif
                <div class="card-body">
                    <form method="post">
                        @csrf


                            <label for="buyer_address_" class="col-md-4 col-form-label text-md-right">{{ __('Dane Odbiorcy/Nabywcy') }}</label>
                            <div class="col-md-6">
                                <input id="buyer_address_" type="text" class="form-control{{ $errors->has('buyer_address_') ? ' is-invalid' : '' }}" name="buyer_address_" value="{{ $customer->name.', '.$customer->street.', '.$customer->postal_code.' '.$customer->city.', NIP: '.$customer->nip }}" autofocus>
                                @if ($errors->has('buyer_address_'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('buyer_address_') }}</strong>
                                </span>
                                @endif
                            </div>
                            <label for="purchaser" class="col-md-4 col-form-label text-md-right">{{ __('Dane Nabywcy') }}</label>
                            <div class="col-md-6">
                                <input id="purchaser" type="text" class="form-control{{ $errors->has('purchaser') ? ' is-invalid' : '' }}" name="purchaser" value="{{ $customer->purchaser }}" autofocus>
                                @if ($errors->has('purchaser'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('purchaser') }}</strong>
                                </span>
                                @endif
                            </div>

                            <label for="pay_type" class="col-md-4 col-form-label text-md-right">{{ __('Forma Płatności') }}</label>
                            <div class="col-md-6">
                                <div>
                                    <select class="form-control" name="pay_type">
                                    @if ($payType == "transfer")
                                    <option value="transfer" selected>Przelew</option>
                                    <option value="cash">Gotówka</option>
                                     @else
                                        <option value="transfer">Przelew</option>
                                        <option value="cash" selected>Gotówka</option>
                                        @endif
                                    </select>
                                </div>
                                @if ($errors->has('pay_type'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('pay_type') }}</strong>
                                </span>
                                @endif
                            </div>
                            <label for="pay_term" class="col-md-4 col-form-label text-md-right">{{ __('Termin Płatności') }}</label>
                            <div class="col-md-6">
                                <div>

                                    <select class="form-control" name="pay_term">

                                        @foreach($paydays as $payday)
                                        @if ($payday == $payTerm)
                                        <option value="{{ $payday }}" selected>{{ $payday }}</option>
                                        @else
                                        <option value="{{ $payday }}">{{ $payday }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('pay_term'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('pay_term') }}</strong>
                                </span>
                                @endif
                            </div>
                              @if ($correction)

                            <label for="comments" class="col-md-4 col-form-label text-md-right">{{ __('Komentarz do dokumentu') }}</label>
                            <div class="col-md-6">
                            @else
                               <label for="comments" class="col-md-4 col-form-label text-md-right">{{ __('Komentarz do dokumentu') }}</label>
                            <div class="col-md-6">
                            @endif

                                <input id="comments" type="text" class="form-control{{ $errors->has('comments') ? ' is-invalid' : '' }}" name="comments" value="{{$invoiceComments}}" autofocus>
                                @if ($errors->has('comments'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('comments') }}</strong>
                                </span>
                                @endif
                            </div>


<br>

   <div class="form-group row">
    <table class="invoices-table" border="1" cellspacing="2" cellpadding="10">
        <thead>
            <tr>

            <th>Nazwa Produktu </th>
            <th>Symbol Produktu </th>
            <th>Cena Sprzedaży</th>
            <th>Ilość</th>
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
                                    {{ __('Generuj fakturę') }}
                                </button>

                                <button type="button" class="add-invoice-product btn btn-primary">+</button>

                                </table>
</div>
</div>
</form>
</div>
             </b>
             </b>


             </div>
             </div>


@endsection



@section('scripts')

<script>
    const customerId = {!! $customer->id !!};
const customerProducts = {!! $customer_products !!};
const invoiceProducts = {!! $invoice_products !!};
</script>
<script src="{{ asset('js/invoice.js') }}"></script>

@endsection
