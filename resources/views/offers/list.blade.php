@extends('layouts.customer')

@section('content')
<div class="container">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header">Lista dokumentów</div>
                @if ($user->id == 1)
@else
                <nav class="customer-menu">

                <a href="{{ url('customer/offer/create/'.$customer->id) }}"><button class="btn btn-primary">Dodaj Ofertę</button></a> <br>

</nav>
@endif
<div>
<h1>
Oferty
</h1>


        <table class="table-hover" border="1" cellspacing="1" cellpadding="10">


<thead>

<tr>
<td> Dane oferty </td>
<td> Data powstania </td>


</tr>
</thead>
@foreach ($offers as $offer)

  <tbody>
      <tr>
      <td>
<a href="{{ url('offer/'.$offer->id) }}" download="{{ 'OF_'.$offer->number }}">Oferta {{ $offer->number }} </a>



          </td>

          <td>{{ $offer->issued_at }}</td>


@endforeach

 </tr>


</tbody>
</table>
<br />


</div>
<div class="pagination">
{{ $offers->links() }}
</div>
</div>
</div>
</div>
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
