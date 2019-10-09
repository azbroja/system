@extends('layouts.app')

@section('content')
<div class="">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header">Lista dni urlopu do wykorzystania</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <center>
                    <form method="post">
                    <input type="hidden" name="_method" value="PUT">

@csrf
                    <div>
                    <table class="table-hover" border="1" cellspacing="1" cellpadding="10">

<tr>
<td>
Dane Użytkownika
</td>
<td>
Ilość dni urlopu
</td>
</tr>
                        @foreach ($users as $user)

<tr>
<td>
                       <input type="hidden" name="users[{{$loop->index}}][id]" value="{{$user->id}}">
                        {{ $user->name }} {{ $user->surname }}
                        </td>
                        <td>
                        <input name="users[{{$loop->index}}][dayoff_counter]" value="{{ $user->dayoff_counter }}">
                        </td>
                        </tr>

                        @endforeach

</table>

        </div>
        <br>
        <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
        </form>
    </center>
    </div>
    </div>
    </div>

</div>
@endsection
