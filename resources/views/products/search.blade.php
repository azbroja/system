@extends('layouts.app')
@section('content')







<h1>
@if ($products->isNotEmpty())
    Wyniki dla: {{$needle}}
@else
    Brak wyników
@endif

</h1>

<table class="table-hover" border="1" cellspacing="1" cellpadding="10">


<thead>

<tr>
<td> LP </td>
<td> Dane produktu </td>
<td> Symbol produktu </td>
<td> Cena netto </td>


</tr>
</thead>
@foreach ($products as $index=>$product)

  <tbody>
      <tr>
      <td> {{ $index+1 }} </td>

      <td>
      <a href="/product/update/{{$product->id}}">
{{ $product->name }}  </a>
          </td>
          <td>{{$product->symbol}}</td>
          <td>{{$product->selling_price }}</td>



</td>
@endforeach

 </tr>


</tbody>
</table>
<br />



</div>

@endsection
