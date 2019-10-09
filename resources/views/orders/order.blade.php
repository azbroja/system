<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Zlecenie {{$order->number}}</title>
<style>
body {
    font-family: "Times New Roman", Times, serif;
    font-size: 20px;
    margin: 10px;
    padding: 10px;
    background-color: white;
}

    strong:nth-child(3) {
        font-weight: normal;
    }
    strong:nth-child(5) {
        font-weight: normal;
    }


    </style>
</head>
<body>
<div class="table-responsive">
<table>
  <tr><td valign='top' fv_std_td>


  @if ($order->buyer_address_delivery)
  <strong> {!! str_replace("\n", "</strong> <br><strong> ", ($order->buyer_address_delivery)) !!}  </strong><br />
@else
<strong>{{$customer->name }}</strong> <br />

    {{ $customer->street }} <br />
    {{ $customer->postal_code.' '.$customer->city }}<br />
    NIP {{ $customer->nip}}<br />
    {{ $customer->telephone1}}


    <strong>{{ $contactPerson ? $contactPerson->name : ' '}} {{$contactPerson ? $contactPerson->surname : ' ' }} {{$contactPerson ? $contactPerson->telephone1 : ' ' }} </strong><br />
    @endif
    <br />


Handlowiec:   {{ $order->user->name }}   {{ $order->user->surname }} <br>


    <table border="1" cellspacing="1" cellpadding="10">
      <tr>
        <td class='gray_td'>Ilosć</td>
        <td class='gray_td'>Nazwa</td>
        <td class='gray_td'>Numer zlecenia</td>
        <td class='gray_td'>Cena skupu</td>

      </tr>
            @foreach ($order_products as $product)

      <tr>

<td><strong>{{ $product->pivot->quantity }}</strong></td>
        <td>{{ $product->pivot->product_name }}</td>
        <td> {{ $order->number }}</td>
        <td> {{ amount_pl($product->consumed_price) }} </td>

      </tr>
              @endforeach
</table></td>


<div>
      <table border='0' width='100%'><tr>
      <td align='left' valign='top' width='50%'>
          <table border='0' width='100%'><tr>
            <td valign=top width=70><b>Uwagi: </b></td>
            <td align=left valign=top>{!! str_replace("\n", "<br> ", ($order->comments)) !!}</td>
            </tr>
            </table>
            <br>
            <br>

            <br>

      </td>
      </div>




</div>

      </body>
</html>
