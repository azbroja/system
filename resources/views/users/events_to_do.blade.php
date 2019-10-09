@extends('layouts.app')

@section('content')


<div class="">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header"></div>

                <br>


<div>
<h1>
Notatki do realizacji:

</h1>


<table class="table-hover" border="1" cellspacing="1" cellpadding="10">

      <thead>

<tr>
    <td>LP</td>
    <td>Priorytet</td>
    <td> Nazwa Klienta </td>
    <td> Notatka</td>
    <td> Data Planowania</td>
</tr>
</thead>
      @foreach ($userToDoEvents as $index=>$event)
        <tbody>
            <tr>
                <td> {{ $index+1 }} </td>
                <td>
                @if ($event->priority)
        <strong style="color: red">W</strong>
    @else
N
@endif
</td>
                <td><a href="{{ url('customer-events/list/'.$event->customer_id) }}">{{ $event->customer->name }}<b> </td>
                <td><a href="{{ url('customer-events/list/'.$event->customer_id) }}">{{ $event->note }}</a></td>
                <td>{{ $event->planned }}</td>
            </tr>

@endforeach
</tbody>
</table>




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
