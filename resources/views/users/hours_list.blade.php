@extends('layouts.app')

@section('content')
<div class="container">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header">Lista Godzin i Rozmów</div>



<div>

<h1>
Lista Godzin i Rozmów</h1>



<table class="table-hover" border="1" cellspacing="1" cellpadding="10">

      <thead>

<tr>
<td> LP </td>
<td> Data </td>
<td> Ilość Godzin Pracy</td>
<td> Opis Godzin Pracy</td>
<td> Ilość Rozmów</td>

</tr>
</thead>
@foreach ($working_hours as $index=>$working_hour)
<tbody>
            <tr>
                <td> {{$index+1}}</td>
                <td>{{$working_hour->date }}</td>
<td>{{$working_hour->working_hours}}</td>
<td>

     {{ $working_hour->name_of_hours == '1' ? '8' : '' }}
     {{ $working_hour->name_of_hours == '2' ? 'Uw' : '' }}
    {{ $working_hour->name_of_hours == '3' ? 'Uo' : '' }}
    {{ $working_hour->name_of_hours == '4' ? 'Unż' : '' }}
     {{ $working_hour->name_of_hours == '5' ? 'L4' : '' }}
    {{ $working_hour->name_of_hours == '6' ? 'Nb' : '' }}
     {{ $working_hour->name_of_hours == '7' ? 'M' : '' }}
    {{ $working_hour->name_of_hours == '8' ? 'O' : '' }}
                                </td>


<td>{{ $working_hour->work_duration->format('%H:%I')  }}</td>

@endforeach


       </tr>


</tbody>
</table>
<br />
Średnia ilość rozmów w tym miesiącu
 {{ (sprintf('%d:%d', floor($working_hours->avg('telephone_hours') / 60), $working_hours->avg('telephone_hours') % 60))}}
<br>
Łączna ilość godzin pracy w tym miesiącu
{{ $working_hours->sum('working_hours') }}

</div>
</div>
</div>
</div>
</div>




@endsection
