@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-header">{{ __('Zaktualizuj użytkownika') }}</div>

                <div class="card-body">
                    <form method="post">
                            <input type="hidden" name="_method" value="PUT">

                        @csrf



                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Imię') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $user->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                <div class="form-group row">
                            <label for="surname" class="col-md-4 col-form-label text-md-right">{{ __('Nazwisko') }}</label>

                            <div class="col-md-6">
                                <input id="surname" type="text" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ $user->surname }}" required autofocus>

                                @if ($errors->has('surname'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('surname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="period_tests" class="col-md-4 col-form-label text-md-right">{{ __('Termin badań okresowych') }}</label>

                            <div class="col-md-6">
                                <input id="period_tests" type="date" class="form-control{{ $errors->has('period_tests') ? ' is-invalid' : '' }}" name="period_tests" value="{{ $user->period_tests }}" required autofocus>

                                @if ($errors->has('period_tests'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('period_tests') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                       <center>
                        @foreach($roles as $role)
            <button type="button" class="btn btn-primary role" data-permissions="{{ $role->permissions->pluck('id') }}">{{ $role->name }}</button>
                        @endforeach
                        </center>
                        <br>




                <div class="form-group row">
                    <label for="permissions" class="col-md-4 col-form-label text-md-right">{{ __('Uprawnienia') }}</label>
                    @foreach ($permissions as $permission)
                    <input type="checkbox" class="permission" name="newPermissionsIDs[]" value="{{ $permission->id }}"
                    {{  $userPermissionsIDs->contains($permission->id) ? 'checked' : '' }}> {{ $permission->name }} </input>

                    <input type="hidden" name="allPermissionsIDs[]" value="{{ $permission->id }}">





                    @endforeach

                                @if ($errors->has('permissions'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('permissions') }}</strong>
                                    </span>
                                @endif
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Adres E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $user->email }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <br>
                        <a href="/user/update-password/{{ $user->id }}">Zmień hasło dla użytkownika {{ $user->name}}</a>
                        <br>



                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Zaktualizuj') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    [...document.getElementsByClassName('role')].forEach(button => {
        const permissionsIDs = JSON.parse(button.dataset.permissions);
        button.addEventListener('click', e => {
            [...document.getElementsByClassName('permission')].forEach(checkbox => {
                checkbox.checked = permissionsIDs.includes(Number(checkbox.value));
            });
        });
    });
 </script>


@endsection
