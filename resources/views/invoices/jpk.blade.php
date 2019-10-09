@extends('layouts.app')

@section('content')


<div class="container">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header"></div>
                <br>


                <br>

                <h1>Swórz jpk</h1>
                <div class="list">
                    <div class="search">
                        <form method="GET" action="">
                            <br>
                            <div class="form-group text-center">
                            <div class="input-group" style="margin:auto;">

                            <label for="" class="col-md-4 col-form-label"><strong>{{ __('Podaj Zakres dat') }}</strong></label>

<div class="col-md-6">
<input class="form-control search-input" name="q1" value="{{ $needle1 }}" type="date" style="width: 300px; height: 45px">

<input class="form-control search-input" name="q2" value="{{ $needle2 }}" type="date" style="width: 300px; height: 45px">

    @if ($errors->has('q1'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('q1') }}</strong>
        </span>
    @endif
</div>




<br />

                            <button type="submit" class="fas fa-search left">Szukaj</button>

                        </form>
                    </div>
                    </div>
</div>







{{ $result}}



@endsection
