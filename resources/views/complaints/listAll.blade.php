@extends('layouts.app')

@section('content')
<div class="container">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header">Lista Reklamacji</div>
                <nav class="customer-menu">


</nav>
<div>

<h1>
Lista Reklamacji
</h1>


<table class="table-hover" border="1" cellspacing="1" cellpadding="10">

      <thead>

<tr>
<td> Nazwa Klienta </td>
<td> Handlowiec </td>
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

            @can('create_customers')
            <a href="{{ url('customer/update/'.$complaint->customer->id) }}">{{ $complaint->customer->name }}</a>
                @endcan

            @can('manage_complaints')
            {{ $complaint->customer->name }}</a>
                @endcan

                </td>
                <td>
                {{ $complaint->user->name }} {{ $complaint->user->surname }}

                </td>
                <td>
                <a href="{{ url('complaint/'.$complaint->id) }}" download="{{ 'RE_'.$complaint->number }}">{{ $complaint->number }} </a>


                </td>
                <td>{{ $complaint->issued_at->todatestring() }}</td>
                <td>{{ $complaint->comments }}</td>
                <td>{{ $complaint->worker }}</td>
                <td>{{ $complaint->made_date }}</td>
                <td> @foreach ($complaint->products as $product)
                <li> {{ $product->name }}</li>
                @endforeach
                </td>
                <td>
                @can('manage_complaints')

                {{ $complaint->message }} <a href="{{route('create-complaint-messageP', $complaint->id) }}" class="btn btn-warning" target="_blank"> KR</a>
                @endcan
                @can('create_customers')
                @if ($complaint->is_paid)
                <button disabled class="btn btn-dark">R</button></td>
                @else
    <a href="{{route('made-complaint', $complaint->id) }}" class="btn btn-danger" >R</a>
    @endcan

    @endif
    </td>

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
