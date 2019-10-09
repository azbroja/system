@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-header">{{ __('Dodaj komentarz do Reklamacji') }} {{ $complaint->number }}</div>
                <div class="card-body">
                    <form method="post">
                        @csrf

                <div class="form-group row">



                            <label for="message" class="col-md-4 col-form-label text-md-right">{{ __('Treść komentarza') }}</label>

                            <div class="col-md-12">
                                <textarea rows="1" id="message" type="text" class="form-control{{ $errors->has('message') ? ' is-invalid' : '' }} input-lg" name="message" value="" required autofocus> {{ $complaint->message }} </textarea>
</textarea>
                                @if ($errors->has('message'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('message') }}</strong>
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
