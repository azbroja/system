@extends('layouts.app')

@section('content')
<div class="container">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header">Karta Ewidencji Czasu Pracy</div>
@can('hours_raport')
                <nav class="customer-menu">
<form method="GET" action="/generate-hours-pdf/">

<a href="#"><button type="submit" class="btn btn-primary">Generuj Kartę</button></a>

                <select name="user_s">
    @foreach ($users as $user_s)
                <option value="{{ $user_s->id }}" {{ ($user_s->id == $userID) ? 'selected' : ''}}>
    {{ $user_s->name }} {{ $user_s->surname }}
</option>
@endforeach
</select>


<select name="month">
 <option value="1" {{ ($now->month == 1) ? 'selected' : ''}}>Styczeń</option>
 <option value="2" {{ ($now->month == 2) ? 'selected' : ''}}>Luty</option>
 <option value="3" {{ ($now->month == 3) ? 'selcted' : ''}}>Marzec</option>
 <option value="4" {{ ($now->month == 4) ? 'selected' : ''}}>Kwiecień</option>
 <option value="5" {{ ($now->month == 5) ? 'selected' : ''}}>Maj</option>
 <option value="6" {{ ($now->month == 6) ? 'selected' : ''}}>Czerwiec</option>
 <option value="7" {{ ($now->month == 7) ? 'selected' : ''}}>Lipiec</option>
 <option value="8" {{ ($now->month == 8) ? 'selected' : ''}}>Sierpień</option>
 <option value="9" {{ ($now->month == 9) ? 'selected' : ''}}>Wrzesień</option>
 <option value="10" {{ ($now->month == 10) ? 'selected' : ''}}>Październik</option>
 <option value="12" {{ ($now->month == 11) ? 'selected' : ''}}>Listopad</option>
 <option value="12" {{ ($now->month == 12) ? 'selected' : ''}}>Grudzień</option>

</select>

</form>
</nav>
@endcan
<div>
<div style="font-size: 15px">Karta Ewidencji Czasu Pracy </div> <br>
Rok: <strong>{{ $now->year }}</strong>
Miesiąc: <strong>{{ $now->month }}</strong><br>
Imie: <strong>{{ $user->name }}</strong>
Nazwisko: <strong>{{ $user->surname }}</strong>
Stanowisko: <strong>{{ $user->workplace }}</strong><br>
Normatywny czas pracy w dniach <strong>poniedziałek - piątek</strong> od <strong>8 </strong>do <strong>16</strong><br>

<br>


<table class="table-hover" border="1" cellspacing="1" cellpadding="10">

      <thead>

<tr>
<td> LP </td>
<td> Data </td>
<td> Opis Godzin Pracy</td>

</tr>
</thead>
@foreach ($working_hours as $index=>$working_hour)
<tbody>
            <tr>
                <td> {{$index+1}}</td>
                <td>{{$working_hour->date }}</td>
<td>

     {{ $working_hour->name_of_hours == '1' ? '8' : '' }}
     {{ $working_hour->name_of_hours == '2' ? 'W' : '' }}
    {{ $working_hour->name_of_hours == '3' ? 'O' : '' }}
    {{ $working_hour->name_of_hours == '4' ? 'Wnż' : '' }}
     {{ $working_hour->name_of_hours == '5' ? 'Ch' : '' }}
    {{ $working_hour->name_of_hours == '6' ? 'N' : '' }}
     {{ $working_hour->name_of_hours == '7' ? 'M' : '' }}
    {{ $working_hour->name_of_hours == '8' ? 'K' : '' }}
    {{ $working_hour->name_of_hours == '9' ? 'P' : '' }}

                                </td>




@endforeach


       </tr>


</tbody>
</table>
<br />
<br>
Godziny faktycznie przepracowane: <strong>{{ $working_hours_8*8 }}</strong><br>
Suma godzin - Urlop Wypoczynkowy: <strong>{{ $working_hours_w*8 }}</strong><br>
Suma godzin - Urlop Okolicznościowy: <strong>{{ $working_hours_o*8 }}</strong><br>
Suma godzin - Urlop Wypoczynkowy Na żądanie: <strong>{{ $working_hours_wnz*8 }}</strong><br>
Suma godzin - Zwolnienie chorobowe L4: <strong>{{ $working_hours_ch*8 }}</strong><br>
Suma godzin - Nieobecność nieusprawiedliwiona: <strong>{{ $working_hours_n*8 }}</strong><br>
Suma godzin - Urlop Macierzyński/Wychowawczy: <strong>{{ $working_hours_m*8 }}</strong><br>
Suma godzin - Opieka: <strong>{{ $working_hours_k*8 }}</strong><br>
Suma godzin - Inne Nieobecności Płatne: <strong>{{ $working_hours_p*8 }}</strong><br>



</div>
</div>
Legenda:<br>
8 - Godziny faktycznie przepracowane<br>
                                    W - Urlop Wypoczynkowy <br>
                                    O - Urlop Okolicznościowy<br>
                                    Wnż - Urlop Wypoczynkowy Na żądanie<br>
                                    Ch - Zwolnienie chorobowe L4<br>
                                    N - Nieobecność nieusprawiedliwiona<br>
                                    M - Urlop Macierzyński/Wychowawczy<br>
                                    K - Opieka <br>
                                    P - Inne nieobecności płatne<br>
</div>
</div>
</div>




@endsection
