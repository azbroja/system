@extends('layouts.customer')

@section('content')
<div class="container">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header">Notatki Klienta</div>

                <nav class="customer-menu">

                <a href="{{ url('customer-event/create/'.$customer->id) }}"><button class="btn btn-primary">Dodaj notatkę</button></a> <br>
                </nav>

<div>
<ul>
<strong>Notatki do realizacji:</strong><br>

        @foreach ($customerEvents as $event)
        @if ($event->is_completed == 0)
        <a href="{{ url('customer-event/update/'.$event->id) }}"> <b> {{ $event->user->name }} {{ $event->user->surname }}</b>     @if ($event->priority)
        <strong style="color: red">W</strong>
    @else
N
@endif  {{ $event->planned }} </a>
       <a href="{{ url('customer-event/update/'.$event->id) }}" style="color:black"><b> {{ $event->note }}</b>  <a> <br>
        @endif
        @endforeach
        </ul>

        <ul>
        <strong>Notatki zrealizowane:</strong><br>

        @foreach ($customerEvents as $event)
        @if ($event->is_completed == 1)
        <a href="{{ url('customer-event/update/'.$event->id) }}">{{ $event->user->name }} {{ $event->user->surname }} {{ $event->planned }}  </a> <a href="{{ url('customer-event/update/'.$event->id) }}" style="color:black">  {{ $event->note }} <a> <br>
        @endif
        @endforeach
        </ul>

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
