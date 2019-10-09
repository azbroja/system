@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-header">Rozliczenia</div>
</div>
</div>

<div>
    <button type="button" class="btn btn-primary">Dodaj Fakturę</button>
</div>


<table class="table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">First</th>
            <th scope="col">Last</th>
            <th scope="col">Handle</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row">
                <input type="checkbox" aria-label="Checkbox for following text input">
            </th>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
        </tr>
        <tr>
            <th scope="row">2</th>
            <td>Jacob</td>
            <td>Thornton</td>
            <td>@fat</td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>Larry</td>
            <td>the Bird</td>
            <td>@twitter</td>
        </tr>
    </tbody>
</table>




@endsection
