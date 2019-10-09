@extends('layouts.customer')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-header">{{ __('Dodaj Produkt') }} {{ $product-> name }}{{__(' dla Klienta')}} {{$customer->name }}  </div>

                <div class="card-body">
                            <form method="post">

                        @csrf

                <div class="form-group row">



                   <label for="selling_customer_price" class="col-md-4 col-form-label text-md-right">{{ __('Cena Sprzedaży') }}</label>

                            <div class="col-md-6">
                                <input id="selling_customer_price" type="text" class="form-control{{ $errors->has('selling_customer_price') ? ' is-invalid' : '' }}" name="selling_customer_price" value="{{ $product->selling_price }}" required autofocus>

                                @if ($errors->has('selling_customer_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('selling_customer_price') }}</strong>
                                    </span>
                                @endif
                            </div>

                 <label for="purchase_customer_price" class="col-md-4 col-form-label text-md-right">{{ __('Cena Zakupu') }}</label>

                            <div class="col-md-6">
                                <input id="purchase_customer_price" type="text" class="form-control{{ $errors->has('purchase_customer_price') ? ' is-invalid' : '' }}" name="purchase_customer_price" value="{{ $product->purchase_price}}" required autofocus>

                                @if ($errors->has('purchase_customer_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('purchase_customer_price') }}</strong>
                                    </span>
                                @endif
                            </div>


                 <label for="consumed_customer_price" class="col-md-4 col-form-label text-md-right">{{ __('Cena Sprzedaży') }}</label>

                            <div class="col-md-6">
                                <input id="consumed_customer_price" type="text" class="form-control{{ $errors->has('consumed_customer_price') ? ' is-invalid' : '' }}" name="consumed_customer_price" value="{{ $product->consumed_price }}" required autofocus>

                                @if ($errors->has('consumed_customer_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('consumed_customer_price') }}</strong>
                                    </span>
                                @endif
                            </div>



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



@endsection


