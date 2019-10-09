@extends('layouts.app')

@section('content')
<div class="">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header">Lista faktur</div>


<div>
<a href="{{ url('/invoices/not-paid/') }}" class="btn btn-dark" >Wyślij listę dłużników<a/>

<h1>
Lista Zaległych Faktur
</h1>



<table class="table-hover" border="1" cellspacing="1" cellpadding="10">

      <thead>

<tr>
<td> LP </td>
<td> Data powstania dokumentu</td>
<td> Termin płatności </td>
<td> Dni po terminie </td>
<td> Typ płatności </td>
<td> Nazwa Klienta </td>
<td> Numer faktury </td>
<td> Kwota netto faktury </td>
<td> Kwota brutto faktury </td>
<td> Komentarze </td>
<td> Akcje </td>

</tr>
</thead>

@foreach ($invoices as $index=>$invoice)
        <tbody>
            <tr>
                <td> {{ $index+1 }} </td>
                <td>{{ $invoice->issued_at->todatestring() }}</td>
                <td>{{ $invoice->pay_deadline->todatestring()  }}</td>
                    @if (($now->diffInDays($invoice->pay_deadline)) > 7)
                        <td class='errormsg'>{{ $now->diffInDays($invoice->pay_deadline) }} </td>
                    @else
                        <td>{{ $now->diffInDays($invoice->pay_deadline) }} </td>
                    @endif
                    <td>
                    @if ( $invoice->pay_type == 'transfer') Przelew
                    @else Gotówka
                    @endif </td>

                <td> <a href="{{ url('customer/update/'.$invoice->customer_id) }}">{{ $invoice->buyer_address_ }} </a> </td>
                <td> <a href="{{ url('invoice/'.$invoice->id) }}" download="{{ 'FV_'.$invoice->number }}">Faktura {{ $invoice->number }} </a> </td>
                <td>  {{ $invoice->net_value }} </a> </td>
                <td>  {{ $invoice->total_value }} </a> </td>
<td>
@foreach ($invoice->invoice_comments as $comment)
<li><strong>{{ $comment->note }}</strong> {{ $comment->created_at }}</li>
@endforeach
</td>

                    <td><a href="{{route('create-invoice-comment', $invoice->id) }}" class="btn btn-danger"> {{ __('K') }}</a></td>

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
