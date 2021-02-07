<?php

    function d2w($amount) {

      $separator = ' ';
      $jednosci = array('', ' jeden', ' dwa', ' trzy', ' cztery', ' pięć', ' sześć', ' siedem', ' osiem', ' dziewięć');
      $nascie = array('', ' jedenaście', ' dwanaście', ' trzynaście', ' czternaście', ' piętnaście', ' szesnaście', ' siedemnaście', ' osiemnaście', ' dziewietnaście');
      $dziesiatki = array('', ' dziesieć', ' dwadzieścia', ' trzydzieści', ' czterdzieści', ' pięćdziesiąt', ' sześćdziesiąt', ' siedemdziesiąt', ' osiemdziesiąt', ' dziewięćdziesiąt');
      $setki  = array('', ' sto', ' dwieście', ' trzysta', ' czterysta', ' pięćset', ' sześćset', ' siedemset', ' osiemset', ' dziewięćset');
      $grupy = array(
        array('' ,'' ,''),
        array(' tysiąc' ,' tysiące' ,' tysięcy'),
        array(' milion' ,' miliony' ,' milionów'),
        array(' miliard',' miliardy',' miliardów'),
        array(' bilion' ,' biliony' ,' bilionów'),
        array(' biliard',' biliardy',' biliardów'),
        array(' trylion',' tryliony',' trylionów')
      );

      $wynik = ''; $znak = '';
      if ($amount == 0)
        return 'zero';
      if ($amount < 0) {
        $znak = 'minus';
        $amount = -$amount;
      }
      $g = 0;
      while ($amount > 0) {


        $s = floor(($amount % 1000)/100);
        $n = 0;
        $d = floor(($amount % 100)/10);
        $j = floor($amount % 10);


        if ($d == 1 && $j>0) {
          $n = $j;
          $d = $j = 0;
        }

        $k = 2;
        if ($j == 1 && $s+$d+$n == 0)
          $k = 0;
        if ($j == 2 || $j == 3 || $j == 4)
          $k = 1;

        if ($s+$d+$n+$j > 0)
          $wynik = $setki[$s].$dziesiatki[$d].$nascie[$n].$jednosci[$j].$grupy[$g][$k].$wynik;

        $g++;
        $amount = floor($amount/1000);

      }
      return trim($znak.$wynik);



    }

function amount_literally($value){

      $kwota = number_format($value , 2, '.', '');
      $zl = array("złotych", "złoty", "złote");
      $gr = array("groszy", "grosz", "grosze");
      $kwotaArr  = explode('.', $kwota);


      $ostZl = substr($kwotaArr[0], -1, 1);
      switch($ostZl){
        case "0":
        $zlote = $zl[0];
        break;

        case "1":
        $ost2Zl = substr($kwotaArr[0], -2, 2);

        if($kwotaArr[0] == "1"){ $zlote = $zl[1]; }
        elseif($ost2Zl == "01"){ $zlote = $zl[0]; }
        else{ $zlote = $zl[0]; }
        break;

        case "2":
        $ost2Zl = substr($kwotaArr[0], -2, 2);
        if($ost2Zl == "12"){$zlote = $zl[0]; }
        else{ $zlote = $zl[2]; }
        break;

        case "3":
        $ost2Zl = substr($kwotaArr[0], -2, 2);
        if($ost2Zl == "13"){  $zlote = $zl[0]; }
        else{$zlote = $zl[2];}
        break;

        case "4":
        $ost2Zl = substr($kwotaArr[0], -2, 2);
        if($ost2Zl == "14"){$zlote = $zl[0];}
        else{$zlote = $zl[2];}
        break;
        case "5": $zlote = $zl[0]; break;
        case "6": $zlote = $zl[0]; break;
        case "7": $zlote = $zl[0]; break;
        case "8": $zlote = $zl[0]; break;
        case "9": $zlote = $zl[0]; break;
      }

            ############### PONIZEJ ||VVV|| GROSZE
      if(substr($kwotaArr['1'], 0,1) == 0 & substr($kwotaArr['1'], 1,2) >0 ){
        $grosze_poprawione = "0" . substr($kwotaArr['1'], 0,2) ;
          //print "<font color=green> <10 & 0 [$kwotaArr[1]]</font>";
      }

      else if (round($kwotaArr[1],2) < 10){
        $grosze_poprawione = substr($kwotaArr[1], 0,1) . "0" ;
             // print "<font color=red> <10 [$kwotaArr[1]]</font>";
      }

      else {    $grosze_poprawione = round($kwotaArr[1],2); }

      $kwotaArr[1] = $grosze_poprawione;

      $ostGr = substr($kwotaArr[1], -1, 1);
      switch($ostGr){
        case "0":
        $grosze = $gr[0];
        break;

        case "1":
        $ost2Gr = substr($kwotaArr[1], -2, 2);


        if($kwotaArr[0] == "1"){$grosze = $gr[1];}
        elseif($ost2Gr == "01"){  $grosze = $gr[1];}
        else{                  $grosze = $gr[0];}
        break;

        case "2":
        $ost2Gr = substr($kwotaArr[1], -2, 2);
        if($ost2Gr == "12"){$grosze = $gr[0];}
        else{$grosze = $gr[2];}
        break;

        case "3":
        $ost2Gr = substr($kwotaArr[1], -2, 2);
        if($ost2Gr == "13"){  $grosze = $gr[0];}
        else{                 $grosze = $gr[2];}
        break;

        case "4":
        $ost2Gr = substr($kwotaArr[1], -2, 2);
        if($ost2Gr == "14"){ $grosze = $gr[0];                }
        else{                  $grosze = $gr[2];}
        break;

        case "5": $grosze = $gr[0]; break;
        case "6": $grosze = $gr[0]; break;
        case "7": $grosze = $gr[0]; break;
        case "8": $grosze = $gr[0]; break;
        case "9": $grosze = $gr[0]; break;
      }



      return( d2w( $kwotaArr[0] ) . ' '.$zlote.' i ' .
        d2w($grosze_poprawione) .' '. $grosze);
    }


if (!function_exists('amount_pl')) {

  function amount_pl($value, $with_currency = false) {

    return @money_format('%'.($with_currency ? '' : '!').'n', $value);


  }

}
