


<html>
  <head>
  <meta http-equiv='content-type' content='text/html; charset=UTF-8'>
  <meta name='generator' content='pspad editor, www.pspad.com'>
  <title>FV {{$invoice->number}}</title>




  </head>
  <body LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>



<table border='0' width='100%'>
  <tr><td valign='top'>

      <table border='0' width='100%'><tr>
          <td width='35%'>{!! str_replace("\n", "<br/>", ($invoice->seller_address)) !!}
            <br>
            <br>
          Numer Konta bankowego: <br>Bank <br>{{ $seller->bank_account_number }}
<br>
Tel./Fax {{ $seller->fax }}<br>
Numer {{ $seller->bdo_number }}
          <td width='30%'  align='middle' valign='top'> </td>
          <td  width='35%' align='middle'><p class='gray_wide'>Miejsce wystawienia:</p><b>{{ $invoice->place }}</b><p class='gray_wide'>Data wystawienia:</p><b>{{ date('d-m-Y', strtotime($invoice->issued_at)) }}</b><p class='gray_wide'>Data dokonania dostawy towarów:</p><b>{{ date('d-m-Y', strtotime($invoice->sell_date)) }}</b><hr></td>
      </tr></table>
     <br><br>

      <table border='0' width='100%'><tr>
      <td><center><p class='gray'>Sprzedawca:</p></center></td><td></td><td><center>
@if ($invoice->buyer_address_recipient == NULL)
        <p class='gray'>Nabywca:</p></center></td></tr><tr>
          <td valign='top' width='47%'>{!! str_replace("\n", "<br/>", ($invoice->seller_address)) !!}<hr></td>
          <td></td>
          <td valign='top' width='47%'>{{ $invoice->buyer_address__name }}<br>
          {{ $invoice->buyer_address__address }}<br>
          {{ $invoice->buyer_address__postal_code }}
          {{ $invoice->buyer_address__city }}<br>
          {{ $invoice->buyer_address__nip ? 'NIP: '.$invoice->buyer_address__nip : ' '}}<hr>


      </tr></table>
  @else
   <p class='gray'>Nabywca:</p></center></td></tr><tr>
          <td valign='top' width='47%'>{!! str_replace("\n", "<br/>", ($invoice->seller_address)) !!}<hr></td>
          <td></td>
          <td valign='top' width='47%'>
          {{ $invoice->buyer_address__name }}<br>
          {{ $invoice->buyer_address__address }}<br>
          {{ $invoice->buyer_address__postal_code }}
          {{ $invoice->buyer_address__city }}<br>
          {{ $invoice->buyer_address__nip ? 'NIP: '.$invoice->buyer_address__nip : ' '}}

          <hr>
            <center><p class='gray'>Odbiorca:</p></center>
          {!! str_replace("\n", "<br/>", ($invoice->buyer_address_recipient)) !!}<hr></td>

      </tr></table>

@endif
      <br>

      <center><h2>{{$invoice->is_proforma ? 'Faktura '.$invoice->number : 'Faktura VAT '.$invoice->number }}</h2>
      @if ($duplicat)
      <h3>Duplikat z dnia {{date('d-m-Y',strtotime($now))}}</h3></center>
    @else
    @endif
      <table border=0 width='100%'><tr><td>
      <table border='0' width='100%' style='border-spacing: 0px 0px;'>
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
          <td class='fv_std_td' align='middle'>{{ $index + 1 }}</td>
          <td class='fv_std_td' align='left' width='35%'>{{ $product->pivot->product_name }} </td>
          <td class='fv_std_td' align='middle'>{{ $product->pivot->quantity }}</td>
          <td class='fv_std_td' align='middle'>szt. </td>
          <td class='fv_std_td' align='right'> {{ amount_pl($product->pivot->net_unit_price) }} </td>
          <td class='fv_std_td' align='middle'> 23% </td>
          <td class='fv_std_td' align='right'> {{ amount_pl(($product->pivot->net_unit_price*$product->pivot->quantity)) }} </td>
          <td class='fv_std_td' align='right'> {{ amount_pl((($product->pivot->gross_unit_price-$product->pivot->net_unit_price)*$product->pivot->quantity)) }} </td>
          <td class='fv_std_td' align='right'> {{ amount_pl(($product->pivot->gross_unit_price*$product->pivot->quantity)) }} </td>
      </tr>
              @endforeach
</table></td></tr></table>

      <br><br>
      <table border=0 width=100%><tr><td wdith=30%></td><td width=70% align=right>
      <table border=1 width=100%>
      <tr>
        <td class='gray_td'>Nazwa stawki VAT</td>
        <td class='gray_td'>wartość netto</td>
        <td class='gray_td'>kwota VAT</td>
        <td class='gray_td'>wartość brutto</td>
      <tr>
      <tr>
        <td class='fv_std_td'>Podstawowy podatek VAT 23% </td>
        <td class='fv_std_td' align=right>{{ amount_pl($sum_net) }} </td>
        <td class='fv_std_td' align=right>{{ amount_pl($sum_gross - $sum_net) }}</td>
        <td class='fv_std_td' align=right>{{ amount_pl($sum_gross) }} </td>
      </tr>
      </table>
      </td></tr></table>

      <br><br>
      <table border='0' width='100%'><tr>
      <td align='left' valign='top' width='50%'>  <table width='100%' border='0'><tr><td width=130>
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
   <table border='0' width='43%'><tr>
      <td align='left'><p class=''><b>Uwagi:</b></p></td><td></td></tr><tr>
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
   <table border='0' width='43%'><tr>
      <td align='left'><p class=''><b>Uwagi:</b></p></td><td></td></tr><tr>
          <td>{{ $invoice->comments }}</td>

      </tr></table>
      <br>


@endif
      </td>

      @endif
      <td align='right' valign=top>

              <table width='90%'><tr>
              @if ($invoice->pay_type == 'cash')
              <td class='do_zaplaty'>&nbsp &nbsp <b>Zapłacono razem:</b></td>
              @else
              <td class='do_zaplaty'>&nbsp &nbsp <b>Razem do zapłaty:</b></td>
              @endif
              <td  class='do_zaplaty' align='right' width='40%'> <b>{{ amount_pl($sum_gross, true) }}</b></td></tr></table>
              <table width='90%'><tr><td align='right'><b>Słownie:</b> <font size=2>

                           {{ amount_literally($sum_gross) }}
</font></td></tr></table>
      </td>







      <table border='0' width='100%'><tr>
      <td align='middle'><p class='gray'><b>Wystawił(a):</b></p></td><td></td></tr><tr>
          <td valign='top' width='43%' class='std_td' height='90'><center>&nbsp</center></td>
          <td></td>

      </tr></table>
      <br>
    </td></tr>
    </table>


</body>
</html>
