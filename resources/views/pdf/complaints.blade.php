
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lista Raklamacji</title>
    <style>
    body {
        font-size: 10px;
        font-family: DejaVu Sans;

    }
    </style>
</head>
<body>





                    <table class="table-hover" border="1" cellspacing="1" cellpadding="10">
                        <thead>
                            <tr>
                            <td> LP </td>
                                <td> Data powstania dokumentu</td>
                                <td> Data produkcji</td>                                <td> Numer Reklamacji</td>
                                <td> Producent </td>
                                <td> Toner </td>
                                <td> Uwagi </td>
                                <td> Użytkownik </td>

                            </tr>
                        </thead>
                        @foreach ($complaints as $index=>$complaint)
                        <tbody>
                            <tr>
                            <td> {{ $index+1 }}



                </td>
                                <td> {{ date('d-m-Y',strtotime($complaint->issued_at)) }} </td>
                                <td>{{ $complaint->made_date }}</td>
          <td>{{ $complaint->number }}

                </td>

                <td>{{ $complaint->worker }}</td>
                <td> @foreach ($complaint->products as $product)
                <li> {{ $product->name }}</li>
                @endforeach
                </td>

                <td> {{ $complaint->comments }}</td>

                <td>{{ $complaint->customer->user->name }} {{ $complaint->customer->user->surname }}</td>

                            </tr>



                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
</div>


</body>
</html>
