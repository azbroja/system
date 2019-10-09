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

                <div class="card-header">{{ $updateOrder ? 'Edytuj zlecenie sprzedaży' : 'Dodaj zlecenie sprzedaży' }} </div>
                <div class="card-body">

                    @csrf



                    <label for="buyer_address_" class="col-md-4 col-form-label">{{ __('Dane Odbiorcy/Nabywcy') }}</label>
                            <div class="col-md-12">
                                <input id="buyer_address_" type="text" class="form-control{{ $errors->has('buyer_address_') ? ' is-invalid' : '' }}" name="buyer_address_" value="{{ $customer->name.', '.$customer->street.', '.$customer->postal_code.' '.$customer->city.', NIP: '.$customer->nip }}" autofocus disabled>
                                @if ($errors->has('buyer_address_'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('buyer_address_') }}</strong>
                                </span>
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
<label for="addresDelivery" class="col-md-4 col-form-label">{{ __('Adres Dostawy') }}</label>

<div class="col-md-12">
    <textarea rows="4" id="addressDelivery" type="text" class="form-control{{ $errors->has('addressDelivery') ? ' is-invalid' : '' }}" name="addressDelivery" autofocus>{{ $updateOrder ? $buyer_address_delivery : $customer->address_delivery }}</textarea>

    @if ($errors->has('addressDelivery'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('addressDelivery') }}</strong>
        </span>
    @endif

    </div>

                        <label for="payType" class="col-md-4 col-form-label">{{ __('Forma Płatności') }}</label>
                        <div class="col-md-12">
                            <div>
                                <select id="payType" class="form-control" name="payType">
                                    <option {{ $pay_type == 'transfer' ? 'selected' : '' }} value="transfer">Przelew</option>
                                    <option {{ $pay_type == 'cash' ? 'selected' : '' }} value="cash">Gotówka</option>
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


                                    <select id="payTerm" class="form-control" name="payTerm">
                                        @foreach($paydays as $payday)
                                            <option {{ $pay_term == $payday ? 'selected' : '' }} value="{{ $payday }}">{{ $payday }}</option>
@endforeach
                                    </select>

                                </div>
                                @if ($errors->has('payTerm'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('payTerm') }}</strong>
                                </span>
                                @endif
</div>

                        <label for="invoiceComments" class="col-md-4 col-form-label">{{ __('Uwagi do faktury') }}</label>
                        <div class="col-md-12">


                            <input style="border-color: blue;"  id="invoiceComments" type="text" class="form-control form-control-success{{ $errors->has('invoiceComments') ? ' is-invalid' : '' }}"
                                name="invoiceComments" value="{{ $invoice_comments }}" autofocus>

                            @if ($errors->has('invoiceComments'))
                            <span class="invalid-feedback" role="alert">
<strong>{{ $errors->first('invoiceComments') }}</strong>
</span>
                            @endif
</div>


                        <label for="comments" class="col-md-4 col-form-label">{{ __('Uwagi do zlecenia') }}</label>
                        <div class="col-md-12">



                            <textarea rows="4" id="comments" type="text" class="form-control{{ $errors->has('comments') ? ' is-invalid' : '' }}"
                                name="comments" value="" autofocus>{{ $comments }}</textarea>
                            @if ($errors->has('comments'))
                            <span class="invalid-feedback" role="alert">
<strong>{{ $errors->first('comments') }}</strong>
</span>
                            @endif
                            </div>

                            <label for="priority" class="col-md-4 col-form-label">{{ __('
Priorytet') }}</label>
                        <div class="col-md-12">
                            <div>
                                <select id="priority" class="form-control" name="priority">
                                    <option {{ $order_priority == '0' ? 'selected' : '' }} value="0">Standardowy</option>
                                    <option {{ $order_priority == '1' ? 'selected' : '' }} value="1">Wysoki</option>
                                </select>
                            </div>
                            @if ($errors->has('priority'))
                            <span class="invalid-feedback" role="alert">
<strong>{{ $errors->first('priority') }}</strong>
@endif
</div>


                            <label for="planned" class="col-md-4 col-form-label">{{ __('Data wysyłki') }}</label>

<div class="col-md-12">
    <input id="planned" type="date" class="form-control{{ $errors->has('planned') ? ' is-invalid' : '' }}" name="planned" value="{{ $order_planned }}" required autofocus>

    @if ($errors->has('planned'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('planned') }}</strong>
        </span>
    @endif
</div>

<label for="orderType" class="col-md-4 col-form-label">{{ __('Typ zlecenia') }}</label>
                        <div class="col-md-12">
                            <div>
                                <select id="orderType" class="form-control" name="orderType">
                                    <option {{ $order_type == '0' ? 'selected' : '' }} value="0">Wychodzące</option>
                                    <option {{ $order_type == '1' ? 'selected' : '' }} value="1">Przychodzące</option>
                                </select>
                            </div>
                            @if ($errors->has('orderType'))
                            <span class="invalid-feedback" role="alert">
<strong>{{ $errors->first('orderType') }}</strong>
@endif
</div>

<label for="collection" class="col-md-4 col-form-label">{{ __('Typ dostawy') }}</label>
                        <div class="col-md-12">
                            <div>
                                <select id="collection" class="form-control" name="collection">
                                    <option {{ $collection == 'courier' ? 'selected' : '' }} value="courier">Kurier</option>
                                    <option {{ $collection == 'driver' ? 'selected' : '' }} value="driver">Kierowca</option>
                                    <option {{ $collection == 'own' ? 'selected' : '' }} value="own">Odbiór Osobisty</option>

                                </select>
                            </div>
                            @if ($errors->has('collection'))
                            <span class="invalid-feedback" role="alert">
<strong>{{ $errors->first('collection') }}</strong>
@endif
</div>

<label for="documentType" class="col-md-4 col-form-label">{{ __('Typ Dokumentu') }}</label>
                        <div class="col-md-12">
                            <div>
                                <select id="documentType" class="form-control" name="documentType">
                                    <option {{ $document_type == '0' ? 'selected' : '' }} value="0">Faktura VAT</option>
                                    <option {{ $document_type == '1' ? 'selected' : '' }} value="1">Inny</option>
                                </select>
                            </div>
                            @if ($errors->has('documentType'))
                            <span class="invalid-feedback" role="alert">
<strong>{{ $errors->first('documentType') }}</strong>
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
                            @if ($updateOrder)
                            <table style="border: 1px solid black;" class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>Wartość Netto {{ $order->total_sum_net() }}</th>
                                        <th>Wartość Brutto {{ $order->total_sum_gross() }}</th>
                                    </tr>
                                </thead>
                                <tbody >

                                </tbody>
                            </table>
                            <br>
                            @endif
                            <input type="button" class="btn btn-dark" id="addAll" value="Dodaj Wszystkie">

                            <input type="submit" class="btn btn-primary" id="save" value="{{ $updateOrder ? 'Zaktualizuj zlecenie' : 'Generuj Zlecenie' }}">

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
    const orderProducts = {!!$order_products!!};
    const updateOrder = {!! $updateOrder !!};
    const orderId = {!! $orderId !!};



</script>
<script src="{{ asset('js/hardwareOrder.js') }}"></script>

@endsection
