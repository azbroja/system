@extends('layouts.app')
@section('content')

<h1>
@if ($customers->isNotEmpty())
    Wyniki dla: {{$needle}}
@else
    Brak wyników
@endif

</h1>

<table class="table-hover" border="1" cellspacing="1" cellpadding="10">


<thead>

<tr>
<td> LP </td>
<td> Dane klienta </td>
<td> Osoba kontaktowa </td>
<td> Użytkownik </td>
</tr>
</thead>
@foreach ($customers as $index=>$customer)

  <tbody>
      <tr>
      <td> {{ $index+1 }} </td>

      <td>
      <a href="/customer/update/{{$customer->id}}">
{{ $customer->name }} {{$customer->street}} {{$customer->city}} {{$customer->nip}} </a>
          </td>
          <td>@if ($customer->contact_people()->count() > 0)
{{ $customer->contact_people()->first()->name }}
{{ $customer->contact_people()->first()->surname }}
@endif</td>

<td>          {{$customer->user->name }}   {{$customer->user->surname }}
</td>
@endforeach

 </tr>


</tbody>
</table>
<br />



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
