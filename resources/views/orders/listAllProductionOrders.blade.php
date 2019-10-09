@extends('layouts.app')

@section('content')
<div class="">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header">Lista zleceń</div>



<div>

<h1>
Zlecenia Do Realizacji
</h1>



<table class="table-hover" border="1" cellspacing="1" cellpadding="10">

      <thead>

<tr>
<td> LP </td>
<td> Priorytet </td>
<td> Zlecenie </td>
<td> Termin realizacji </td>
<td> Data powstania </td>
<td> Uwagi </td>
<td> Typ Dostawy </td>
<td> Handlowiec </td>

</tr>
</thead>
@foreach ($orders as $index=>$order)
<form method="get" action="{{route('create-invoice', ['customer' => $order->customer_id])}}">
        <tbody>

            <tr>
            <td> {{ $index+1 }} </td>

            @if ($order->priority)
            <td style="color: red"><strong>Wysoki</strong></td>
            @else
            <td>Standardowy</td>
            @endif



                <td><a href="{{ url('orderProduction/'.$order->id) }}" download="{{ 'ZLECENIE_'.$order->number }}">Zlecenie {{ $order->number }}

                @if($order->production == false)
<a href="{{ url('/order/production/'.$order->id) }}" class="btn btn-danger" >P<a/>
@else
<button class="btn btn-dark" disabled >PZ</button>
@endif



</td>
<td>{{ $order->planned }}</td>

                <td>{{ $order->issued_at }}</td>
                <td>{{ $order->comments }}
                <br>
                @foreach ($order->products as $product)
                <li style="font-size: 12px"> {{ $product->pivot->product_name }} - {{ $product->pivot->quantity }} </li>
                @endforeach
</td>
<td>


@if ($order->collection == 'courier')
<button class="btn btn-warning" disabled> W</button>
@elseif ($order->collection == 'driver')
<button class="btn btn-danger" disabled> K</button>
@else
 <button class="btn btn-primary" disabled> O </button>
 @endif
 </td>

                <td>{{ $order->user->name }} {{ $order->user->surname }}</td>


@endforeach

       </tr>


</tbody>
</table>
<br />

<br>


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
