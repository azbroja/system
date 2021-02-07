
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">



      <title>Wezwanie do zaplaty</title>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
      <META HTTP-EQUIV='CACHE-CONTROL' CONTENT='NO-CACHE'>
      <META HTTP-EQUIV='PRAGMA' CONTENT='NO-CACHE'>


<style>
body {
    font-family: DejaVu Sans;
    style:font-size: 16px;
}</style>



 	  </head>
 	  <body>
<div style="text-align: right">
<span class = "reciverData topMarginDouble right"><strong>{{ $customer->name }}</strong><br>{{ $customer->street }}<br>{{ $customer->postal_code }} {{ $customer->city }}<br>NIP: {{ $customer->nip }}</span></div>
</div>
</div>
	 	  	<div class = "fullWidth left bottomMargin">
	 	  		<h1 style="text-align: center;" class = "center centerText">WEZWANIE DO ZAPŁATY</h1>
	 	  	</div>

	 	  	<span style="font-size: 12px" class = "textInfo clear left">Na podstawie art. 476 Kodeksu cywilnego (Dz. U. z 1964 r. Nr 16, poz. 93, z późn. zmianami) wzywamy do natychmiastowego uregulowania należnej sumy, zgodnie z poniższym zestawieniem.</span>
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
 	  	<span class = "grayBackground clear left paddingSmall bottomMargin fullWidth centerText lineHeightExpanded">
           <br/>
           <strong>
           Wymienioną sumę prosimy przekazać na rachunek bankowy firmy Druk-Pol<br />Credit Agricole Bank Polska S.A 7019 4010 76300 96772 00000000 <br />w ciągu 7 dni od daty otrzymania niniejszego wezwania.</span></strong>
 	  	<div class = "fullWidth blackBorder clear left">
 	  		<img class = "left rightMargin" src="{{URL::to('/')}}/img/krd.jpg" />
 	  		<div style="font-size: 12px" class = "right centerText paddingStandard rejestrInfo">
 	  			Informacje o nieuregulowanych zobowiązaniach, <br />
				zgodnie z Ustawą o udostępnianiu informacji gospodarczych<br />
				 i wymianie danych gospodarczych<br />
				 z dnia 9 kwietnia 2010 r.<br />
				(Dz.U. Nr 81, poz. 530), będą przekazywane do:<br /><br />

				Krajowego Rejestru Długów<br />
				Biura Informacji Gospodarczej SA<br />
				ul. Armii Ludowej 21, 51-214 Wrocław<br />
				<strong>www.krd.pl</strong><br /><br />
<div style="font-size: 12px">
				Informacja o zadłużeniu upubliczniona będzie w KRD do dnia zapłaty<br />
				lub do 10 lat od daty dokonania wpisu.<br />
                </div>
			</div>
 	  	</div>
 	  	<span class = "left topMargin bottomMargin bottomInfo" style="font-size: 8px">Umieszczenie informacji o Państwa zobowiązaniach w Krajowym Rejestrze Długów skutkuje ograniczeniem możliwości swobodnego funkcjonowania na rynku konsumenckim lub prowadzenia działalności gospodarczej, nawet do 10 lat.
Dłużnicy notowani w KRD napotykają na znaczne utrudnienia w korzystaniu z usług finansowych (kredyty, zakupy ratalne, leasing), telekomunikacyjnych (możliwość kupna telefonu w abonamencie), multimedialnych (telewizja kablowa, szerokopasmowy dostęp do Internetu), wynajmu lokali (mieszkania, biura, magazyny) itp.
Banki, firmy leasingowe i pośrednictwa ratalnego, operatorzy telefoniczni, telewizje kablowe i wiele innych firm mogą w takim przypadku odmówić współpracy albo zaoferować ją na gorszych warunkach.</span>

 	  </body>
</html>
