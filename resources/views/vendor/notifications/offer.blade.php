
 <!DOCTYPE html>


<html>
  <head>
<meta charset="utf-8">

  <title></title>

<style>
     td {font-family: Arial; font-size: 15px; line-height: 105%; }
    .gray {background-color: #e0e0e0; margin-top: 2; margin-bottom: 2; }
    .gray_td { font-weight: bold; text-align: center; border-collapse: collapse; padding: 1px; vertical-align: bottom; font-family: Arial; font-size: 15px; line-height: 100%; }
    .gray_wide {background-color: #e0e0e0; margin-top: 5; margin-bottom: 2; }
    .fv_std_td{ border-collapse: collapse; empty-cells: show; margin: 2px; padding: 3px; text-align: center; font-family: Arial; font-size: 15px; line-height: 90%;}
    .do_zaplaty{ background-color: #e0e0e0; border: 1px solid #000000; border-collapse: collapse; empty-cells: show; margin-top: 5px; font-size: 15px; padding: 5px;}
    .std_td{ border: 1px solid #000000; border-collapse: collapse; empty-cells: show; margin: 2px; font-size: 10px; }
    font {font-size: 15px; font-family: Arial; line-height: 135%; }
    .big {font-size: 20px; font-family: Arial; font-weight: bold;}
    hr { height: 1px; background-color: none; color: slategray;}
    selektor { text-indent: 50px; f}
</style>

  </head>
  <body LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
  <html>
   <head>
     <meta http-equiv="content-type" content="text/html; charset=utf-8">
   </head>
   <body text="#000000" bgcolor="#FFFFFF">
     <meta http-equiv="content-type" content="text/html; charset=utf-8">
     <div style="width:700px; font-family:arial; ">
       <div style="width:700px; height: 180px;
background:url('https://www..pl/img/top7.jpg')
         no-repeat; "> </div>
       <div style="padding:0px; padding-bottom:0px;"><br>

<table border=0 width=95%><tr><td>
<table border='0' width='100%'>
  <tr><td valign='top'>

  </td>
  <td  valign='top' align=right>Kraków, {{ date('d-m-Y', strtotime($offer->issued_at)) }}</td></tr></table>
  <table border=0 width=100%><tr>



  <td  valign='top' align=right><Br><table width=40% border=0><tr><td><font>
    <strong>{{$customer->name }} </strong><br />
    {{ $customer->street }} <br />
    {{ $customer->postal_code.' '.$customer->city }}<br />
    <strong>{{ $contactPerson ? 'Sz. P. '.$contactPerson->name : ' '}} {{$contactPerson ? $contactPerson->surname : ' ' }}</strong> <br />
  </b></font><br><br></td></tr></table>



  </td></tr></table><br>

<div style="text-align:center; font-size: 20px; font-weight: bold; margin-bottom: 25px;">
    PROPOZYCJA HANDLOWA
  </div>


<div style="text-indent: 50px; line-height:1.2">Szanowni Państwo,
        firma  wychodząc Państwu na przeciw stworzyła w 2006 roku linię produkcyjną wytwarzającą wysokiej jakości regenerowane wkłady do drukarek laserowych oraz atramentowych.<br> W nawiązaniu do przeprowadzonej rozmowy przedstawiamy Państwu naszą ofertę cenową oraz zapewniamy:</p>
        </div>
        <div style="line-height:1.2">
    <ul>
    <li> bezpłatny transport,
    <li> utylizację i skup zużytych kaset produkcji  w trosce o środowisko,
    <li> dożywotnią gwarancję na tonery laserowe,
    <li> faktury przelewowe,
    <li> fachowe porady z zakresu sprzętu drukującego,
    <li> sprzedaż drukarek nowych oraz poleasingowych,
    <li> darmowy test tonera firmy .
</ul>
<p style='text-indent: 1cm'>Każdego klienta traktujemy indywidualnie, więc jeśli Państwo mają jakieś sugestie co do tej propozycji handlowej, prosimy o kontakt, a na pewno zaspokoimy Państwa oczekiwania.<br>
Oto wstępne warunki cenowe:<br></p>




      <table width=100%>
      <tr>
          <td class='gray_td' NOWRAP>Nazwa Drukarki:</td>
          <td class='gray_td' NOWRAP>Symbol Produktu:</td>
          <td class='gray_td' NOWRAP>Cena sprzedaży:</td>
          <td class='gray_td' NOWRAP>Cena skupu:</td>
      </tr>
      @foreach ($offer->products->sortByDesc('name') as $product)

<tr>
    <td class='fv_std_td' align='left'>{{ $product->pivot->product_name }} </td>
    <td class='fv_std_td' align='left'>{{ $product->symbol }} </td>
    <td class='fv_std_td' align='middle' NOWRAP>{{ $product->pivot->selling_customer_price }} zł + VAT</td>
    <td class='fv_std_td' align='middle' NOWRAP>{{ $product->pivot->consumed_customer_price }} zł</td>
        @endforeach

      </tr>  </table>
              <br><br>


              ________________<br>

              <font>Z poważaniem,<br>{{ $user->name.' '.$user->surname }} <br>{{ $user->email }} <br> Tel. {{ $user->telephone }} </font>
              </td></tr></table></center>

           <br>
           <b>Śledź nasze profile w mediach społecznościowych</b><br>
         </p>
    
         <b>Co</b><b> tydzień wyjątkowe promocje dostępne tylko tam!<br>
         </b><small><br>
         </small>
         <p class="MsoNormal"
style="margin:0px;color:rgb(34,34,34);font-family:arial,sans-serif;font-size:12.8px;font-style:normal;font-variant-ligatures:normal;font-variant-caps:normal;font-weight:400;letter-spacing:normal;text-indent:0px;text-transform:none;white-space:normal;word-spacing:0px;background-color:rgb(255,255,255);text-decoration-style:initial;text-decoration-color:initial;text-align:justify"><small><small><a
                 class="policy"
href="https://.pl/polityka-prywatnosci/">Polityka


                 Prywatności</a> </small></small></p>
       </div>
       <div style="width:700px; height: 180px;
background:url('https://www..pl/img/bottom7.jpg')
         no-repeat;) no-repeat top; font-weight:bold; color:#fff;
         font-family:arial; font-size:9px; line-height:2px;">
         <div style="width:190px; float:left; height:110px;
           padding-left:40px; padding-top:50px;">
           <p><font color="#000000"></font></p>
           <font color="#000000"> </font>
           <p><font color="#000000">Al. Jana Pawła II 150/113 </font></p>
           <font color="#000000"> </font>
           <p><font color="#000000">31-982 Kraków</font></p>
           <font color="#000000"> </font></div>
         <font color="#000000"> </font>
         <div style="width:190px; float:left; height:110px;
           padding-left:40px; padding-top:50px;">
           <p><font color="#000000"><a style="text-decoration: none;"
href="mailto:biuro@.pl">biuro@.pl</a></font></p>
           <p><font color="#000000"><a style="text-decoration: none;"
href="http://www..pl">www..pl</a></font></p>
           <p><font color="#000000"><a style="text-decoration: none;"
href="https://pl-pl.facebook.com/pages/DP-/114798575244223">Fanpage  na facebook</a></font></p>
         </div>
         <font color="#000000"> </font>
         <div style="width:180px; float:left; height:110px;
           padding-left:60px; padding-top:50px;"><font color="#000000">
</font>
           <p><font color="#000000">tel. 12 631 06 66</font></p>
           <font color="#000000"> </font>
           <p><font color="#000000">fax 12 631 06 65</font></p>
           <font color="#000000"> </font>
           <p><font color="#000000"> tel. kom. 664 74 19 19 </font></p>
         </div>
       </div>
     </div>
     <br/>






              </body>
             </html>
