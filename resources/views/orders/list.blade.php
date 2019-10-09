@extends('layouts.customer')

@section('content')
<div class="">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header">Lista zleceń</div>
                <nav class="customer-menu">


</nav>
<div>




<nav class="customer-menu">
<a href="{{ url('customer/order/create/'.$customer->id) }}"><button class="btn btn-primary">Dodaj Zlecenie</button></a>
</nav>

<table class="table-hover" border="1" cellspacing="1" cellpadding="10" >


      <thead>


<tr>
<td> Priorytet </td>
<td> Wybierz </td>
<td> Status zlecenia</td>
<td> Dane zlecenia </td>
<td> Data powstania </td>
<td> Uwagi </td>
<td> Kwota netto </td>
<td> Kwota brutto </td>

</tr>
</thead>
@foreach ($orders as $order)

        <tbody>
            <tr>
            @if ($order->priority)
            <td style="color: red"><strong>Wysoki</td>
            @else
            <td>Standardowy</strong></td>
            @endif
            <td>

                @if ($order->is_paid || $order->document_type)
                <button class="btn btn-dark" disabled  >FV</a>
                @else
                <a href="{{route('create-invoice', ['customer' => $order->customer_id, 'orders' => [$order->id]])}}" class="btn btn-dark" disabled >FV</a>
                @endif

                <td>
                @if ($order->is_paid)
                Zrealizowane
                @else
                Do realizacji
                @endif
                </td>
                <td><a href="{{ url('order/'.$order->id) }}" download="{{ 'ZLECENIE_'.$order->number }}">Zlecenie {{ $order->number }} </a>
                @if ($order->is_paid)
                @elseif($order->products()->where('is_gift', '=', '1')->first())

                <a href="{{ url('gift/create/'.$order->id) }}" class="btn btn-success" target="_blank">G<a/>
                @endif



                </td>
                <td>{{ $order->issued_at }}</td>
                <td>
                @if ($order->pay_type == 'cash')
                Gotówka
                @else
                Przelew {{ $order->pay_term }} dni
                @endif
                <br>
                {{ $order->comments }}

                </td>
                <td>{{ $order->total_sum_net() }}</td>
                <td>{{ $order->total_sum_gross() }}</td>

@endforeach

       </tr>


</tbody>
</table>

<br />


<div class="pagination">
{{ $orders->links() }}
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
