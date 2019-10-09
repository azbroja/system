@extends('layouts.customer')

@section('content')
<div class="container">
    <div class="">
        <div class="">
            <div class="card">
            <div class="card-header">{{ __('Osoby kontaktowe') }} </div>

            <nav class="customer-menu">

                <a href="{{ url('/contact-person/create/'.$customer->id) }}"><button class="btn btn-primary">Dodaj Osobę kontaktową</button></a> <br>
</nav>
<div>

      <ul>

        @foreach ($customer->contact_people as $key=>$contactPerson)
            <a href="{{ url('/contact-person/update/'.$contactPerson->id) }}"> {{ ++$key }} {{$contactPerson->name}} {{$contactPerson->surname}} {{$contactPerson->telephone1}} {{$contactPerson->email}}</a> <br>
        @endforeach
        </ul>


</div>
</div>
</div>
</div>
</div>


@endsection
