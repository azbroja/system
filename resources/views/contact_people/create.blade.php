@extends('layouts.customer')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-header">{{ __('Dodaj Osobę kontaktową') }}</div>

                <div class="card-body">
                    <form method="post">
                        @csrf

                <div class="form-group row">



                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Imię') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>


                            <label for="surname" class="col-md-4 col-form-label text-md-right">{{ __('Nazwisko') }}</label>

                            <div class="col-md-6">
                                <input id="surname" type="text" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ old('surname') }}" autofocus>

                                @if ($errors->has('surname'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('surname') }}</strong>
                                    </span>
                                @endif
                            </div>



                            <label for="telephone1" class="col-md-4 col-form-label text-md-right">{{ __('Telefon Stacjonarny') }}</label>

                            <div class="col-md-6">
                                <input id="telephone1" type="text" class="form-control{{ $errors->has('telephone1') ? ' is-invalid' : '' }}" name="telephone1" value="{{ old('telephone1') }}" autofocus>

                                @if ($errors->has('telephone1'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('telephone1') }}</strong>
                                    </span>
                                @endif
                            </div>




                            <label for="telephone2" class="col-md-4 col-form-label text-md-right">{{ __('Telefon Komórkowy') }}</label>

                            <div class="col-md-6">
                                <input id="telephone2" type="text" class="form-control{{ $errors->has('telephone2') ? ' is-invalid' : '' }}" name="telephone2" value="{{ old('telephone2') }}" autofocus>

                                @if ($errors->has('telephone2'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('telephone2') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Adres e-mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>


                            <label for="comments" class="col-md-4 col-form-label text-md-right">{{ __('Komentarze') }}</label>

                            <div class="col-md-6">
                                <input id="comments" type="text" class="form-control{{ $errors->has('comments') ? ' is-invalid' : '' }}" name="comments" value="{{ old('comments') }}" autofocus>

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
