@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-header">{{ __('Dodaj Komentarz do Faktury') }} {{ $invoice->number }}</div>

                <div class="card-body">
                    <form method="post">
                        @csrf

                <div class="form-group row">



                            <label for="note" class="col-md-4 col-form-label text-md-right">{{ __('Notatka') }}</label>

                            <div class="col-md-12">
                                <textarea rows="5" id="note" type="text" class="form-control{{ $errors->has('note') ? ' is-invalid' : '' }} input-lg" name="note" value="{{ old('note') }}" required autofocus>
</textarea>
                                @if ($errors->has('note'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('note') }}</strong>
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
