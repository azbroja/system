@extends('layouts.app')

@section('content')


<div class="container">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header"></div>
                <br>

                <a href="{{ url('user-events-to-do') }}" > Pokaż wszystkie Notatki </a>

                <!-- <button style="width = 10px;" class="btn-primary" onclick="location.href='{{ url('user-events-to-do') }}'" type="button">
     Pokaz wszystkie notatki</button> -->
                <br>

       <h1>Wyszukaj notatki</h1>
<div class="list">
        <div class="search">
        <form method="GET" action="search">
            <br>

            <input class="search-input" name="q1" value="{{date('Y-m-d') }}" type ="date" style="width: 300px; height: 45px">
            <input class="search-input" name="q2" value="{{date('Y-m-d') }}" type ="date" style="width: 300px; height: 45px">
            <button type="submit" class="fas fa-search"></button>

        </form>
        </div>


@if ($userToDoEvents === null)


@else
<table class="table-hover" border="1" cellspacing="1" cellpadding="10">
<thead>
<tr>
<td>LP</td>
<td> Nazwa Klienta </td>
<td> Notatka</td>
<td> Data Planowania</td>
</tr>
</thead>
@foreach ($userToDoEvents as $index=>$event)
  <tbody>
      <tr>
          <td> {{ $index+1 }} </td>
          <td><a href="{{ url('customer-events/list/'.$event->customer_id) }}">{{ $event->customer->name }}<b> </td>
          <td><a href="{{ url('customer-events/list/'.$event->customer_id) }}">{{ $event->note }}</a></td>
          <td>{{ $event->planned }}</td>
      </tr>

@endforeach
</tbody>
</table>

@endif
</div>
</div>
</div>
</div>
</div>
</div>



@endsection
@section('scripts')

<script type="text/javascript">
$(document).ready(function () {
    //Disable cut copy paste
    $('body').bind('cut copy paste', function (e) {
        e.preventDefault();
    });

    //Disable mouse right click
    $("body").on("contextmenu",function(e){
        return false;
    });
});
</script>

@endsection
@section('scripts')

<script type="text/javascript">
$(document).ready(function () {
    //Disable cut copy paste
    $('body').bind('cut copy', function (e) {
        e.preventDefault();
    });

    //Disable mouse right click
    $("body").on("contextmenu",function(e){
        return false;
    });
});
</script>

@endsection
