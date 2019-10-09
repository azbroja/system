@extends('layouts.app')
@section('content')










<h1>
@if ($customers->isNotEmpty())
    Wyniki dla: {{$needle}}
@else
@endif


</h1>
@if ($customers->isNotEmpty())

<table class="table-hover" border="1" cellspacing="1" cellpadding="10">


<thead>

<tr>
<td> LP </td>
<td> Link do klienta </td>
<td> Użytkownik </td>



</tr>
</thead>
@foreach ($customers as $index=>$customer)

  <tbody>
      <tr>
      <td> {{ $index+1 }} </td>

      <td>
      <a href="/customer/update/{{$customer->id}}">
{{ $customer->name }}  </a>
          </td>

          <td> {{ $customer->user->name }} {{ $customer->user->surname }}  </td>






</td>
@endforeach

 </tr>


</tbody>
</table>
<br />



</div>

<div class="pagination">
{{ $customers->appends(Request::all())->links() }}
</div>

@else
@endif

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
