@extends('layouts.app')

@section('content')


<div class="">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header"></div>








<h1>Rozliczenia</h1>
<div>
Suma należności brutto <strong style="font-family: Arial;">{{ str_replace('.', ',', $invoices->sum('total_value')) }}</strong>
</div>




</div>
<form action="{{ url('/invoice/incoming/made/') }}" >
Rozlicz zbiorczo</h3>
<input value="0" readonly="readonly" type="text" id="total" style="font-family: Arial;"/>



<button type="submit" class="btn btn-dark">ROZLICZ ZBIORCZO</button>

                    @if ($invoices === null)

                    @else

                    <table class="table-hover" border="1" cellspacing="1" cellpadding="10" id="invoices-table">
                        <thead>
                            <tr>
                            <td> LP </td>
                            <td> Wybierz </td>
                            <td> Numer Dokumentu</td>
                            <td> Nazwa Klienta </td>
                            <td> Termin płatności dokumentu</td>
                                <td> Po termine </td>
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
                <td><input type="text" name="" id="" value="{{ $invoice->number }}" style="font-family: Arial; width: 50%">
          @can('update_invoices')
                            @if (!$invoice->is_paid)
                            <a href="{{route('made-invoice-protocol', $invoice->id) }}" class="btn btn-danger" >R</a>
                            @elseif ($invoice->is_paid)
                            <a href="{{ route('unmade-invoice-protocol', $invoice->id) }}" class="btn btn-dark" >R</a>
                @endif
                @endcan
                </td>
                <td><a href="{{ url('customer/update/'.$invoice->customer_id) }}">{{ $invoice->seller_address }}<b> </td>
                <td> {{ date('d-m-Y',strtotime($invoice->pay_deadline)) }} </td>


                @if ($invoice->is_paid)
                <td>Zapłacono</td>
                @else
                    @if (($invoice->pay_deadline->diffInDays($now, false)) > 0)
                        <td class='errormsg'>{{ $invoice->pay_deadline->diffInDays($now, false) }} </td>
                    @else
                        <td>{{ $invoice->pay_deadline->diffInDays($now, false) }} </td>
                    @endif
                @endif





                <td style="font-family: Arial; text-align: right;">{{ amount_pl($invoice->total_value) }}</td>


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
