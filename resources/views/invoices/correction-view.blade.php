


<html>
  <head>
  <meta http-equiv='content-type' content='text/html; charset=UTF-8'>
  <meta name='generator' content='pspad editor, www.pspad.com'>
  <title></title>



  </head>
  <body LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>



<table border='0' width='100%'>
  <tr><td valign='top'>

      <table border='0' width='100%'><tr>
          <td width='35%'>
            {!! str_replace("\n", "<br/>", ($invoice->seller_address)) !!}
            <br>
                        <br>
          Numer Konta bankowego: <br>Bank  {{ $seller->bank_account_number }}
<br>
Tel./Fax {{ $seller->fax }}
<br>
          <td width='30%'  align='middle' valign='top'> </td>
          <td  width='35%' align='middle'><p class='gray_wide'>Miejsce wystawienia:</p><b>{{ $invoice->place }}</b><p class='gray_wide'>Data wystawienia:</p><b>{{ date('d-m-Y', strtotime($invoice->issued_at)) }}</b><p class='gray_wide'>Data sprzedaży:</p><b>{{ date('d-m-Y', strtotime($parentInvoice->sell_date)) }}</b><hr></td>
      </tr></table>
     <br>

      <table border='0' width='100%'><tr>
      <td><center><p class='gray'>Sprzedawca:</p></center></td><td></td><td><center>
@if ($invoice->buyer_address_recipient == null)
        <p class='gray'>Nabywca:</p></center></td></tr><tr>
          <td valign='top' width='47%'>{!! str_replace("\n", "<br/>", ($invoice->seller_address)) !!}<hr></td>
          <td></td>
          <td valign='top' width='47%'>
          {{ $invoice->buyer_address__name }}<br>
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
          {{ $invoice->buyer_address__nip ? 'NIP: '.$invoice->buyer_address__nip : ' '}}<hr>


            <center><p class='gray'>Odbiorca:</p></center>
          {!! str_replace("\n", "<br/>", ($invoice->buyer_address_recipient)) !!}<hr></td>

      </tr></table>

@endif


      <center><h2>Faktura VAT KOREKTA nr {{ $invoice->number }}</h2>
        <b>do Faktury {{ $parentInvoice->number }} <b> data wystawienia: <b> {{ date('d-m-Y', strtotime($parentInvoice->issued_at)) }}, data sprzedaży: <b> {{ date('d-m-Y', strtotime($parentInvoice->issued_at)) }} </b>
            </center>


      <b>PRZED KOREKĄ: </b>

      <br>
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
            @foreach ($invoice_parent_products as $index => $product)

      <tr>
          <td class='fv_std_td' align='middle'>{{ $index + 1 }}</td>
          <td class='fv_std_td' align='left' width='35%'>{{ $product->name }} </td>
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
     <!--  -->

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
        <td class='fv_std_td' align=right>{{ amount_pl($parentInvoice->total_sum_net()) }} </td>
        <td class='fv_std_td' align=right>{{ amount_pl($parentInvoice->total_sum_gross()-$parentInvoice->total_sum_net()) }}</td>
        <td class='fv_std_td' align=right>{{ amount_pl($parentInvoice->total_sum_gross()) }} </td>
      </tr>
      </table>
      </td></tr></table>


     <br>
      <b> PO KOREKCIE: </b>

      <br>
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
          <td class='fv_std_td' align='left' width='35%'>{{ $product->name }} </td>
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
        <td class='fv_std_td' align=right>{{ amount_pl($invoice->total_sum_net()) }} </td>
        <td class='fv_std_td' align=right>{{ amount_pl($invoice->total_sum_gross()-$invoice->total_sum_net()) }}</td>
        <td class='fv_std_td' align=right>{{ amount_pl($invoice->total_sum_gross()) }} </td>
      </tr>
      </table>
      </td></tr></table>

</font></td></tr></table>


      <br>


      <table border=0 width=100%><tr><td wdith=30%></td><td width=70% align=left>
        <b> Korekta: </b>
      <table border=1 width=100%>
      <tr>
        <td class='gray_td'>Nazwa stawki VAT</td>
        <td class='gray_td'>wartość netto</td>
        <td class='gray_td'>kwota VAT</td>
        <td class='gray_td'>wartość brutto</td>
      <tr>
      <tr>
        <td class='fv_std_td'>Podstawowy podatek VAT 23% </td>
        <td class='fv_std_td' align=right>{{ amount_pl($invoice->total_sum_net()-$parentInvoice->total_sum_net()) }} </td>
        <td class='fv_std_td' align=right>{{ amount_pl(($invoice->total_sum_gross()-$invoice->total_sum_net())-($parentInvoice->total_sum_gross()-$parentInvoice->total_sum_net())) }}</td>
        <td class='fv_std_td' align=right>{{ amount_pl($invoice->total_sum_gross()-$parentInvoice->total_sum_gross()) }} </td>
      </tr>
      </table>
      </td></tr></table>


      <table border='0' width='100%'><tr>
      <td align='left' valign='top' width='50%'>  <table width='100%' border='0'>

        <tr><td width=130><b>Pozostało do zwrotu:</b></td><td>
                  <b>{{ amount_pl(abs($invoice->total_value))}} zł</b></td></tr>


        <tr><td width=130><b>Słownie:</b></td><td>
                  <b>{{ amount_literally(abs($invoice->total_value)) }}</b></td></tr>


{{--
                  <table width='90%'><tr><td align='right'><b>Słownie:</b> <font size=2>

                            --}}

                  <tr><td><b>W terminie do:</b></td><td><b> {{ date('d-m-Y', strtotime($invoice->pay_deadline)) }}<b></td></tr>
                  <tr><td valign=top><b>  </b></td><td></td></tr></table>
      </td>

      </td>
      </tr></table>


@if ($invoice->comments == null)

  @else
   <table border='0' width='43%'><tr>
      <td align='left'><p class=''><b>Przyczyna korekty:</b></p></td><td></td></tr><tr>
          <td><b>{{ $invoice->comments }}</b></td>

      </tr></table>
      <br>

      </tr></table>

@endif


      <table border='0' width='100%'><tr>
      <td align='middle'><p class='gray'><b>Wystawił(a):</b></p></td><td></td><td align='middle'><p class='gray'><b>Odebrał(a):</b></p></td></tr><tr>
          <td valign='top' width='43%' class='std_td' height='90'><center>&nbsp</center></td>
          <td></td>
          <td valign='top' width='43%' class='std_td'>&nbsp</td></tr><tr>
          <td><center><font size=1>Podpis osoby upoważnionej do wystawienia<br> Korekty Faktury VAT</font></center></td><td></td>
          <td><center><font size=1>Podpis osoby upoważnionej do odbioru<br> Korekty Faktury VAT</font></center></td>
      </tr></table>
      <br>
    </td></tr>
    </table>


</body>
</html>
