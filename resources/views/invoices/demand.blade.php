
<html>
      <head>
      <title>Wezwanie do zaplaty</title>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
      <META HTTP-EQUIV='CACHE-CONTROL' CONTENT='NO-CACHE'>
      <META HTTP-EQUIV='PRAGMA' CONTENT='NO-CACHE'>

      <style type='text/css' media='all'>
      	body {
      		font-size: 0.9em;
      		font-family: "Arial"
      	}
      	div#testData  {
      		width: 34%;
      		padding: 0 4% 4% 4%;
      		float: left;
      		line-height: 1.4em;
      	}
      	div#customerData {
      		width: 50%;
      		padding: 0 4% 4% 4%;
      		float: left;
      	}
      	div#customerData {
      		text-align: right;
      	}
      	.topMargin {
      		margin-top: 20px;
      	}
      	.bottomMargin {
      		margin-bottom: 20px;
      	}
      	.rightMargin {
      		margin-right: 20px;
      	}
      	#wrapper {
      		width: 94%;
      		padding: 0 3% 3% 3%;
      	}
      	#header {
      		font-size: 0.8em;
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
      	table.stuffData {
      		border: 1px solid #E9ECED;
      		width: 100%;
      	}
      	table.stuffData th {
      		background-color: #E9ECED;
      		font-size: 0.7em;
      	}
      	table.stuffData td {
      		text-align: center;
      		border: 1px solid #E9ECED;
      		font-size: 0.7em;
      	}
      	.grayBackground {
      		background-color: #E9ECED;
      	}
      	.paddingSmall {
      		padding: 0.3em;
      	}
      	.paddingStandard {
      		padding: 1em;
      	}
      	.blackBorder {
      		border: 1px solid #000000;
      	}
      	.rejestrInfo {
      		width: 40%;
      		font-size: 0.6em;
      	}
      	.fatBorder {
      		border: 2px solid #E9ECED;
      	}
      	#sign {
      		height: 6em;
      		width: 35%;f
      	}
      	#signLabel {
      		margin-top: 30%;
      		height: 10%;
      		width: 100%;
      		text-align: center;
      		font-size: 0.7em;
      	}
      	.bottomInfo {
      		font-size: 0.7em;
      	}
      	.lineHeightExpanded {
      		line-height: 1.4em;
      	}
      	.reciverData {
      		font-size: 1.3em;
      		text-align: left;
      		width: 300px;
      		margin-top: 100px;
      	}
      </style>




 	  </head>
 	  <body>
 	  	<div id = "wrapper">
	 	  	<div id = "header" class = "bottomMargin left fullWidth">
               <div id = "testData"><strong>{{ $seller->name }}</strong><br>{{ $seller->street }}, {{ $seller->postal_code }} {{ $seller->city }} <br>NIP: {{$seller->nip}}
               <br>{{ $seller->telephone1 }}, {{ $seller->telephone2 }}<br></div>
               <div id = "customerData">Kraków, {{ date('d-m-Y',strtotime($now)) }}<br /><br />


<span class = "reciverData topMarginDouble right"><strong>{{ $customer->name }}</strong><br>{{ $customer->street }}<br>{{ $customer->postal_code }} {{ $customer->city }}<br>NIP: {{ $customer->nip }}</span></div>
</div>
	 	  	<div class = "fullWidth left bottomMargin">
	 	  		<h1 class = "center centerText">WEZWANIE DO ZAPŁATY</h1>
	 	  	</div>
	 	  	<span class = "textInfo clear left">Na podstawie art. 476 Kodeksu cywilnego (Dz. U. z 1964 r. Nr 16, poz. 93, z późn. zmianami) wzywamy do natychmiastowego uregulowania należnej sumy, zgodnie z poniższym zestawieniem.</span>
	 	  	<table class = "stuffData topMargin left bottomMargin">
	 	  		<tr>
	 	  			<th>Podstawa zobowiązania</th>
	 	  			<th>Numer dokumentu</th>
	 	  			<th>Kwota netto</th>
	 	  			<th>Kwota brutto</th>
	 	  			<th>Data płatności</th>
	 	  			<th>Pozostało do zapłaty</th>
	 	  		</tr>
	 	  			 	  			<tr>
		 	  			<td>Faktura VAT</td>
		 	  			<td>{{$invoice->number}}</td>
		 	  			<td>{{ $invoice->net_value}}</td>
		 	  			<td>{{ $invoice->total_value}}</td>
		 	  			<td>{{ date('d-m-Y',strtotime($invoice->pay_deadline)) }}</td>
		 	  			<td>{{ $invoice->total_value}}</td>
		 	  		</tr>
	 	  			 	  			<tr>
		 	  			<td colspan = "4"></td>
		 	  			<th>Razem:</th>
		 	  			<td>{{ $invoice->total_value}}</td>
		 	  		</tr>
 	  	</table>
 	  	<span class = "left bottomMargin">Kwota słownie: {{ amount_literally($invoice->total_value) }}
</span>
 	  	<span class = "grayBackground clear left paddingSmall bottomMargin fullWidth centerText lineHeightExpanded">Wymienioną sumę prosimy przekazać na rachunek bankowy firmy <br />Credit Agricole Bank Polska S.A 7019 4010 76300 96772 00000000 <br />w ciągu 7 dni od daty otrzymania niniejszego wezwania.</span>
 	  	<div class = "fullWidth blackBorder clear left">
 	  		<img class = "left rightMargin" src="{{URL::to('/')}}/img/krd.jpg" />
 	  		<div class = "right centerText paddingStandard rejestrInfo">
 	  			Informacje o nieuregulowanych zobowiązaniach, <br />
				zgodnie z Ustawą o udostępnianiu informacji gospodarczych<br />
				 i wymianie danych gospodarczych<br />
				 z dnia 9 kwietnia 2010 r.<br />
				(Dz.U. Nr 81, poz. 530), będą przekazywane do:<br /><br />

				Krajowego Rejestru Długów<br />
				Biura Informacji Gospodarczej SA<br />
				ul. Armii Ludowej 21, 51-214 Wrocław<br />
				<strong>www.krd.pl</strong><br /><br />

				Informacja o zadłużeniu upubliczniona będzie w KRD do dnia zapłaty<br />
				lub do 10 lat od daty dokonania wpisu.<br />
			</div>
 	  	</div>
 	  	<span class = "left topMargin bottomMargin bottomInfo">Umieszczenie informacji o Państwa zobowiązaniach w Krajowym Rejestrze Długów skutkuje ograniczeniem możliwości swobodnego funkcjonowania na rynku konsumenckim lub prowadzenia działalności gospodarczej, nawet do 10 lat.
Dłużnicy notowani w KRD napotykają na znaczne utrudnienia w korzystaniu z usług finansowych (kredyty, zakupy ratalne, leasing), telekomunikacyjnych (możliwość kupna telefonu w abonamencie), multimedialnych (telewizja kablowa, szerokopasmowy dostęp do Internetu), wynajmu lokali (mieszkania, biura, magazyny) itp.
Banki, firmy leasingowe i pośrednictwa ratalnego, operatorzy telefoniczni, telewizje kablowe i wiele innych firm mogą w takim przypadku odmówić współpracy albo zaoferować ją na gorszych warunkach.</span>

 	  </body>
</html>
