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

                <div class="card-header">{{ $updateGift ? 'Edytuj protokół gratisu' : 'Dodaj protokół gratisu' }}</div>
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




                        <label for="comments" class="col-md-4 col-form-label">{{ __('Uwagi do protokołu') }}</label>
                        <div class="col-md-12">



                            <textarea rows="4" id="comments" type="text" class="form-control{{ $errors->has('comments') ? ' is-invalid' : '' }}"
                                name="comments" value="" autofocus></textarea>
                            @if ($errors->has('comments'))
                            <span class="invalid-feedback" role="alert">
<strong>{{ $errors->first('comments') }}</strong>
</span>
                            @endif

<br>
                    <div class="">


                        <form method="post" id="sendProductsForm">

                            <table style="border: 1px solid black;" class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Nazwa</th>
                                        <th>Symbol</th>
                                        <th>Cena Sprzedaży Netto</th>
                                        <th>Ilość</th>
                                        <th>Usuń</th>
                                    </tr>
                                </thead>
                                <tbody id="products-list">

                                </tbody>
                            </table>

                            <input type="submit" class="btn btn-primary" id="save" value="{{ $updateGift ? 'Zaktualizuj Protokół Gratisu' : 'Generuj Protokół Gratisu' }}">

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
    const orderId = {!!$order_id!!};
    const giftProducts = {!!$gift_products!!};
    const updateGift = {!!$updateGift!!};
    const orderGift = {!!$order_gift!!};
    const giftId = {!!$gift_id!!};





</script>
<script src="{{ asset('js/hardwareGift.js') }}"></script>

@endsection
