@extends('layouts.app')

@section('content')
<div class="container">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header">Informacje o użytkowniku</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
<div>
Imie: {{ $user->name }}               </div>
<div>
Nazwisko: {{ $user->surname }}               </div>
<div>
E-mail: {{ $user->email }}               </div>
<div>

            </div>

        </div>
    </div>
</div>
</div>

@endsection
