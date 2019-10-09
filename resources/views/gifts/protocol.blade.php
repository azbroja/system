<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
</head><body><html>
      <head>
      <title>- Protokół Wydania Gratisu</title>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
      <META HTTP-EQUIV='CACHE-CONTROL' CONTENT='NO-CACHE'>
      <META HTTP-EQUIV='PRAGMA' CONTENT='NO-CACHE'>

      <style type='text/css' media='all'>
      	body {
      		font-size: 1em;
      		font-family: "Arial"
      		}
      	#wrapper {
      		width: 94%;
      		padding: 3%;
      	}
      	.bottomMargin {
      		margin-bottom: 20px;
      	}
      	.topMargin {
      		margin-top: 20px;
      	}
      	.bottomMarginDouble {
      		margin-bottom: 100px;
      	}
      	.topMarginDouble {
      		margin-top: 100px;
      	}
      	.left {
      		float: left;
      	}
      	.right {
      		float: right;
      	}
      	.clear {
      		clear: both;
      	}
      	.fullWidth {
      		width: 100%;
      	}
      	.halfWidth {
      		width: 50%;
      	}
      	.center {
      		margin: auto;
      	}
      	.centerText {
      		text-align: center;
      	}
      	.rightText {
      		text-align: right;
      	}
      	.leftText {
      		text-align: left;
      	}
      	.addressLabel {
      		line-height: 2em;
      		font-weight: bold;
      	}
      	.bottomInfo {
      		font-size: 0.7em;
      	}
      </style>

 	  </head>
 	  <body>
		<div id = "wrapper">
			<span class = "right rightText">Kraków, {{ $gift->issued_at }}</span>
			<h2 class = "clear center topMargin bottomMargin">Protokół odbioru nagrody nr {{ $gift->number }}</h2>
			<span class = "clear addressLabel bottomMargin topMargin left">Przekazujący - organizator</span>
			<span class = "clear addressText bottomMargin left">
               <div id = "testData"><strong>{{ $seller->name }}</strong><br>{{ $seller->street }}<br> {{ $seller->postal_code }} {{ $seller->city }} <br>NIP: {{$seller->nip}}
<br>
<br>

			<span class = "clear addressLabel bottomMargin left">Odbierający Nagrodę - Uczestnik</span>
			<span class = "clear addressText bottomMargin left"> <strong>{{ $customer->name }}</strong><br>{{ $customer->street }}<br>{{ $customer->postal_code }} {{ $customer->city }}<br>NIP: {{ $customer->nip }}
			<div class = "left fullWidth topMargin">
				<ol class = "left">
					<li>Niniejszy Protokół Odbioru dotyczy przekazania nagrody uzyskanej w Promocji, zgodnie z jej Regulaminem.</li>
					<li>Nagrody odbierane przez Uczestnika w Promocji to: <br /><br /><strong>{{ $gift->products()->first()->name }} {{ $gift->products()->first()->symbol }} o wartości {{ $gift->products()->first()->pivot->gross_unit_price }} zł </strong><br /><br /></li>
					<li>Uczestnik potwierdza odbiór Nagrody.</li>
				</ol>
			</div>
			<div class = "topMarginDouble bottomMarginDouble left fullWidth">
				<div class = "sign halfWidth centerText left">Przekazujący-Organizator</div>
				<div class = "sign halfWidth centerText left">Odbierający Nagrodę</div>
			</div>
			<div class = "bottomInfo left fullWidth topMargin">
				OPIECZĘTOWANY JEDEN EGZEMPLARZ PROTOKŁU PROSIMY ODESŁAĆ DO SIEDZIBY ORGANIZATORA
				<ol class = "topMargin">
					<li>Informujemy, że nagroda stanowi nieodpłatne świadczenie i zgodnie z polskim prawem, należy ją traktować jako przychód obdarowanego podlegający opodatkowaniu.</li>
					<li>Wraz z odbiorem darowizny obdarowany podpisuje protokół odbioru nagrody i od tego momentu wszelkie zobowiązania wynikające z faktu przyjęcia nagrody obciążają obdarowanego.</li>
				</ol>
			</div>
		</div>
 	  </body>
</html></body></html>
