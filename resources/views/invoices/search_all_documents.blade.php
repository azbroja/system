@extends('layouts.app')

@section('content')


<div class="">
    <div class="">
        <div class="">
            <div class="card">
            <nav class="customer-menu">
            <a href="{{ url('/admin') }}"><button class="btn btn-dark">Zestawienia</button></a>

<a href="{{ url('workers-amount') }}"><button class="btn btn-dark">Pracownicy</button></a>
<a href="{{ url('/all-invoices-list') }}"><button class="btn btn-dark">Lista</button></a>
</nav>
                <div class="card-header"></div>
                <br>


                <br>

                <h1>Wyszukaj dokumenty</h1>
                <div class="list">
                    <div class="search">
                        <form method="GET" action="">
                            <br>
                            <div class="form-group text-center">
                            <div class="input-group" style="margin:auto;">

                            <label for="" class="col-md-4 col-form-label"><strong>{{ __('Podaj Zakres dat') }}</strong></label>

<div class="col-md-6">
<input class="form-control search-input" name="q1" value="{{ $needle1 }}" type="date" style="width: 300px; height: 45px">

<input class="form-control search-input" name="q2" value="{{ $needle2 }}" type="date" style="width: 300px; height: 45px">

    @if ($errors->has('q1'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('q1') }}</strong>
        </span>
    @endif
</div>

                            <label for="documentType" class="col-md-4 col-form-label"><strong>{{ __('Rodzaj dokumentu') }}</strong></label>

<div class="col-md-6">
<select name="documentType" class="form-control search-input">
                                <option value="Invoice" {{ ($documentType == 'Invoice') ? 'selected' : ''}}>Faktura</option>
                                <option value="Order" {{ ($documentType == 'Order') ? 'selected' : ''}}>Zlecenie</option>
                                <option value="Gift" {{ ($documentType == 'Gift') ? 'selected' : ''}}>Gratis</option>
                                <option value="Rubbish" {{ ($documentType == 'Rubbish') ? 'selected' : ''}}>Odpad</option>
                                <option value="Offer" {{ ($documentType == 'Offer') ? 'selected' : ''}}>Oferta</option>
                                <option value="Complaint" {{ ($documentType == 'Complaint') ? 'selected' : ''}}>Reklamacja</option>
                            </select>

    @if ($errors->has('documentType'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('documentType') }}</strong>
        </span>
    @endif
</div>




                            <label for="qNumber" class="col-md-4 col-form-label"><strong>{{ __('Podaj Numer Dokumentu') }}</strong></label>

<div class="col-md-6">
    <input id="qNumber" type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }} search-input" name="qNumber" value="{{ $qNumber }}" autofocus>

    @if ($errors->has('qNumber'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('qNumber') }}</strong>
        </span>
    @endif
</div>

<label for="isPaid" class="col-md-4 col-form-label"><strong>{{ __('Status rozliczenia') }}</strong></label>

<div class="col-md-6">
<select name="isPaid" class="form-control search-input">
<option value="">Wybierz status rozliczenia</option>
                                <option value="1" {{ ($isPaid == '1') ? 'selected' : ''}}>Rozliczony</option>
                                <option value="false" {{ ($isPaid == 'false') ? 'selected' : ''}}>Nierozliczony</option>

                            </select>

    @if ($errors->has('isPaid'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('isPaid') }}</strong>
        </span>
    @endif
</div>
@can('update_invoices')
<label for="" class="col-md-4 col-form-label"><strong>{{ __('Wybierz użytkownika') }}</strong></label>
<div class="col-md-6">
<select name="user" class="form-control search-input">
<option value="">Wybierz użytkownika</option>

@foreach($users as $user)

<option value="{{ $user->id }}" {{ ($userChose == $user->id) ? 'selected' : ''}}>
    {{ $user->name }} {{ $user->surname }}
</option>
@endforeach
</div>
</select>
</div>
@elsecan('create_invoices')
<label for="" class="col-md-4 col-form-label"><strong>{{ __('Użytkownik') }}</strong></label>
<div class="col-md-6">
<select name="user" class="form-control search-input">
<option value="two" {{ ($userChose == 'two') ? 'selected' : ''}}>Wybierz użytkownika</option>
<option value="{{$userLogin->id}}" {{ ($userChose == $userLogin->id) ? 'selected' : ''}} >{{$userLogin->name}} {{$userLogin->surname}}</option>
<option value="1" {{ ($userChose == '1') ? 'selected' : ''}}>Domyślny</option>
</div>
</select>
</div>
@endcan

<label for="" class="col-md-4 col-form-label"><strong>{{ __('Rodzaj płatności') }}</strong></label>
<div class="col-md-6">

<select name="typeOfPaid" class="form-control search-input">
                                <option value="">Wybierz rodzaj płatności</option>
                                <option value="transfer" {{ ($typeOfPaid == 'transfer') ? 'selected' : ''}} >Przelew</option>
                                <option value="cash" {{ ($typeOfPaid == 'cash') ? 'selected' : ''}}>Gotówka</option>

                            </select>
</div>










<br />

                            <button type="submit" class="fas fa-search left">Szukaj</button>

                        </form>
                    </div>
                    </div>
</div>
Po terminie:
<strong>{{$old_invoices}}</strong><br>

Suma netto <strong>{{ str_replace('.', ',', $invoices->sum('net_value')) }}</strong><br>
VAT <strong>{{ str_replace('.', ',', $invoices->sum('total_value') - $invoices->sum('net_value')) }}</strong><br>
Suma brutto <strong>{{ str_replace('.', ',', $invoices->sum('total_value')) }}</strong><br>
Odpady <strong>{{ str_replace('.', ',', $invoices->sum('value')) }}</strong>

                    @if ($invoices === null)

                    @else

                    <table class="table-hover" border="1" cellspacing="1" cellpadding="10">
                        <thead>
                            <tr>
                            <td> LP </td>
                                <td> Data powstania dokumentu</td>
                                <td> Data sprzedaży dokumentu</td>
                                <td> Numer Dokumentu</td>
                                <td> Nazwa Klienta </td>
                                <td> Ulica, Kod i Miasto </td>
                                <td> NIP</td>
                                <td> Kwota netto</td>
                                <td> VAT</td>
                                <td> Kwota brutto</td>
                                <td> Odpady </td>



                            </tr>
                        </thead>
                        @foreach ($invoices as $index=>$invoice)
                        <tbody>
                            <tr>
                            <td> {{ $index+1 }}


                </td>
                                <td> {{ date('d-m-Y',strtotime($invoice->issued_at)) }} </td>
                                <td> {{ date('d-m-Y',strtotime($invoice->issued_at)) }} </td>
                                <td>{{ $invoice->number }} </td>
                                <td>{{$invoice->buyer_address__name }}</td>
                                <td>{{$invoice->buyer_address__address }} {{$invoice->buyer_address__postal_code}} {{$invoice->buyer_address__city }} </td>
                                <td>{{ $invoice->buyer_address__nip ? $invoice->buyer_address__nip : 'BRAK'}}</td>



                <td>{{ str_replace('.', ',', $invoice->net_value) }}</td>
                <td>{{ str_replace('.', ',', ($invoice->total_value - $invoice->net_value))}}</td>
                <td>{{ str_replace('.', ',', ($invoice->total_value))}}</td>
                <td>{{ str_replace('.', ',', ($invoice->value))}}</td>





                            </tr>



                            @endforeach
                        </tbody>
                    </table>

<br>



                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>



@endsection
