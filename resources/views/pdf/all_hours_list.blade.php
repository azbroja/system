<style>body {
    font-family: DejaVu Sans;
    font-size: 12px;

}
table, td {
  border: 1px solid black;
}
</style>
<div class="container">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header"></div>



<div>
<strong><h2></h2>Karta Ewidencji Czasu Pracy </h2></strong><br>
Rok: <strong>{{ $now->year }}</strong>
Miesiąc: <strong>{{ $month }}</strong><br>
Imie: <strong>{{ $user->name }}</strong>
Nazwisko: <strong>{{ $user->surname }}</strong>
Stanowisko: <strong>{{ $user->workplace }}</strong><br>
Normatywny czas pracy w dniach <strong>poniedziałek - piątek</strong> od <strong>8</strong> do <strong> 12</strong><br>

<br>


<table style="text-align: center; font-size: 10px;">

      <thead>

<tr>
<td><strong> LP</strong> </td>
<td> <strong>Data </strong></td>
<td><strong> Opis Godzin Pracy</strong></td>

</tr>
</thead>
@foreach ($working_hours as $index=>$working_hour)
<tbody>
            <tr>
                <td> <strong>{{$index+1}}</strong></td>
                <td><strong>{{$working_hour->date }}</strong></td>
<td>
<strong>
     {{ $working_hour->name_of_hours == '1' ? '4' : '' }}
     {{ $working_hour->name_of_hours == '2' ? 'W' : '' }}
    {{ $working_hour->name_of_hours == '3' ? 'O' : '' }}
    {{ $working_hour->name_of_hours == '4' ? 'Wnż' : '' }}
     {{ $working_hour->name_of_hours == '5' ? 'Ch' : '' }}
    {{ $working_hour->name_of_hours == '6' ? 'N' : '' }}
     {{ $working_hour->name_of_hours == '7' ? 'M' : '' }}
    {{ $working_hour->name_of_hours == '8' ? 'K' : '' }}
    {{ $working_hour->name_of_hours == '9' ? 'P' : '' }}
    {{ $working_hour->name_of_hours == '10' ? 'ONZD' : '' }}

    </strong> </td>




@endforeach


       </tr>


</tbody>
</table>
<br />
<br>
Godziny faktycznie przepracowane: <strong>{{ $working_hours_8*4 }}</strong><br>
Suma godzin - Urlop Wypoczynkowy: <strong>{{ $working_hours_w*4 }}</strong><br>
Suma godzin - Urlop Okolicznościowy: <strong>{{ $working_hours_o*4 }}</strong><br>
Suma godzin - Urlop Wypoczynkowy Na żądanie: <strong>{{ $working_hours_wnz*4 }}</strong><br>
Suma godzin - Zwolnienie chorobowe L4: <strong>{{ $working_hours_ch*4 }}</strong><br>
Suma godzin - Nieobecność nieusprawiedliwiona: <strong>{{ $working_hours_n*4 }}</strong><br>
Suma godzin - Urlop Macierzyński/Wychowawczy: <strong>{{ $working_hours_m*4 }}</strong><br>
Suma godzin - Opieka: <strong>{{ $working_hours_k*4 }}</strong><br>
Suma godzin - Opieka nad zdrowym dzieckiem do 14 roku życia: <strong>{{ $working_hours_onzd*4 }}</strong><br>
Suma godzin - Inne Nieobecności Płatne: <strong>{{ $working_hours_p*4 }}</strong><br>



</div>
</div>
<div style="font-size: 9px">
<hr>
Opis skrótów:<br>
4 - Godziny faktycznie przepracowane<br>
                                    W - Urlop Wypoczynkowy <br>
                                    O - Urlop Okolicznościowy<br>
                                    Wnż - Urlop Wypoczynkowy Na żądanie<br>
                                    Ch - Zwolnienie chorobowe L4<br>
                                    N - Nieobecność nieusprawiedliwiona<br>
                                    M - Urlop Macierzyński/Wychowawczy<br>
                                    ONZD - Opieka nad zdrowym dzieckiem do 14 roku życia <br>
                                    K - Opieka <br>
                                    P - Inne nieobecności płatne<br>
                                    </div>
</div>
</div>
</div>
