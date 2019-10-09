<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

Reklamacja klienta {{ $complaint->customer->name }} <br>

Wiadomość dotycząca reklamacji nr {{ $complaint->number }}:<br>
<strong>{{ $complaint->message }}</strong>
<br>

</body>
</html>
