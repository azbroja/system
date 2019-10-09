@extends('layouts.app')

@section('content')


<div class="container">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header"></div>








<h1>Dokumenty zakupu</h1>

<div>
Suma należności brutto <strong>{{ str_replace('.', ',', $invoices->sum('total_value')) }}</strong>
</div>

                <div class="list">
                    <div class="search">
                        <form method="GET" id="search" action="search">
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

<label for="name" class="col-md-4 col-form-label"><strong>{{ __('Podaj Nazwę Klienta') }}</strong></label>

<div class="col-md-6">
    <input id="name" type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }} search-input" name="name" value="{{ $name }}" autofocus>

    @if ($errors->has('name'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('name') }}</strong>
        </span>
    @endif
</div>

<label for="isPaid" class="col-md-4 col-form-label"><strong>{{ __('Status rozliczenia') }}</strong></label>

<div class="col-md-6">
<select name="isPaid" class="form-control search-input">
<option value="false" {{ ($isPaid == 'false') ? 'selected' : ''}}>Nierozliczony</option>
                                <option value="1" {{ ($isPaid == '1') ? 'selected' : ''}}>Rozliczony</option>


                            </select>

    @if ($errors->has('isPaid'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('isPaid') }}</strong>
        </span>
    @endif
</div>

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


<label for="" class="col-md-4 col-form-label"><strong>{{ __('Rodzaj płatności') }}</strong></label>
<div class="col-md-6">

<select name="typeOfPaid" class="form-control search-input">
                                <option value="">Wybierz rodzaj płatności</option>
                                <option value="transfer" {{ ($typeOfPaid == 'transfer') ? 'selected' : ''}} >Przelew</option>
                                <option value="cash" {{ ($typeOfPaid == 'cash') ? 'selected' : ''}}>Gotówka</option>

                            </select>
</div>










<br />

                            <button type="submit" class="fas fa-search left" formaction="search">Szukaj</button>

                        </form>
                    </div>
                    </div>
</div>


<div>



</div>
<form action="{{ url('/invoice/incoming/made/') }}" >
Rozlicz zbiorczo</h3>
<input value="0" readonly="readonly" type="text" id="total"/>



<button type="submit" class="btn btn-dark">ROZLICZ ZBIORCZO</button>

                    @if ($invoices === null)

                    @else

                    <table class="table-hover" border="1" cellspacing="1" cellpadding="10" id="invoices-table">
                        <thead>
                            <tr>
                            <td> LP </td>
                            <td> Wybierz </td>
                            <td> Termin płatności dokumentu</td>
                            <td> Data powstania dokumentu</td>
                            <td> Data aktualizacji dokumentu</td>
                                <td> Po termine </td>
                                <td> Nazwa Klienta </td>
                                <td> Numer Dokumentu</td>
                                <td> Status</td>
                                <td> Kwota</td>
                                <td> Użytkownik </td>

                            </tr>
                        </thead>
                        @foreach ($invoices as $index=>$invoice)
                        <tbody id="invoices-list">
                            <tr>
                            <td> {{ $index+1 }}


                </td>

                <td><input type="checkbox" name="invoices[]" value="{{ $invoice->id }}" data-price="{{$invoice->total_value}}"></td>
                <td> {{ date('d-m-Y',strtotime($invoice->pay_deadline)) }} </td>

                <td> {{ date('d-m-Y',strtotime($invoice->issued_at)) }} </td>
                <td> {{ date('d-m-Y',strtotime($invoice->updated_at)) }} </td>


                @if ($invoice->is_paid)
                <td>Zapłacono</td>
                @else
                    @if (($invoice->pay_deadline->diffInDays($now, false)) > 7)
                        <td class='errormsg'>{{ $invoice->pay_deadline->diffInDays($now, false) }} </td>
                    @else
                        <td>{{ $invoice->pay_deadline->diffInDays($now, false) }} </td>
                    @endif
                @endif



                                <td><a href="{{ url('customer/update/'.$invoice->customer_id) }}">{{ $invoice->seller_address }}<b> </td>
          <td><input type="text" name="" id="" value="{{ $invoice->number }}">
          @can('update_invoices')
                            @if (!$invoice->is_paid)
                            <a href="{{route('made-invoice-protocol', $invoice->id) }}" class="btn btn-danger" >R</a>
                            @elseif ($invoice->is_paid)
                            <a href="{{ route('unmade-invoice-protocol', $invoice->id) }}" class="btn btn-dark" >R</a>
                @endif
                @endcan
                </td>
          @if ($invoice->is_paid)
                <td>Rozliczono</td>
                @else
                <td>Nie rozliczono</td>
                @endif

                <td>{{ $invoice->total_value }}</td>

                <td>{{ $invoice->user()->first()->name }} {{ $invoice->user()->first()->surname }}</td>

                            </tr>



                            @endforeach
                        </tbody>
                    </table>

                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</form>


@endsection
@section('scripts')


<script src="{{ asset('js/incomingCheckbox.js') }}"></script>

@endsection
