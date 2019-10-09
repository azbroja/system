@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-header">{{ __('Utwórz Klienta') }}</div>

                <div class="card-body">
                    <form method="post">
                        @csrf



                        <div class="form-group row">

                            <label for="name" class="col-md-4 col-form-label text-md-right"><strong>{{ __('Nazwa Firmy') }}</strong></label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="street" class="col-md-4 col-form-label text-md-right">{{ __('Ulica') }}</label>

<div class="col-md-6">
    <input id="street" type="text" class="form-control{{ $errors->has('street') ? ' is-invalid' : '' }}" name="street" value="" autofocus>

    @if ($errors->has('street'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('street') }}</strong>
        </span>
    @endif
</div>

<label for="postal_code" class="col-md-4 col-form-label text-md-right">{{ __('Kod Pocztowy') }}</label>

<div class="col-md-6">
    <input id="postal_code" type="text" class="form-control{{ $errors->has('postal_code') ? ' is-invalid' : '' }}" name="postal_code" value="" autofocus>

    @if ($errors->has('postal_code'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('postal_code') }}</strong>
        </span>
    @endif
</div>

     <label for="city" class="col-md-4 col-form-label text-md-right">{{ __('Miasto') }}</label>

                            <div class="col-md-6">
                                <input id="city" type="text" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="" autofocus>

                                @if ($errors->has('city'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                @endif
                            </div>











                            <label for="purchaser" class="col-md-4 col-form-label text-md-right">{{ __('Dane Nabywcy') }}</label>

<div class="col-md-6">
    <textarea id="purchaser" purchaser="text" class="form-control{{ $errors->has('purchaser') ? ' is-invalid' : '' }}" name="purchaser" value="" autofocus></textarea>

    @if ($errors->has('purchaser'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('purchaser') }}</strong>
        </span>
    @endif
</div>


<label for="address_delivery" class="col-md-4 col-form-label text-md-right">{{ __('Adres Dostawy') }}</label>

<div class="col-md-6">
    <textarea id="address_delivery" address_delivery="text" class="form-control{{ $errors->has('address_delivery') ? ' is-invalid' : '' }}" name="address_delivery" value="" autofocus></textarea>

    @if ($errors->has('address_delivery'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('address_delivery') }}</strong>
        </span>
    @endif
</div>

     <label for="nip" class="col-md-4 col-form-label text-md-right">{{ __('Numer NIP') }}</label>

                            <div class="col-md-6">
                                <input id="nip" type="text" class="form-control{{ $errors->has('nip') ? ' is-invalid' : '' }}" name="nip" value="" autofocus>

                                @if ($errors->has('nip'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('nip') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="regon" class="col-md-4 col-form-label text-md-right">{{ __('Numer Regon') }}</label>

<div class="col-md-6">
    <input id="regon" type="text" class="form-control{{ $errors->has('regon') ? ' is-invalid' : '' }}" name="regon" value="" autofocus>

    @if ($errors->has('regon'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('regon') }}</strong>
        </span>
    @endif
</div>

<label for="bdo_number" class="col-md-4 col-form-label text-md-right">{{ __('Numer BDO') }}</label>

<div class="col-md-6">
    <input id="bdo_number" type="text" class="form-control{{ $errors->has('bdo_number') ? ' is-invalid' : '' }}" name="bdo_number" value="" autofocus>

    @if ($errors->has('bdo_number'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('bdo_number') }}</strong>
        </span>
    @endif
</div>



     <label for="telephone1" class="col-md-4 col-form-label text-md-right">{{ __('Numer Telefonu') }}</label>

                            <div class="col-md-6">
                                <input id="telephone1" type="text" class="form-control{{ $errors->has('telephone1') ? ' is-invalid' : '' }}" name="telephone1" value="" autofocus>

                                @if ($errors->has('telephone1'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('telephone1') }}</strong>
                                    </span>
                                @endif
                            </div>


     <label for="telephone2" class="col-md-4 col-form-label text-md-right">{{ __('Kolejny Numer Telefonu') }}</label>

                            <div class="col-md-6">
                                <input id="telephone2" type="text" class="form-control{{ $errors->has('telephone2') ? ' is-invalid' : '' }}" name="telephone2" value="" autofocus>

                                @if ($errors->has('telephone2'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('telephone2') }}</strong>
                                    </span>
                                @endif
                            </div>


     <label for="fax" class="col-md-4 col-form-label text-md-right">{{ __('Numer FAX') }}</label>

                            <div class="col-md-6">
                                <input id="fax" type="text" class="form-control{{ $errors->has('fax') ? ' is-invalid' : '' }}" name="fax" value="" autofocus>

                                @if ($errors->has('fax'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('fax') }}</strong>
                                    </span>
                                @endif
                            </div>


     <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Adres email') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="" autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>



     <label for="www" class="col-md-4 col-form-label text-md-right">{{ __('Adres strony www') }}</label>

                            <div class="col-md-6">
                                <input id="www" type="text" class="form-control{{ $errors->has('www') ? ' is-invalid' : '' }}" name="www" value="" autofocus>

                                @if ($errors->has('www'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('www') }}</strong>
                                    </span>
                                @endif
                            </div>



        <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Typ Klienta') }}</label>

                            <div class="col-md-6">
                                <input id="type" type="text" class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}" name="type" value="Odbiorca" autofocus>

                                @if ($errors->has('add_date'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="comments" class="col-md-4 col-form-label text-md-right">{{ __('Uwagi') }}</label>

<div class="col-md-6">
    <textarea id="comments" comments="text" class="form-control{{ $errors->has('comments') ? ' is-invalid' : '' }}" name="comments" value="" autofocus></textarea>

    @if ($errors->has('comments'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('comments') }}</strong>
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
