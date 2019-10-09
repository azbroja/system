@extends('layouts.customer')

@section('content')
<div class="">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header">Lista Reklamacji</div>
                <nav class="customer-menu">

                <a href="{{ url('customer/complaint/create/'.$customer->id) }}"><button class="btn btn-primary">Dodaj Reklamację</button></a> <br>
</nav>
<div>

<h1>
Lista Reklamacji
</h1>

<form method="get" action="{{route('create-invoice', ['customer' => $customer])}}">

<table class="table-hover" border="1" cellspacing="1" cellpadding="10">

      <thead>

<tr>
<td> Wybierz </td>
<td> Status zlecenia</td>
<td> Dane zlecenia </td>
<td> Data powstania </td>
<td> Opis reklamacji </td>
<td> Osoba odpowiedzialna </td>
<td> Data produkcji </td>
<td> Produkt </td>

<td> Akcja </td>

</tr>
</thead>
@foreach ($complaints as $complaint)

        <tbody>
            <tr>
            <td>
                @if ($complaint->is_paid)
                <input type="checkbox" value="{{ $complaint->id }}" name="complaints[]" disabled checked></td>
                @else
                <input type="checkbox" value="{{ $complaint->id }}" name="complaints[]"></td>
                @endif
                <td>
                @if ($complaint->is_paid)
                Rozpatrzona
                @else
                Do rozpatrzenia
                @endif
                </td>
                <td><a href="{{ url('complaint/'.$complaint->id) }}" download="{{ 'RE_'.$complaint->number }}">{{ $complaint->number }} </a></td>
                <td>{{ $complaint->issued_at->todatestring() }}</td>
                <td>{{ $complaint->comments }}</td>
                <td>{{ $complaint->worker }}</td>
                <td>{{ $complaint->made_date }}</td>
                <td> @foreach ($complaint->products as $product)
                <li> {{ $product->name }}</li>
                @endforeach
                </td>

                <td style="text-align:center;">
                <a href="{{ url('/customer/complaint/update/'.$complaint->id) }}" class="btn btn-danger" >E<a/>
                @if ($complaint->is_paid)
                <a href="{{route('update-complaint', $complaint->id) }}" class="btn btn-dark">R</a></td>
                @else
    <a href="{{route('made-complaint', $complaint->id) }}" class="btn btn-danger" >R</a>
    </td>

    @endif


@endforeach

       </tr>


</tbody>
</table>
<br />
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
