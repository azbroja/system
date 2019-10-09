@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">


            <div class="card">
            <nav class="customer-menu">
                @can('create_customers')
                        <a href="{{ url('working-hours/list/'.$userID) }}"><button class="btn btn-primary">Lista Godzin I Rozmów</button></a> <br>
@endcan
                        <a href="{{ url('all-working-hours/list/'.$userID) }}"><button class="btn btn-primary">Karta Ewidencji Czasu Pracy</button></a> <br>
</nav>


                <div class="card-header">{{ __('Podaj godziny pracy') }}</div>

                <div class="card-body">
                    <form method="post">
                        @csrf




                        <div class="form-group row">
                            <label for="telephone_hours" class="col-md-4 col-form-label text-md-right">{{ __('Godziny rozmów') }}</label>



                            <div class="col-md-8">
                                <input id="telephone_hours" name="telephone_hours" type="text" class="form-control{{ $errors->has('telephone_hours') ? ' is-invalid' : '' }}" telephone_hours="telephone_hours" value="00:00{{ old('telephone_hours') }}" required autofocus>

                                @if ($errors->has('telephone_hours'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('telephone_hours') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="name_of_hours" class="col-md-4 col-form-label text-md-right">{{ __('Data') }}</label>

                        <div class="col-md-8">
<input class="form-control search-input" name="date" value="{{ $now->toDateString() }}" type="date">
</div>
</div>
                        <div class="form-group row">
                            <label for="name_of_hours" class="col-md-4 col-form-label text-md-right">{{ __('Godziny pracy - oznaczenie') }}</label>

                            <div class="col-md-8">
                                <select name="name_of_hours" >
                                    <option value="1">8 - Godziny faktycznie przepracowane</option>
                                    <option value="2">W - Urlop Wypoczynkowy</option>
                                    <option value="3">O - Urlop Okolicznościowy</option>
                                    <option value="4">Wnż - Urlop Wypoczynkowy Na żądanie</option>
                                    <option value="5">Ch - Zwolnienie chorobowe L4</option>
                                    <option value="6">N - Nieobecność nieusprawiedliwiona</option>
                                    <option value="7">M - Urlop Macierzyński/Wychowawczy</option>
                                    <option value="8">K - Opieka </option>
                                    <option value="9">P - Inne nieobecności płatne</option>
                                </select>

                                @if ($errors->has('name_of_hours'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name_of_hours') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

@can('hours_raport')
                        <div class="form-group row">
                            <label for="user" class="col-md-4 col-form-label text-md-right">{{ __('Użytkownik') }}</label>

                            <div class="col-md-8">
                            <select name="user">
    @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ ($user->id == $userID) ? 'selected' : ''}}>
    {{ $user->name }} {{ $user->surname }}
</option>
@endforeach
</select>

                                @if ($errors->has('user'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('user') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
@endcan



                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Zapisz') }}
                                </button>


</div>
</div>

</div>
</div>

</div>
</div>


@endsection
