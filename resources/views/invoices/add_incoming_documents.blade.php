@extends('layouts.app')

@section('content')


<div class="container">
    <div class="">
        <div class="">
            <div class="card">
                <div class="card-header"></div>
                <nav>
                <div style="margin: 10px; float: right;">

                <a href="{{ url('customer/incoming/create/') }}"><button class="btn btn-dark">Dodaj Dostawcę</button></a>
@can('regulation_invoices')
                <a href="{{ url('/invoices-incoming/search') }}"><button class="btn btn-danger">Wyszukaj Dokument</button></a>
                <a href="{{ url('/invoices-incoming/made') }}"><button class="btn btn-primary">Rozliczenia</button></a>
@endcan
</div>
</nav>




                        <div>
                            <!-- <input type="search" id="filter" class="form-control" placeholder="Wybierz dostawcę"> -->


                        </div>

                        <div>

                        </div>

                        <form method="post" action="{{ route('add-incoming-invoices') }}" id="sendSuppliersForm">
@csrf


<div id="suppliers-list" ></div>
<input type="submit" class="btn btn-primary" value="Zapisz fakturę" style="margin: 10px; float: right;">
</form>
<br />




<h1>Dokumenty zakupu</h1>

</div>


                    @if ($invoices === null)

                    @else

                    <table class="table-hover" border="1" cellspacing="1" cellpadding="10">
                        <thead>
                            <tr>
                            <td> LP </td>
                            <td> Data powstania dokumentu</td>
                                <td> Termin płatności dokumentu</td>
                                @can('regulation_invoices')

                                <td> Po termine </td>
                                @endcan
                                <td> Nazwa Klienta </td>
                                <td> Numer Dokumentu</td>
                                @can('regulation_invoices')

                                <td> Status</td>
                                @endcan
                                <td> Kwota</td>
                                <td> Użytkownik </td>

                            </tr>
                        </thead>
                        @foreach ($invoices as $index=>$invoice)
                        <tbody>
                            <tr>
                            <td> {{ $index+1 }}


                </td>
                <td> {{ date('d-m-Y',strtotime($invoice->issued_at)) }} </td>

                <td> {{ date('d-m-Y',strtotime($invoice->pay_deadline)) }} </td>
                @can('regulation_invoices')

                @if ($invoice->is_paid)
                <td>Zapłacono</td>
                @else
                    @if (($invoice->pay_deadline->diffInDays($now, false)) > 7)
                        <td class='errormsg'>{{ $invoice->pay_deadline->diffInDays($now, false) }} </td>
                    @else
                        <td>{{ $invoice->pay_deadline->diffInDays($now, false) }} </td>
                    @endif
                @endif
                @endcan



                                <td><a href="{{ url('customer/update/'.$invoice->customer_id) }}" >{{ $invoice->seller_address }}<b> </td>
          <td> <div style="float: left;">{{ $invoice->number }}</div>
          @can('regulation_invoices')
                            @if (!$invoice->is_paid)
                            <a href="{{route('made-invoice-protocol', $invoice->id) }}" class="btn btn-danger" >R</a>
                            @elseif ($invoice->is_paid)
                            <a href="{{ route('unmade-invoice-protocol', $invoice->id) }}" class="btn btn-dark" >R</a>
                @endif
                @endcan
                </td>
                @can('regulation_invoices')

          @if ($invoice->is_paid)
                <td>Rozliczono</td>
                @else
                <td>Nie rozliczono</td>
                @endif
                @endcan

                <td>{{ amount_pl($invoice->total_value) }}</td>

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
<div class="pagination">
{{ $invoices->links() }}
</div>


@endsection
@section('scripts')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue"></script>

<script>
var app = new Vue({
  el: '#suppliers-list',
  data: {
    supplier: null,
    grossPrice: null,
  },
  computed: {
    supplierNetPrice: function() {
            if (this.supplier === null) {
                return '';
            }
            return Math.round((this.supplier.grossPrice / 1.23) * 100) / 100;
        }
  },
  template: `
  <table style="border: 1px solid black;" class="table">
    <thead class="thead-dark">
        <tr>
            <th>Dostawca</th>
            <th>Typ faktury</th>
            <th>Numer Dokumentu</th>
            <th>Termin płatności</th>
            <th>Cena netto</th>
            <th>Cena Brutto</th>
                    </tr>
    </thead>
    <tbody style="font-family: Arial;">
    <tr>
    <td>
        <select class="js-example-basic-single"  id="supplierId" name="supplierId"></select>
    </td>
    <td>
    <select id="invoice-type">
        <option value="cost">Koszt</option>
        <option value="resale">Odsprzedaż</option>
    </select>
</td>
    <td><input class="invoice-number" type="text" name="invoiceNumber" required></td>
    <td><input class="term-payment" type="date" value="" required></td>
    <td><input class="net-value"  type="text" :value="grossPrice !== null ? Math.round((grossPrice.replace(/\,/g, '.') / 1.23) * 100) / 100 : ''" name="netPrice" required></td>
    <td><input class="gross-value" type="text" v-model="grossPrice" name="grossPrice" min="1" required></td>
    </tr>

    </tbody>
</table>
`,

})
</script>
<script>
    const customerSupplier = {!!$customerSupplier!!};
    const userID = {!!$userLogin->id!!};


</script>
<script src="{{ asset('js/addIncomingInvoice.js') }}"></script>

@endsection
