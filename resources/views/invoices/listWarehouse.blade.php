@extends('layouts.app')

@section('content')
<div class="container">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header">Lista faktur</div>


<h1>
Lista Faktur
</h1>



<table class="table-hover" border="1" cellspacing="1" cellpadding="10">

      <thead>

<tr>
<td> LP </td>
<td> Data powstania dokumentu</td>
<td> Nazwa </td>
<td> Typ płatności </td>
<td> Uwagi do faktury </td>
<td> Numer faktury </td>

</tr>
</thead>

@foreach ($invoices as $index=>$invoice)

        <tbody>
            <tr>
                <td> {{ $index+1 }} </td>
                <td>{{ $invoice->issued_at->todatestring() }}</td>
                <td>{{ $invoice->buyer_address__name }}</td>

                    <td>
                    @if ( $invoice->pay_type == 'transfer') Przelew
                    @else Gotówka
                    @endif </td>
                    <td>{{ $invoice->comments  }}</td>

@if ($invoice->parent_id)
                <td> <a href="{{ url('correction/'.$invoice->id) }}" download="{{ 'KOREKTA_'.$invoice->number }}">Korekta Faktury {{ $invoice->number }} </a>
@else
                <td> <a href="{{ url('invoice/'.$invoice->id) }}" download="{{ 'FV_'.$invoice->number}}">Faktura {{ $invoice->number }} </a>
@endif
@endforeach

       </tr>


</tbody>
</table>

<div class="pagination">
{{ $invoices->links() }}
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
