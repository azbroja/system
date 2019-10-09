@extends('layouts.customer')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-header">{{ __('Dodaj Notatkę dla Klienta') }}</div>

                <div class="card-body">
                    <form method="post">
                        @csrf

                <div class="form-group row">



                            <label for="planned" class="col-md-4 col-form-label text-md-right">{{ __('Data planowania') }}</label>

                            <div class="col-md-6">
                                <input id="planned" type="date" class="form-control{{ $errors->has('planned') ? ' is-invalid' : '' }}" name="planned" value="{{date('Y-m-d') }}" required autofocus>

                                @if ($errors->has('planned'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('planned') }}</strong>
                                    </span>
                                @endif
                            </div>

                       <label for="contact_way" name="contact_way" class="col-md-4 col-form-label text-md-right">{{ __('Typ kontaktu') }}</label>

                            <div class="col-md-6">
                                <div>
                                <select class="form-control" name="contact_way">
                                    <option value="email">Kontakt e-mail</option>
                                    <option value="phone">Kontakt telefoniczny</option>
                                </select>
                                </div>
                                @if ($errors->has('contact_way'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('contact_way') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <label for="priority" name="priority" class="col-md-4 col-form-label text-md-right">{{ __('Priorytet') }}</label>

<div class="col-md-6">
    <div>
    <select class="form-control" name="priority">
        <option value="0">Niski</option>
        <option value="1">Wysoki</option>
    </select>
    </div>
    @if ($errors->has('priority'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('priority') }}</strong>
        </span>
    @endif
</div>


                     <label for="is_completed" name="is_completed" class="col-md-4 col-form-label text-md-right">{{ __('Status notatki') }}</label>

                            <div class="col-md-6">
                                <div>
                                <select class="form-control" name="is_completed">
                                    <option value="0">Do realizacji</option>
                                    <option value="1">Zrealizowana</option>
                                </select>
                                </div>
                                @if ($errors->has('is_completed'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('is_completed') }}</strong>
                                    </span>
                                @endif
                            </div>








                   <label for="note" class="col-md-4 col-form-label text-md-right">{{ __('Notatka') }}</label>

                   <div class="col-md-6">
                                <textarea rows="4" id="note" type="text" class="form-control{{ $errors->has('note') ? ' is-invalid' : '' }}" name="note" value="" required autofocus></textarea>

                                @if ($errors->has('note'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('note') }}</strong>
                                    </span>
                                @endif
                            </div>
</div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Zapisz') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
