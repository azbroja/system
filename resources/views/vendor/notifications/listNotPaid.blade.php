
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<style>

body {
    display: grid;
    grid-template-columns: 220px 3fr;
    grid-template-rows: 70px 1fr 1fr;
    grid-template-areas: "logo main""menu content""menu content ""menu footer";
    max-width: 100%;
    min-height: 100vh;
    font-size: 15px;
    font-family: sans-serif;
    margin: 0.5px auto;
    grid-row-gap: 1px;
}
.errormsg {
    font-weight: bold;
    color: red;
}
</style>
<body>


<div class="">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header"></div>


<div>

<h1>
Lista Zaległych Faktur {{ $user->name }} {{ $user->surname }}
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

                <td> {{ $invoice->buyer_address_ }} </td>
                <td> Faktura {{ $invoice->number }}  </td>
                <td>  {{ $invoice->net_value }}  </td>
                <td>  {{ $invoice->total_value }}  </td>
<td>
@foreach ($invoice->invoice_comments as $comment)
<li><strong>{{ $comment->note }}</strong> {{ $comment->created_at }}</li>
@endforeach


                    @endforeach

       </tr>


</tbody>
</table>

</div>
    </div>
    </div>
    </div>

</div>


</body>
</html>
