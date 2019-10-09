@extends('layouts.app')

@section('content')


<div class="container">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header"></div>
                <br>
                <nav class="customer-menu">

                <form method="get" action="/complaints/pdf?name=">
                        <select name="name" id="name">
                        <option value="maga">Magazyn</option>
                        <option value="micha">Michał</option>
                        <option value="dawi">Dawid</option>
                        <option value="damia">Damian</option>
                        <option value="sebas">Sebastian</option>
                        </select>

                        </select>
                        <button class="btn btn-dark" type="submit">Generuj Zestawienie PDF</button>
                        </form>
</nav>                <br>

                <h1>Wyszukaj reklamacje</h1>
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

<label for="isPaid" class="col-md-4 col-form-label"><strong>{{ __('Status rozpatrzenia') }}</strong></label>

<div class="col-md-6">
<select name="isPaid" class="form-control search-input">
<option value="">Wybierz status rozliczenia</option>
                                <option value="1" {{ ($isPaid == '1') ? 'selected' : ''}}>Rozpatrzona</option>
                                <option value="false" {{ ($isPaid == 'false') ? 'selected' : ''}}>Nierozpatrzona</option>

                            </select>

    @if ($errors->has('isPaid'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('isPaid') }}</strong>
        </span>
    @endif
</div>




<label for="name" class="col-md-4 col-form-label"><strong>{{ __('Podaj Producenta') }}</strong></label>

<div class="col-md-6">
    <input id="name" type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }} search-input" name="name" value="{{ $name }}" autofocus>

    @if ($errors->has('name'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('name') }}</strong>
        </span>
    @endif
</div>


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
                                <td> Data produkcji</td>
                                <td> Nazwa Klienta </td>
                                <td> Numer Reklamacji</td>
                                <td> Status</td>
                                <td> Producent </td>
                                <td> Toner </td>
                                <td> Uwagi </td>
                                <td> Użytkownik </td>

                            </tr>
                        </thead>
                        @foreach ($invoices as $index=>$invoice)
                        <tbody>
                            <tr>
                            <td> {{ $index+1 }}



                </td>
                                <td> {{ date('d-m-Y',strtotime($invoice->issued_at)) }} </td>
                                <td>{{ $invoice->made_date }}</td>

                                <td>{{ $invoice->customer->name}}<b> </td>
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
                <td>Rozpatrzono</td>
                @else
                <td>Nie rozpatrzono</td>
                @endif
                <td>{{ $invoice->worker }}</td>
                <td> @foreach ($invoice->products as $product)
                <li> {{ $product->name }}</li>
                @endforeach
                </td>

                <td> {{ $invoice->comments }}</td>

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
