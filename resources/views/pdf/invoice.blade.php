
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
    <style>
body {
    font-family: DejaVu Sans;
}
    .gray {background-color: #e0e0e0; margin-top: 2; margin-bottom: 2; }
    .gray_td {background-color: #e0e0e0; text-decoration: underline; text-align: center;  border: 1px solid #000000; border-collapse: collapse; padding: 2px;}
    .gray_wide {background-color: #e0e0e0; margin-top: 5; margin-bottom: 2; }
    .fv_std_td{ border: 1px solid #000000; border-collapse: collapse; empty-cells: show; margin: 2px; font-size: 11px; padding: 5px;}
    .do_zaplaty{ background-color: #e0e0e0; border: 1px solid #000000; border-collapse: collapse; empty-cells: show; margin-top: 5px; font-size: 12px; padding: 5px;}
    .std_td{ border: 1px solid #000000; border-collapse: collapse; empty-cells: show; margin: 2px; font-size: 11px; }
    td {font-size: 12px;}
    hr { height: 1px; background-color: none; color: slategray;}

</style>
</head>
<body>


  <title>FV {{$invoice->number}}</title>







<table width='100%'>
  <tr><td valign='top'>

      <table width='100%'><tr>
          <td width='35%'>{!! str_replace("\n", "<br/>", ($invoice->seller_address)) !!}
            <br>
            <br>
          Numer Konta bankowego: <br>Bank Credit Agricole<br>{{ $seller->bank_account_number }}
<br>
Tel./Fax {{ $seller->fax }}<br>
Numer {{ $seller->bdo_number }}
          <td width='30%'  valign='top'> </td>
          <td  width='35%' ><p class='gray_wide'>Miejsce wystawienia:</p><b>{{ $invoice->place }}</b><p class='gray_wide'>Data wystawienia:</p><b>{{ date('d-m-Y', strtotime($invoice->issued_at)) }}</b><p class='gray_wide'>Data dokonania dostawy towarów:</p><b>{{ date('d-m-Y', strtotime($invoice->sell_date)) }}</b><hr></td>
      </tr></table>
     <br><br>

     <table width='100%'><tr>
      <td><p class='gray'>Sprzedawca:</p></td><td></td><td>
@if ($invoice->buyer_address_recipient == NULL)
        <p class='gray'>Nabywca:</p></td></tr><tr>
          <td valign='top' width='47%'>{!! str_replace("\n", "<br/>", ($invoice->seller_address)) !!}<hr></td>
          <td></td>
          <td valign='top' width='47%'>          {{ $invoice->buyer_address__name }}<br>
          {{ $invoice->buyer_address__address }}<br>
          {{ $invoice->buyer_address__postal_code }}
          {{ $invoice->buyer_address__city }}<br>
          {{ $invoice->buyer_address__nip ? 'NIP: '.$invoice->buyer_address__nip : ' '}}<hr>


      </tr></table>
  @else
   <p class='gray'>Nabywca:</p></td></tr><tr>
          <td valign='top' width='47%'>{!! str_replace("\n", "<br/>", ($invoice->seller_address)) !!}<hr></td>
          <td></td>
          <td valign='top' width='47%'>          {{ $invoice->buyer_address__name }}<br>
          {{ $invoice->buyer_address__address }}<br>
          {{ $invoice->buyer_address__postal_code }}
          {{ $invoice->buyer_address__city }}<br>
          {{ $invoice->buyer_address__nip ? 'NIP: '.$invoice->buyer_address__nip : ' '}}<hr>



          <hr>
            <p class='gray'>Odbiorca:</p>
          {!! str_replace("\n", "<br/>", ($invoice->buyer_address_recipient)) !!}<hr></td>

      </tr></table>

@endif
      <br>

      <h2 style="text-align: center;">{{$invoice->is_proforma ? 'Faktura '.$invoice->number : 'Faktura VAT '.$invoice->number }}</h2>
      @if ($duplicat)
      <h3>Duplikat z dnia {{date('d-m-Y',strtotime($now))}}</h3>
    @else
    @endif
      <table width='100%'><tr><td>
      <table width='100%' style='border-spacing: 0px 0px;'>
      <tr>
          <td class='gray_td'>Lp</td>
          <td class='gray_td'>Nazwa</td>
          <td class='gray_td'>Ilosć</td>
          <td class='gray_td'>j.m.</td>
          <td class='gray_td'>Cena netto</td>
          <td class='gray_td'>VAT<br>[%]</td>
          <td class='gray_td'>Wart. netto</td>
          <td class='gray_td'>VAT</td>
          <td class='gray_td'>Wart. brutto</td>
      </tr>
            @foreach ($invoice_products as $index => $product)

      <tr>
          <td class='fv_std_td'>{{ $index + 1 }}</td>
          <td class='fv_std_td'  width='35%'>{{ $product->pivot->product_name }} </td>
          <td class='fv_std_td'>{{ $product->pivot->quantity }}</td>
          <td class='fv_std_td'>szt. </td>
          <td class='fv_std_td' > {{ amount_pl($product->pivot->net_unit_price) }} </td>
          <td class='fv_std_td'> 23% </td>
          <td class='fv_std_td' > {{ amount_pl(($product->pivot->net_unit_price*$product->pivot->quantity)) }} </td>
          <td class='fv_std_td' > {{ amount_pl((($product->pivot->gross_unit_price-$product->pivot->net_unit_price)*$product->pivot->quantity)) }} </td>
          <td class='fv_std_td' > {{ amount_pl(($product->pivot->gross_unit_price*$product->pivot->quantity)) }} </td>
      </tr>
              @endforeach
</table></td></tr></table>


      <table  width=100%><tr><td wdith=30%></td><td width=70% >
      <table  width=100%>
      <tr>
        <td class='gray_td'>Nazwa stawki VAT</td>
        <td class='gray_td'>wartość netto</td>
        <td class='gray_td'>kwota VAT</td>
        <td class='gray_td'>wartość brutto</td>
</tr>
        <td class='fv_std_td'>Podstawowy podatek VAT 23% </td>
        <td class='fv_std_td' >{{ amount_pl($sum_net) }} </td>
        <td class='fv_std_td' >{{ amount_pl($sum_gross - $sum_net) }}</td>
        <td class='fv_std_td' >{{ amount_pl($sum_gross) }} </td>
      </tr>
      </table>
      </td></tr></table>

      <br><br>
      <table  width='100%'><tr>
      <td  valign='top' width='50%'>  <table width='100%' ><tr><td width=130>
      @if (($invoice->sell_date == $invoice->pay_deadline) || ($invoice->pay_type == 'cash'))
      <b>Zapłacono:</b></td><td>
                  <b>{{ amount_pl($sum_gross) }}</b></td></tr>
                  @if ($invoice->pay_type == 'cash')
                  <tr><td><b>Typ płatności:</b></td><td><b> Gotówka <b></td></tr>
                  @else
                  <tr><td><b>Typ płatności:</b></td><td><b> Przelew <b></td></tr>
@endif
                  <tr><td><b></b></td><td><b> <b></td></tr>
                  <tr><td valign=top><b>  </b></td><td></td></tr></table>
                  @if ($invoice->comments == null)

  @else
   <table  width='43%'><tr>
      <td ><p class=''><b>Uwagi:</b></p></td><td></td></tr><tr>
          <td>{{ $invoice->comments }}</td>

      </tr></table>
      <br>


@endif
      </td>
      @else

      <b>Pozostało do zapłaty:</b></td><td>
                  <b>{{ amount_pl($sum_gross) }}</b></td></tr>
                  <tr><td><b>Typ płatności:</b></td><td><b> Przelew <b></td></tr>
                  <tr><td><b>W terminie do:</b></td><td><b> {{ date('d-m-Y', strtotime($invoice->pay_deadline)) }}<b></td></tr>
                  <tr><td valign=top><b>  </b></td><td></td></tr></table>
@if ($invoice->comments == null)

  @else
   <table  width='43%'><tr>
      <td ><p class=''><b>Uwagi:</b></p></td><td></td></tr><tr>
          <td>{{ $invoice->comments }}</td>

      </tr></table>
      <br>


@endif
@if ($invoice->dividedPayType == 1)

<b style="margin-left: 2px;font-size: 15px"> MECHANIZM PODZIELONEJ PŁATNOŚCI</b>
@else
@endif
      </td>

      @endif
      <td  valign=top>

              <table width='90%'><tr>
              @if ($invoice->pay_type == 'cash')
              <td class='do_zaplaty'><b>Zapłacono razem:</b></td>
              @else
              <td class='do_zaplaty'><b>Razem do zapłaty:</b></td>
              @endif
              <td  class='do_zaplaty'  width='40%'> <b>{{ amount_pl($sum_gross, true) }}</b></td></tr></table>
              <table width='90%'><tr><td ><b>Słownie:</b>

                           {{ amount_literally($sum_gross) }}
</td></tr></table>

      </td>




<br>
<br>


      <table  width='100%'><tr>
      <td><p class='gray'><b>Wystawił(a):</b></p></td><td></td></tr><tr>
          <td valign='top' width='43%' class='std_td' height='70'></td>
          <td></td>

      </tr></table>
      <br>


    </td></tr>

    </table>



</body>
</html>
