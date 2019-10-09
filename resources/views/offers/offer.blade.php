
 <!DOCTYPE html>


<html>
  <head>
<meta charset="utf-8">

  <title></title>

<style>
     td {font-family: Times New Roman; font-size: 15px; line-height: 105%; }
    .gray {background-color: #e0e0e0; margin-top: 2; margin-bottom: 2; }
    .gray_td { font-weight: bold; text-align: center; border-collapse: collapse; padding: 1px; vertical-align: bottom; font-family: Times New Roman; font-size: 15px; line-height: 100%; }
    .gray_wide {background-color: #e0e0e0; margin-top: 5; margin-bottom: 2; }
    .fv_std_td{ border-collapse: collapse; empty-cells: show; margin: 2px; padding: 3px; text-align: center; font-family: Times New Roman; font-size: 15px; line-height: 90%;}
    .do_zaplaty{ background-color: #e0e0e0; border: 1px solid #000000; border-collapse: collapse; empty-cells: show; margin-top: 5px; font-size: 15px; padding: 5px;}
    .std_td{ border: 1px solid #000000; border-collapse: collapse; empty-cells: show; margin: 2px; font-size: 10px; }
    font {font-size: 15px; font-family: Times New Roman; line-height: 135%; }
    .big {font-size: 20px; font-family: Times New Roman; font-weight: bold;}
    hr { height: 1px; background-color: none; color: slategray;}
    selektor { text-indent: 50px; f}
</style>

  </head>
  <body LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>

<br>
<table border=0 width=95%><tr><td>
<table border='0' width='100%'>
  <tr><td valign='top'>
    {{$seller->name}}<br>
    {{$seller->street }}, {{$seller->postal_code }} {{$seller->city}}<br>
    {{ $seller->telephone1 }}, 664 113 519<br>
    {{ $seller->www.',  '.$seller->email }}<br>

  </td>
  <td  valign='top' align=right>Kraków, {{ date('d-m-Y', strtotime($offer->issued_at)) }}</td></tr></table>
  <table border=0 width=100%><tr>



  <td  valign='top' align=right><Br><table width=40% border=0><tr><td><font>
    <strong>{{$customer->name }} </strong><br />
    {{ $customer->street }} <br />
    {{ $customer->postal_code.' '.$customer->city }}<br />
    <strong>{{ $contactPerson ? 'Sz. P. '.$contactPerson->name : ' '}} {{$contactPerson ? $contactPerson->surname : ' ' }}</strong> <br />
  </b><br></font><br><br></td></tr></table>



  </td></tr></table><br>

<div style="text-align:center; font-size: 20px; font-weight: bold; margin-bottom: 25px;">
Oferta HANDLOWA
  </div>


<div style="text-indent: 50px; line-height:1.2">Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi corrupti sunt, reiciendis tempora id, sint quas dolore facere a perspiciatis maiores. Amet alias ad quaerat, eligendi ut exercitationem quasi! Error?
Debitis, expedita? Exercitationem, eos aperiam. Corrupti quidem ipsam modi deleniti pariatur nam, dicta adipisci, id molestias praesentium et repellat architecto vel dolorum perspiciatis debitis, officiis quae quas rem? Cumque, ea.
Voluptate iste modi pariatur? Quae atque molestiae, laboriosam quidem ad, ducimus culpa optio nostrum, perspiciatis tenetur reprehenderit minima tempora commodi sed minus itaque hic adipisci ratione nulla enim quia? Unde?
Tempore optio illo officia, voluptatibus autem totam praesentium ipsa labore fuga atque ab ut exercitationem dolore quos, sit sed perferendis dolor animi deleniti quod impedit, assumenda repellat. Itaque, molestias quis.
Illum, nemo. Quibusdam voluptatibus numquam aut quia ut voluptas earum eveniet soluta iusto obcaecati excepturi non odio ad, ipsa, facilis eaque quam, illo veniam assumenda. Quod nulla dolorem consectetur quae!
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
              <font>Z poważaniem,<br>{{ $user->name.' '.$user->surname }} <br>{{ $user->email }} <br> Tel. {{ $user->telephone }} </font>
              </td></tr></table></center>
              </body>
             </html>
