@extends('layouts.customer')

@section('content')
<div class="">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header">Lista faktur</div>
                <nav class="customer-menu">

                @can('create_customers')

                <a href="{{ url('customer/invoice/create/'.$customer->id) }}"><button class="btn btn-primary">Dodaj Fakturę</button></a> <br>

                @endcan

                </nav>

<div>

<h1>
Lista Faktur
</h1>

@can('send_packages')
@if ($invoices->count() > 0)
<td> <a href="{{ url('invoice/'.$invoices->first()->id) }}" download="{{ 'FV_'.$invoices->first()->number}}">Faktura {{ $invoices->first()->number }} </a>
@else
@endif
@endcan

@can('create_invoices')

<form method="get" action="{{route('create-invoice', ['customer' => $customer])}}">

<table class="table-hover" border="1" cellspacing="1" cellpadding="10">

      <thead>

<tr>
<td> LP </td>
<td> Data powstania dokumentu</td>
<td> Termin płatności </td>
<td> Dni po terminie </td>
<td> Typ płatności </td>
<td> Uwagi do faktury </td>
<td> Numer faktury </td>

@can('create_customers')

<td> Kwota netto faktury </td>
<td> Kwota brutto faktury </td>

@endcan

</tr>
</thead>

@foreach ($invoices as $index=>$invoice)

        <tbody>
            <tr>
                <td> {{ $index+1 }} </td>
                <td>{{ $invoice->issued_at->todatestring() }}</td>
                <td>{{ $invoice->pay_deadline->todatestring()  }}</td>
                @if ($invoice->is_paid)
                <td>Zapłacono</td>
                @else
                    @if (($invoice->pay_deadline->diffInDays($now, false)) > 7)
                        <td class='errormsg'>{{ $invoice->pay_deadline->diffInDays($now, false) }} </td>
                    @else
                        <td>{{ $invoice->pay_deadline->diffInDays($now, false) }} </td>
                    @endif
                @endif
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
@if (($invoice->is_proforma) && (!$invoice->is_paid))

<a href="{{ url('customer/invoice/proforma/update/'.$invoice->id) }}" class="btn btn-danger" >FV<a/>
@endif

@can('update_invoices')

                <a href="{{ url('customer/invoice/update/'.$invoice->id) }}" class="btn btn-danger" >E<a/>
                @endcan

                @if ($invoice->parent_id || $invoice->incoming || $invoice->is_proforma)
@else


@can('update_invoices')
                <a href="{{ url('customer/invoice/correction/create/'.$invoice->id) }}" class="btn btn-danger" >KO<a/>
                @endcan

                @endif

                @can('create_customers')
                 <a href="{{ url('generate-pdf/'.$invoice->id) }}" class="btn btn-danger">PDF</a>

</td>
<td>  {{ $invoice->net_value }} </a> </td>
                <td>  {{ $invoice->total_value }} </a> </td>




                    @if ($invoice->comments()->count() > 0)
                    <td>

@foreach ($invoice->invoice_comments as $comment)
<li><strong>{{ $comment->note }}</strong> {{ $comment->created_at }}</li>
@endforeach
</td>
                    @else

                    @endif
                    @if ($invoice->parent_id)

                    @else



</td>
@endcan
@endif

@endforeach
@endcan

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
