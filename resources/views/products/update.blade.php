@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-header">{{ __('Zaktualizuj produkt') }}</div>

                <div class="card-body">
                    <form method="post">
                            <input type="hidden" name="_method" value="PUT">

                        @csrf


                <div class="form-group row">



                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nazwa produktu') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $product->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>



                            <label for="symbol" class="col-md-4 col-form-label text-md-right">{{ __('Symbol produktu') }}</label>

                            <div class="col-md-6">
                                <input id="symbol" type="text" class="form-control{{ $errors->has('symbol') ? ' is-invalid' : '' }}" name="symbol" value="{{ $product->symbol }}" required autofocus>

                                @if ($errors->has('symbol'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('symbol') }}</strong>
                                    </span>
                                @endif
                            </div>



                            <label for="selling_price" class="col-md-4 col-form-label text-md-right">{{ __('Cena sprzeaży produktu') }}</label>

                            <div class="col-md-6">
                                <input id="selling_price" type="text" class="form-control{{ $errors->has('selling_price') ? ' is-invalid' : '' }}" name="selling_price" value="{{ $product->selling_price }}" autofocus>

                                @if ($errors->has('selling_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('selling_price') }}</strong>
                                    </span>
                                @endif
                            </div>



                            <label for="purchase_price" class="col-md-4 col-form-label text-md-right">{{ __('Cena zakupu produktu') }}</label>

                            <div class="col-md-6">
                                <input id="purchase_price" type="text" class="form-control{{ $errors->has('purchase_price') ? ' is-invalid' : '' }}" name="purchase_price" value="{{ $product->purchase_price }}" autofocus>

                                @if ($errors->has('purchase_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('purchase_price') }}</strong>
                                    </span>
                                @endif
                            </div>





                            <label for="consumed_price" class="col-md-4 col-form-label text-md-right">{{ __('Cena skupu zużytego produktu') }}</label>

                            <div class="col-md-6">
                                <input id="consumed_price" type="text" class="form-control{{ $errors->has('consumed_price') ? ' is-invalid' : '' }}" name="consumed_price" value="{{ $product->consumed_price }}" autofocus>

                                @if ($errors->has('consumed_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('consumed_price') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="is_gift" class="col-md-4 col-form-label text-md-right">{{ __('Typ produktu') }}</label>

<div class="col-md-6">
                            <div>
                            <select id="is_gift" class="form-control" name="is_gift">
    <option  {{ $productGift == 0 ? 'selected' : '' }} value="0">Produkt</option>
    <option {{ $productGift == 1 ? 'selected' : '' }} value="1">Gratis</option>

    </select>

                            </div>

    @if ($errors->has('is_gift'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('is_gift') }}</strong>
        </span>
    @endif
</div>

<label for="made_by_us" class="col-md-4 col-form-label text-md-right">{{ __('Productent') }}</label>

<div class="col-md-6">
                            <div>
                            <select id="made_by_us" class="form-control" name="made_by_us">
                            <option {{ $productMade == 1 ? 'selected' : '' }} value="1">Zamiennik</option>
    <option {{ $productMade == 0 ? 'selected' : '' }}  value="0">Oryginał</option>
    </select>

                            </div>

    @if ($errors->has('made_by_us'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('made_by_us') }}</strong>
        </span>
    @endif
</div>


</div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                            @can('update_invoices')

                                <button type="submit" class="btn btn-primary">
                                    {{ __('Zaktualizuj') }}
                                </button>
                                @endcan

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
    //Disable cut copy paste
    $('body').bind('cut copy paste', function (e) {
        e.preventDefault();
    });

    //Disable mouse right click
    $("body").on("contextmenu",function(e){
        return false;
    });
});
</script>

@endsection
