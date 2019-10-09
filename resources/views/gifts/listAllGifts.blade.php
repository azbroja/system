@extends('layouts.app')

@section('content')
<div class="container">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header">Lista Gratisów</div>



<div>

<h1>
Lista Gratisów
</h1>



<table class="table-hover" border="1" cellspacing="1" cellpadding="10">

      <thead>

<tr>
<td> Status protokołu</td>
@can('create_customers')

<td> Dane Klienta </td>
@endcan

<td> Numer protokołu </td>
<td> Data powstania </td>
<td> Kwota brutto</td>
<td> Uwagi </td>
<td> Akcje</td>
@can('create_customers')

<td> Protokół </td>
@endcan


</tr>
</thead>
@foreach ($gifts as $gift)

        <tbody>
            <tr>

                <td>
                @if ($gift->is_paid)
                Rozliczony
                @else
                Nie rozliczony
                @endif
                </td>
                @can('create_customers')

                <td>{{ $gift->buyer_address_ }}</td>
                @endcan
                <td>{{ $gift->number }} </td>
                <td>{{ $gift->issued_at }}</td>
                <td>{{ $gift->total_value }}</td>

                <td>{{ $gift->comments }}</td>
@can('create_customers')
                @if ($gift->is_paid)
                <td><button disabled class="btn btn-dark">R</button></td>
                @else
                <td><a href="{{route('made-gift-protocol', $gift->id) }}" class="btn btn-danger" >R</a></td>
                @endif
@endcan
                <td>    <a href="{{route('gift-protocol', $gift->id) }}" class="btn btn-primary" download="{{ 'GRATIS_P_'.$gift->number }}">P</a>
</td>
@endforeach

       </tr>


</tbody>
</table>
<br />


<div class="pagination">
{{ $gifts->links() }}
</div>

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
