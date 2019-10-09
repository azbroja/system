@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-header">{{ __('Rozlicz dokument: ') }} {{ $complaint->number }}</div>

                <div class="card-body">
                    <form method="post">
                        @csrf

                <div class="form-group row">

                <label for="worker" class="col-md-4 col-form-label">{{ __('Osoba odpowiedzialna') }}</label>

<div class="col-md-12">
    <input rows="5" id="worker" type="text" class="form-control{{ $errors->has('worker') ? ' is-invalid' : '' }} input-lg" name="worker" value="{{ $complaint->worker }}" required autofocus>

    @if ($errors->has('worker'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('worker') }}</strong>
        </span>
    @endif
</div>
<br />
<label for="date" class="col-md-4 col-form-label">{{ __('Data produkcji') }}</label>

<div class="col-md-12">
    <input rows="5" id="date" type="date" class="form-control{{ $errors->has('date') ? ' is-invalid' : '' }} input-lg" name="date" value="{{ $complaint->made_date }}" required autofocus>

    @if ($errors->has('date'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('date') }}</strong>
        </span>
    @endif
</div>


                            <label for="comments" class="col-md-4 col-form-label">{{ __('Opis usterki') }}</label>

                            <div class="col-md-12">
                                <textarea rows="5" id="comments" type="text" class="form-control{{ $errors->has('comments') ? ' is-invalid' : '' }} input-lg" name="comments" value="" required autofocus>{{ $complaint->comments }}
</textarea>
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
                                    {{ __('Rozlicz') }}
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
