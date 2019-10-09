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

<td>Dokument</td>

<td> Termin realizacji </td>
<td> Typ zlecenia </td>
<td> Dane Kliena </td>
<td> Zlecenie </td>
<td> Data powstania </td>
<td> Uwagi </td>
<td> Magazyn </td>
<td> Handlowiec </td>

</tr>
</thead>
@foreach ($orders as $index=>$order)
        <tbody>
            <tr>
            <td> {{ $index+1 }} </td>
            @if ($order->priority)
            <td style="color: red"><strong>Wysoki</td>
            @else
            <td>Standardowy</strong></td>
            @endif

            <td>

            @if ($order->is_paid || $order->document_type)
                <button class="btn btn-dark" disabled  >FV</a>
                @else
                <a href="{{route('create-invoice', ['customer' => $order->customer_id, 'orders' => [$order->id]])}}" class="btn btn-dark">FV</a>
                @endif

            </td>




</div>


                </td>
                <td>{{ $order->planned }}</td>

                <td>

                @if ($order->incoming)
                Przychodzące
                @else
                Wychodzące
                @endif
                </td>
                <td>

                @can('send_packages')
                @if ($order->buyer_address_delivery)
  <strong> {!! str_replace("\n", "</strong> <br><strong> ", ($order->buyer_address_delivery)) !!}  </strong><br />
@else
<strong>{{$order->customer->name }}</strong> <br />

    {{ $order->customer->street }} <br />
    {{ $order->customer->postal_code.' '.$order->customer->city }}<br />
    @endif
    @endcan
                @can('create_customers')
                <a href="{{ url('customer/update/'.$order->customer_id) }}" target="_blank">{{ $order->buyer_address_ }} </a>


                @endcan
                </td>



                <td>
                <a href="{{ url('order/'.$order->id) }}" download="{{ 'ZLECENIE_'.$order->number }}">Zlecenie {{ $order->number }}
                @if($order->warehouse == false)
<a href="{{ url('/order/warehouse/'.$order->id) }}" class="btn btn-danger" >M<a/>
@else
<button class="btn btn-dark" disabled >MZ</button>
@endif
                @if($order->production == false)
<a href="{{ url('/order/production/'.$order->id) }}" class="btn btn-dark" >P<a/>
@else
<button class="btn btn-dark" disabled >PZ</button>
@endif


                @if($order->products()->where('is_gift', '=', '1')->first())

<a href="{{ url('gift/create/'.$order->id) }}" class="btn btn-success" target="_blank">G<a/>
@endif
</td>
                <td>{{ $order->issued_at }}</td>
                <td>{{ $order->comments }}
</td>
<td>
@can('send_packages')
{{ $order->waybill }} <a href="{{route('create-order-waybillW', $order->id) }}" class="btn btn-dark" target="_blank"> L</a>
@endcan
@can('create_customers')
{{ $order->waybill }} <a href="{{route('create-order-waybill', $order->id) }}" class="btn btn-dark" target="_blank"> L</a>
@endcan

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

<h2>Zlecenia Przychodzące</h2>


<table class="table-hover" border="1" cellspacing="1" cellpadding="10">

      <thead>

<tr>
<td> LP </td>
<td> Typ zlecenia </td>
<td> Dane Kliena </td>
<td> Zlecenie </td>
<td> Data powstania </td>
<td> Uwagi </td>
<td> Magazyn </td>
<td> Handlowiec </td>

</tr>
</thead>
@foreach ($orders_incoming as $index=>$order)
        <tbody>
            <tr>
            <td> {{ $index+1 }} </td>


                <td>
                @if ($order->incoming)
                Przychodzące
                @else
                Wychodzące
                @endif
                </td>
                <td>

@can('send_packages')
@if ($order->buyer_address_delivery)
<strong> {!! str_replace("\n", "</strong> <br><strong> ", ($order->buyer_address_delivery)) !!}  </strong><br />
@else
<strong>{{$order->customer->name }}</strong> <br />

{{ $order->customer->street }} <br />
{{ $order->customer->postal_code.' '.$order->customer->city }}<br />
@endif
@endcan
@can('create_customers')
<a href="{{ url('customer/update/'.$order->customer_id) }}" target="_blank">{{ $order->buyer_address_ }} </a>
@endcan
</td>

                <td><a href="{{ url('order/'.$order->id) }}" download="{{ 'ZLECENIE_'.$order->number }}">Zlecenie {{ $order->number }}

                @if($order->products()->where('is_gift', '=', '1')->first())

<a href="{{ url('gift/create/'.$order->id) }}" class="btn btn-danger" target="_blank">G<a/>
@endif
</td>
                <td>{{ $order->issued_at }}</td>
                <td>{{ $order->comments }}
</td>
<td>

@can('send_packages')
{{ $order->waybill }} <a href="{{route('create-order-waybillW', $order->id) }}" class="btn btn-dark" target="_blank"> L</a>
@endcan
@can('create_customers')
{{ $order->waybill }} <a href="{{route('create-order-waybill', $order->id) }}" class="btn btn-dark" target="_blank"> L</a>
@endcan

@if ($order->collection == 'courier')
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
