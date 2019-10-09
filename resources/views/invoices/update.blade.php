@extends('layouts.customer')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-header">{{ __('Dodaj Fakturę sprzedaży') }} </div>
               <div class="card-body">
                    <form method="post">
                        @csrf

                                            <input type="hidden" name="_method" value="PUT">

                        <div class="form-group row">
                            <label for="seller_address" class="col-md-4 col-form-label text-md-right">{{ __('Dane Sprzedawcy') }}</label>
                            <div class="col-md-6">
                                <input id="seller_address" type="text" class="form-control{{ $errors->has('seller_address') ? ' is-invalid' : '' }}" name="seller_address" value="{{ $invoice->seller_address }}" autofocus>
                                @if ($errors->has('seller_address'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('seller_address') }}</strong>
                                </span>
                                @endif
                            </div>
                            <label for="buyer_address_" class="col-md-4 col-form-label text-md-right">{{ __('Dane Nabywcy') }}</label>
                            <div class="col-md-6">
                                <input id="buyer_address_" type="text" class="form-control{{ $errors->has('buyer_address_') ? ' is-invalid' : '' }}" name="buyer_address_" value="{{ $invoice->buyer_address_ }}" autofocus>
                                @if ($errors->has('buyer_address_'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('buyer_address_') }}</strong>
                                </span>
                                @endif
                            </div>
                            <label for="buyer_address_recipient" class="col-md-4 col-form-label text-md-right">{{ __('Dane Odbiorcy') }}</label>
                            <div class="col-md-6">
                                <input id="buyer_address_recipient" type="text" class="form-control{{ $errors->has('buyer_address_recipient') ? ' is-invalid' : '' }}" name="buyer_address_recipient" value="{{ $invoice->buyer_address_recipient }}" autofocus>
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
                                    @if ($invoice->pay_type == 'transfer')

                                        <option value="transfer" selected >Przelew</option>
                                        <option value="cash">Gotówka</option>
                                    @else
                                        <option value="transfer" >Przelew</option>
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


                            <label for="comments" class="col-md-4 col-form-label text-md-right">{{ __('Komentarz do dokumentu') }}</label>
                            <div class="col-md-6">
                                <input id="comments" type="text" class="form-control{{ $errors->has('comments') ? ' is-invalid' : '' }}" name="comments" value="{{ $invoice->comments }}" autofocus>
                                @if ($errors->has('comments'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('comments') }}</strong>
                                </span>
                                @endif
                            </div>
                            <label for="total_value" class="col-md-4 col-form-label text-md-right">{{ __('Kwota do zapłaty') }}</label>
                            <div class="col-md-6">
                                <input id="total_value" type="text" class="form-control{{ $errors->has('total_value') ? ' is-invalid' : '' }}" name="total_value" value="{{ $invoice->total_value }}" autofocus>
                                @if ($errors->has('total_value'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('total_value') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div style="height: 100px;"></div>
                            @for ($i = 0; $i < 3; $i += 1)
                            <label for="product_id{{ $i }}" class="col-md-4 col-form-label text-md-right">{{ __('Dodaj produkt do dokumentu') }}</label>
                            <div class="col-md-6">
                                <div>
                                    <select id="product_id{{ $i }}" class="form-control" name="products[{{ $i }}][id]">
                                        @foreach($invoice->products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <label for="quantity{{ $i }}" class="col-md-4 col-form-label text-md-right">{{ __('Ilość produktów') }}</label>
                            <div class="col-md-6">
                                <input id="quantity{{ $i }}" type="text" class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}" name="products[{{ $i }}][quantity]" value="" autofocus>
                            </div>

                            <label for="net_unit_price{{ $i }}" class="col-md-4 col-form-label text-md-right">{{ __('Cena netto') }}</label>
                            <div class="col-md-6">
                                <input id="net_unit_price{{ $i }}" type="text" class="form-control{{ $errors->has('quantity') ? ' is-invalid' : '' }}" name="products[{{ $i }}][net_unit_price]" value="{{ $product->pivot->selling_customer_price }}" autofocus>
                            </div>

                            @endfor

                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Zapisz') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <iframe name="invoice">
</iframe>
<a href="/invoice/{{ $invoice->id }}"" target="invoice">Drukuj fakturę nr {{ $invoice->id }} </a> --}}

@endsection
