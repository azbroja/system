@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-header">{{ __('Rozlicz negatywnie test: ') }} {{ $test->number }}</div>

                <div class="card-body">
                    <form method="post">
                        @csrf

                <div class="form-group row">



                            <label for="comments" class="col-md-4 col-form-label">{{ __('Opis problemu') }}</label>

                            <div class="col-md-12">
                                <textarea rows="5" id="comments" type="text" class="form-control{{ $errors->has('comments') ? ' is-invalid' : '' }} input-lg" name="comments" value="" required autofocus>{{ $test->comments }}
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
