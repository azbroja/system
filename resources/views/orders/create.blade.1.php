@extends('layouts.customer')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">

                <div class="card-header">{{ __('Dodaj Zlecenie sprzedaży') }} </div>
                <div class="card-body">
                    <form method="post">
                        @csrf


                        <label for="buyer_address_" class="col-md-4 col-form-label">{{ __('Dane Nabywcy') }}</label>
                        <div class="col-md-12">
                            <input id="buyer_address_" type="text" class="form-control{{ $errors->has('buyer_address_') ? ' is-invalid' : '' }}"
                                name="buyer_address_" value="{{ $customer->name.', '.$customer->street.', '.$customer->postal_code.' '.$customer->city.', NIP: '.$customer->nip }}"
                                autofocus>
                            @if ($errors->has('buyer_address_'))
                            <span class="invalid-feedback" role="alert">
<strong>{{ $errors->first('buyer_address_') }}</strong>
</span>
                            @endif
                        </div>
                        <label for="buyer_address_recipient" class="col-md-4 col-form-label">{{ __('Dane Odbiorcy') }}</label>
                        <div class="col-md-12">
                            <input id="buyer_address_recipient" type="text" class="form-control{{ $errors->has('buyer_address_recipient') ? ' is-invalid' : '' }}"
                                name="buyer_address_recipient" value="" autofocus>
                            @if ($errors->has('buyer_address_recipient'))
                            <span class="invalid-feedback" role="alert">
<strong>{{ $errors->first('buyer_address_recipient') }}</strong>
</span>
                            @endif
                        </div>
                        <label for="pay_type" class="col-md-4 col-form-label">{{ __('Forma Płatności') }}</label>
                        <div class="col-md-12">
                            <div>
                                <select class="form-control" name="pay_type">
                                    <option value="transfer">Przelew</option>
                                    <option value="cash">Gotówka</option>
                                </select>
                            </div>
                            @if ($errors->has('pay_type'))
                            <span class="invalid-feedback" role="alert">
<strong>{{ $errors->first('pay_type') }}</strong>
</span>
                            @endif
                        </div>


                        <label for="comments" class="col-md-4 col-form-label">{{ __('Komentarz do dokumentu') }}</label>
                        <div class="col-md-12">


                            <input id="comments" type="text" class="form-control{{ $errors->has('comments') ? ' is-invalid' : '' }}"
                                name="comments" value="Brak" autofocus>
                            @if ($errors->has('comments'))
                            <span class="invalid-feedback" role="alert">
<strong>{{ $errors->first('comments') }}</strong>
</span>
                            @endif
                        </div>

                        <br>


                        <div class="form-group row">
                            <table class="orders-table" border="1" cellspacing="2" cellpadding="10">
                                <thead>
                                    <tr>

                                        <th>Nazwa Produktu </th>
                                        <th>Symbol Produktu </th>
                                        <th>Cena Sprzedaży</th>
                                        <th>Ilość</th>
                                        <th>Usuń Produkt</th>

                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>


                        </div>

                        </p>
                </div>
                </table>
            </div>
            <br>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Generuj zlecenie') }}
                    </button>
                    <button type="button" class="add-all-order-product btn btn-primary">++</button>
                    <button type="button" class="add-order-product btn btn-primary">+</button>

                    </table>
                </div>
            </div>
            </form>
        </div>
        </b>
        </b>
    </div>
</div>




@endsection

@section('scripts')

<script>
    const customerId = {!!$customer->id!!};
    const customerProducts = {!!$customer_products!!};
    const orderProducts = {!!$order_products!!};

</script>
<script src="{{ asset('js/order.js') }}"></script>

@endsection
