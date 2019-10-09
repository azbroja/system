@extends('layouts.app')

@section('content')


<div class="container">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header"></div>
                <br>


                <br>

                <h1>Wyszukaj dokument</h1>
                <div class="list">
                    <div class="search">
                        <form method="GET" action="search">
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

                    @if ($invoices === null)

                    @else

                    <table class="table-hover" border="1" cellspacing="1" cellpadding="10">
                        <thead>
                            <tr>
                            <td> LP </td>
                                <td> Data powstania dokumentu</td>
                                <td> Nazwa Klienta </td>
                                <td> Numer Dokumentu</td>
                                <td> Status</td>
                                <td> Kwota netto</td>
                                <td> Kwota brutto</td>
                                <td> Użytkownik </td>

                            </tr>
                        </thead>
                        @foreach ($invoices as $index=>$invoice)
                        <tbody>
                            <tr>
                            <td> {{ $index+1 }}


                </td>
                                <td> {{ date('d-m-Y',strtotime($invoice->issued_at)) }} </td>
                                <td><a href="{{ url('customer/update/'.$invoice->customer_id) }}">{{ $invoice->buyer_address_ }}<b> </td>
          <td>{{ $invoice->number }}
          @can('update_invoices')
                            @if ((!$invoice->is_paid) && ($documentType == 'Invoice'))
                            <a href="{{route('made-invoice-protocol', $invoice->id) }}" class="btn btn-danger" >R</a>
                            @elseif (($invoice->is_paid) && ($documentType == 'Invoice'))
                            <a href="{{ route('unmade-invoice-protocol', $invoice->id) }}" class="btn btn-dark" >R</a>
                @endif
                @endcan
                </td>
          @if ($invoice->is_paid)
                <td>Rozliczono</td>
                @else
                <td>Nie rozliczono</td>
                @endif
                <td>{{ $invoice->net_value }}</td>

                <td>{{ $invoice->total_value }}</td>

                <td>{{ $invoice->customer->user->name }} {{ $invoice->customer->user->surname }}</td>

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
