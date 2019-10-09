@extends('layouts.app')

@section('content')
<div class="">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header">Lista użytkowników</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <center>

                    <div>
                        @foreach ($users as $user)
                        <a href="{{ url('user/update/'.$user->id) }}">{{ $user->id }} {{ $user->name }} {{ $user->surname }}  </a> <br>
                        @endforeach

        </div>
    </center>
    </div>
    </div>
    </div>

</div>
@endsection
