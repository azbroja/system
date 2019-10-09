@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dodaj Fakturę sprzedaży') }} </div>
                <div class="card-body">
                    <form method="post">
                        @csrf


                            <label for="buyer_address_" class="col-md-4 col-form-label text-md-right">{{ __('Dane Nabywcy') }}</label>
                            <div class="col-md-6">
                                <input id="buyer_address_" type="text" class="form-control{{ $errors->has('buyer_address_') ? ' is-invalid' : '' }}" name="buyer_address_" value="{{ $customer->name.', '.$customer->street.', '.$customer->postal_code.' '.$customer->city.', NIP: '.$customer->nip }}" autofocus>
                                @if ($errors->has('buyer_address_'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('buyer_address_') }}</strong>
                                </span>
                                @endif
                            </div>
                            <label for="buyer_address_recipient" class="col-md-4 col-form-label text-md-right">{{ __('Dane Odbiorcy') }}</label>
                            <div class="col-md-6">
                                <input id="buyer_address_recipient" type="text" class="form-control{{ $errors->has('buyer_address_recipient') ? ' is-invalid' : '' }}" name="buyer_address_recipient" value="" autofocus>
                                @if ($errors->has('buyer_address_recipient'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('buyer_address_recipient') }}</strong>
                                </span>
                                @endif
                            </div>
                            <label for="pay_type" class="col-md-4 col-form-label text-md-right">{{ __('Forma Płatności') }}</label>
                            <div class="col-md-6">
                                <div>
                                    <select class="form-control" name="pay_type">
                                        <option value="transfer">Przelew</option>
                                        <option value="cash">Gotówka</option>
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
                                        <option value="{{ $payday }}">{{ $payday }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('pay_term'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('pay_term') }}</strong>
                                </span>
                                @endif
                            </div>
                            <label for="comments" class="col-md-4 col-form-label text-md-right">{{ __('Komentarz do dokumentu') }}</label>
                            <div class="col-md-6">
                                <input id="comments" type="text" class="form-control{{ $errors->has('comments') ? ' is-invalid' : '' }}" name="comments" value="" autofocus>
                                @if ($errors->has('comments'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('comments') }}</strong>
                                </span>
                                @endif
                            </div>
                            <label for="total_value" class="col-md-4 col-form-label text-md-right">{{ __('Kwota do zapłaty') }}</label>
                            <div class="col-md-6">
                                <input id="total_value" type="text" class="form-control{{ $errors->has('total_value') ? ' is-invalid' : '' }}" name="total_value" value="" autofocus>
                                @if ($errors->has('total_value'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('total_value') }}</strong>
                                </span>
                                @endif
                            </div>


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




@endsection

@section('scripts')

<script>
const customerID = {{ $customer->id }};
const allProducts = {!! $products !!};
const customerProducts = {!! $customer_products !!};
const invoiceProducts = {!! $invoice_products !!};
</script>
<script src="{{ asset('js/invoice.js') }}"></script>

@endsection

