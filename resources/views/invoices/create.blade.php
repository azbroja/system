@extends('layouts.customer')
@section('content')
<style>

select {
    font: inherit;
    height: 125px;
    width: 100%;
}
</style>

        <div class="container">
            <div class="card">

                <div class="card-header">{{ $updateInvoice ? 'Edytuj fakturę sprzedaży' : 'Dodaj fakturę sprzedaży' }}</div>
                <div class="card-body">

                    @csrf

                    @if ($updateInvoice)
                    <label for="buyer_address_" class="col-md-4 col-form-label">{{ __('Dane Odbiorcy/Nabywcy') }}</label>
                            <div class="col-md-12">
                                <input id="buyer_address_" type="text" class="form-control{{ $errors->has('buyer_address_') ? ' is-invalid' : '' }}" name="buyer_address_" value="{{ $customer->name.' '.$customer->street.' '.$customer->postal_code.' '.$customer->city.' NIP: '.$customer->nip }}" autofocus >
                                @if ($errors->has('buyer_address_'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('buyer_address_') }}</strong>
                                </span>
                                @endif

<div>
                            <input type="text" id="customer_id" class="form-control" value="{{$customer->id}}">
                            </div>




                    @else
                    <label for="buyer_address_" class="col-md-4 col-form-label">{{ __('Dane Odbiorcy/Nabywcy') }}</label>
                            <div class="col-md-12">
                                <input id="buyer_address_" type="text" class="form-control{{ $errors->has('buyer_address_') ? ' is-invalid' : '' }}" name="buyer_address_" value="{{ $customer->name.' '.$customer->street.' '.$customer->postal_code.' '.$customer->city.' NIP: '.$customer->nip }}" autofocus disabled>
                                @if ($errors->has('buyer_address_'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('buyer_address_') }}</strong>
                                </span>
                                @endif
                    @endif
                            </div>
                            <label for="purchaser" class="col-md-4 col-form-label">{{ __('Dane Nabywcy') }}</label>
                            <div class="col-md-12">
                            <textarea rows="4" id="purchaser" type="text" class="form-control{{ $errors->has('purchaser') ? ' is-invalid' : '' }}" name="purchaser" autofocus disabled>{{ $customer->purchaser }}</textarea>
                                @if ($errors->has('purchaser'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('purchaser') }}</strong>
                                </span>
                                @endif
                            </div>


                        <label for="payType" class="col-md-4 col-form-label">{{ __('Forma Płatności') }}</label>
                        <div class="col-md-12">
                            <div>
                            <select id="payType" class="form-control" name="payType">
                                    @if ($payType == "transfer")
                                    <option value="transfer" selected>Przelew</option>
                                    <option value="cash">Gotówka</option>
                                     @else
                                        <option value="transfer">Przelew</option>
                                        <option value="cash" selected>Gotówka</option>
                                        @endif
                                    </select>
                            </div>
                            @if ($errors->has('payType'))
                            <span class="invalid-feedback" role="alert">
<strong>{{ $errors->first('payType') }}</strong>
</span>
                            @endif
                        </div>
                        <label for="payTerm" class="col-md-4 col-form-label">{{ __('Termin Płatności') }}</label>
                            <div class="col-md-12">
                                <div>
@if ($updateInvoice)
<input id="payTerm" type="date" class="form-control{{ $errors->has('planned') ? ' is-invalid' : '' }}" name="planned" value="{{ $payTerm->todatestring() }}" required autofocus>

@else
                                <select id="payTerm" class="form-control" name="payTerm">

@foreach($paydays as $payday)
@if ($payday == $payTerm)
<option value="{{ $payday }}" selected>{{ $payday }}</option>
@else
<option value="{{ $payday }}">{{ $payday }}</option>
@endif
@endforeach
</select>
@endif
                                </div>
                                @if ($errors->has('payTerm'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('payTerm') }}</strong>
                                </span>
                                @endif
</div>



                        <label for="comments" class="col-md-4 col-form-label">{{ __('Uwagi') }}</label>
                        <div class="col-md-12">



                            <textarea rows="4" id="comments" type="text" class="form-control{{ $errors->has('comments') ? ' is-invalid' : '' }}"
                                name="comments" value="" autofocus>{{$invoiceComments}}</textarea>
                            @if ($errors->has('comments'))
                            <span class="invalid-feedback" role="alert">
<strong>{{ $errors->first('comments') }}</strong>
</span>
                            @endif

<br>
                    <div class="">
                        <div>
                            <input type="search" id="filter" class="form-control" placeholder="Podaj nazwe/symbol produktu">


                        </div>
                        <div>
                            <select multiple class="select" id="id" name="q">
                            </select>
                        </div>

                        <form method="post" id="sendProductsForm">

                            <table style="border: 1px solid black;" class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Nazwa - Symbol</th>
                                        <th>Cena Sprzedaży</th>
                                        <th>Cena Zakupu</th>
                                        <th>Ilość</th>
                                        <th>Usuń</th>
                                    </tr>
                                </thead>
                                <tbody id="products-list">

                                </tbody>
                            </table>
                            <input type="button" class="btn btn-dark" id="addAll" value="Dodaj Wszystkie">

                            <input type="submit" class="btn btn-primary" value="{{ $updateInvoice ? 'Zaktualizuj fakturę' : 'Generuj Fakturę' }}">

                        </form>

</div>
</div>
</div>
</div>
</div>


@endsection

@section('scripts')

<script>
    const customerID = {!!$customer->id!!};
    const customerProducts = {!!$customer_products!!};
    const invoiceProducts = {!!$invoice_products!!};
    const updateInvoice = {!!$updateInvoice!!};
    const invoiceId = {!!$invoiceId!!};
    const proForma = {!!$proForma!!};


</script>
<script src="{{ asset('js/hardwareInvoice.js') }}"></script>

@endsection
