<?php
if(!$_POST["poslat_x"] or !$_POST['ID_cenovej_ponuky']) exit; // spusti sa len pri kliknuti na Poslat mailom
include "config.php"; 
include "lib.php";

// pripojenie na DB server a zapamatame si pripojenie do premennej $dblink
$dblink = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db);

if (!$dblink) { // kontrola ci je pripojenie na db dobre ak nie tak napise chybu
	echo "Chyba pripojenia na DB!</br>";
	die(); // ukonci vykonanie php
}

mysqli_set_charset($dblink, "utf8mb4");

$ID_cenovej_ponuky=$_POST["ID_cenovej_ponuky"];
$ID_cenovej_ponuky=intval($ID_cenovej_ponuky);

$sql="SELECT * FROM cenova_ponuka cp LEFT JOIN objednavka o ON cp.ID_objednavky = o.ID_objednavky LEFT JOIN registrovany_pouzivatel r ON cp.ID_pouzivatela=r.ID_pouzivatela WHERE cp.ID_cenovej_ponuky = $ID_cenovej_ponuky";
$nadpis = "Cenová ponuka č. ".$ID_cenovej_ponuky;
$vysledok = mysqli_query($dblink,$sql); 
$riadok = mysqli_fetch_assoc($vysledok);
$datum_vytvorenia = $riadok["Cp_Datum_vytvorenia"];
$datum_upravy = $riadok["Cp_Datum_upravy"];
$Nazov_firmy = $riadok["Obj_Nazov_firmy"];
$Meno = $riadok["Obj_Meno"];
$Priezvisko = $riadok["Obj_Priezvisko"];
$Email = $riadok["Obj_Email"];
$Telefon = $riadok["Obj_Telefon"];
$Ulica_cislo_fakturacna = $riadok["Obj_Ulica_cislo_fakturacna"];
$Mesto_fakturacna = $riadok["Obj_Mesto_fakturacna"];
$PSC_fakturacna = $riadok["Obj_PSC_fakturacna"];
$Ulica_cislo_dodacia = $riadok["Obj_Ulica_cislo_dodacia"];
$Mesto_dodacia = $riadok["Obj_Mesto_dodacia"];
$PSC_dodacia = $riadok["Obj_PSC_dodacia"];
$ICO = $riadok["Obj_ICO"];
$DIC = $riadok["Obj_DIC"];
$IC_DPH = $riadok["Obj_IC_DPH"];
$Posledny_pouzivatel = $riadok["Pouz_Meno"]." ".$riadok["Pouz_Priezvisko"];
$Obj_Poznamka = $riadok["Obj_Poznamka"];
$ID_objednavky = $riadok["ID_objednavky"];
$Celkova_cena_s_DPH = $riadok["Celkova_cena_s_DPH"];
$Cp_Poznamka = $riadok["Cp_Poznamka"];
$Posledny_pouzivatel = $riadok["Pouz_Meno"]." ".$riadok["Pouz_Priezvisko"];
$Upraveny_Datum_upravy = date("d.m.Y", strtotime($riadok["Cp_Datum_upravy"]));	

/* SABLONA*/
$sablona='<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
	
<style type="text/css">
	:root {
  --red: #c42f2f;
  --darkred: #c42f2f;
  --sirka: 70%;
  --sirka_login: 35%;
}

body {
  background-color: #FFFFFF;
}



p {
  font-family: Arial, Helvetica, sans-serif;
  color: #000000;
  font-size: 15px;
}
p.text-grey
{
	color: #4f4f4f !important;
}

p.cervene {
  color: #d64161;
  font-size: 15px;
  margin-left: auto;
  margin-right: auto;
  width: var(--sirka);
  text-align: center-left;
  background-color: #ffffff;
  text-shadow: 0px 0px 0px;
  margin-top: 1rem;
}
p.oznam{
  font-family: Arial, Helvetica, sans-serif;
  font-weight: normal;
  color: #000000;
  font-size: 15px;
  margin-left: auto;
  margin-right: auto;
  width: var(--sirka);
  margin-top: 1rem;
 }
 
.red 
{ 
color:red;
}

	  						
 
h1 {
  font-family: Arial, Helvetica, sans-serif;
  color: #FFFFFF;
  background-color: var(--darkred);
  border-collapse: collapse;
  font-size: 2rem;
  width: var(--sirka);	
  padding: 100px  25px 75px; /* top right bottom left*/
  margin-left: auto;
  margin-right: auto;
  margin-bottom: 0px;
}

h2 {
  font-family: Arial, Helvetica, sans-serif;
  color: #FFFFFF;
  background-color: var(--darkred);
  border-collapse: collapse;
  font-size: 40px;
  width: var(--sirka_login);	
  padding: 100px 200px 25px 75px; /* top right bottom left*/
  margin-left: auto;
  margin-right: auto;
  margin-bottom: 0px;
  margin-top: 12rem;
}

#nadpis {
  font-family: Arial, Helvetica, sans-serif;
  color: #FFFFFF;
  background-color: #00ff00;
  text-shadow: 2px 2px 2px #6b6b6b;
}




.zoznam {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: var(--sirka);
}



.zoznam td, .zoznam th {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  border: 1px solid #ddd;
  margin-left: 5px;
  text-align: left;
  padding: 8px 0px 12px 8px; /* top right bottom left*/
   
}

.zoznam th {
  background-color: var(--darkred);
  color: white;
  border-collapse: collapse;
}

/* STARE FARBY RIADKOV
.zoznam tr:nth-child(even){background-color: #f2f2f2;}

.zoznam tr:nth-child(odd){background-color: #fff;}

.zoznam tr:hover{background-color: #ddd;} 
*/

.zoznam tr:nth-child(even){background-color: #fafafa;}

.zoznam tr:nth-child(odd){background-color: #ffffff;}

.zoznam tr:hover{background-color: #f3f3f3;} 

.cisla{text-align:right;margin-right:3px}
	</style>
	</head>
<body>
<h1><div  style="float:left" >'.$nadpis.'</div><div style="float:right">Reklamné štúdio</div></h1>
<p>
		<div class="container d-flex">
		<p class="oznam">Dátum poslednej úpravy: '.$Upraveny_Datum_upravy.' Upravil: '.$Posledny_pouzivatel.'</p>
	</div>	
<form action="zmena_cenovej_ponuky.php" method="POST">
<table  class="zoznam" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr><td colspan="2"><b>Kontaktné údaje:</b></td></tr>
	<tr><td>Názov firmy:<span class="hviezdicka">*</span></td><td style="width:50%;"> '.$Nazov_firmy.'</br></td></tr>
	<tr><td>Meno:<span class="hviezdicka">*</span></td><td>'.$Meno .'</br></td></tr>
	<tr><td>Priezvisko:<span class="hviezdicka">*</span></td><td>'.$Priezvisko.'</br></td></tr>
	<tr><td>Email:<span class="hviezdicka">*</span></td><td>'.$Email.'</br></td></tr>
	<tr><td>Telefon:<span class="hviezdicka">*</span></td><td>'.$Telefon.'</br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Fakturačná adresa:</b></td></tr>
	<tr><td>Ulica a číslo domu (fakturačná adresa):<span class="hviezdicka">*</span></td><td>'.$Ulica_fakturacna.'</br></td></tr>
	<tr><td>Mesto (fakturačná adresa):<span class="hviezdicka">*</span></td><td>'.$Mesto_fakturacna.'</br></td></tr>
	<tr><td>PSČ (fakturačná adresa):<span class="hviezdicka">*</span></td><td>'.$PSC_fakturacna.'</br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Dodacia adresa:</b></td></tr>
	<tr><td>Ulica a číslo domu (dodacia adresa):</td><td>'.$Ulica_dodacia.'</br></td></tr>
	<tr><td>Mesto (dodacia adresa):</td><td>'.$Mesto_dodacia.'</br></td></tr>
	<tr><td>PSČ (dodacia adresa):</td><td>'.$PSC_dodacia.'</br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Firemné údaje:</b></td></tr>
	<tr><td>IČO:</td><td>'.$ICO.'</td></tr>
	<tr><td>DIČ:</td><td>'.$DIC.'</br></td></tr>
	<tr><td>IČ DPH:</td><td>'.$IC_DPH.'</br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2">
		<table  style="width:var(--sirka)" border="1" align="center" cellpadding="0" cellspacing="0">
		<tr><td>Popis služby</td><td>Cena za jednotku</td><td>Počet ks</td><td>Cena za službu</td></tr>';
		
	$sql_sluzby_na_objednavke = "Select *,so.ID_sluzby_na_objednavke as ID_sluzby_na_objednavke_ok  FROM sluzby_na_objednavke so LEFT JOIN sluzba s ON so.ID_sluzby=s.ID_sluzby LEFT JOIN sluzby_na_cenovej_ponuke scp ON scp.ID_sluzby_na_objednavke=so.ID_sluzby_na_objednavke WHERE so.ID_objednavky=$ID_objednavky"; 
	//echo $sql_sluzby_na_objednavke;
	// sluzby pre cenovu ponuku
	$vysledok2=mysqli_query($dblink,$sql_sluzby_na_objednavke);
	if (!$vysledok2){
		echo "Doslo k chybe pri vyhľadaní údajov o službách na cenovej ponuke !";
		exit;
	}	
	$i=0;
	// v cykle nacitame postupne riadky tabulky sluzby na objednavke
	while ($riadok2 = mysqli_fetch_assoc($vysledok2)) {
		$ID_sluzby_na_cenovej_ponuke[$i]=$riadok2['ID_sluzby_na_cenovej_ponuke']; //id  sluzby na cp
		$ID_sluzby_na_objednavke[$i]=$riadok2['ID_sluzby_na_objednavke_ok']; //id  
		$ID_sluzby[$i]=$riadok2['ID_sluzby'];// id sluzby zapisanej z comboboxu
		if($riadok2['Pocet_kusov']) $Pocet_kusov[$i]=$riadok2['Pocet_kusov']; else $Pocet_kusov[$i]="";
		$Farba[$i]=$riadok2['Farba'] ;
		$Format[$i]=$riadok2['Format'];
		if($riadok2['Rozmer_sirka']) $Rozmer_sirka[$i]=$riadok2['Rozmer_sirka']; else $Rozmer_sirka[$i]="";
		if($riadok2['Rozmer_vyska']) $Rozmer_vyska[$i]=$riadok2['Rozmer_vyska'];  else $Rozmer_vyska[$i]="";;
		$Velkost[$i]=$riadok2['Velkost'];
		$Zobrazit_rozmery[$i]=$riadok2['Zobrazit_rozmery']; 
		$Zobrazit_velkost[$i]=$riadok2['Zobrazit_velkost']; 
		$Farby[$i]=$riadok2['Farby']; 
		$Dostupne_formaty[$i]=$riadok2['Dostupne_formaty']; 
		if($riadok2['Cena_za_jednotku']) $Cena_za_jednotku[$i]=$riadok2['Cena_za_jednotku']; else $Cena_za_jednotku[$i]="";
		if($riadok2['Cena_za_sluzbu']) $Cena_za_sluzbu[$i]=$riadok2['Cena_za_sluzbu']; else $Cena_za_sluzbu[$i]="";
		$Nazov[$i]=$riadok2['Nazov'];
		$popis_sluzby=$Nazov[$i];
		if($Farba[$i]) $popis_sluzby.=", ".$Farba[$i];
		if($Format[$i]) $popis_sluzby.=", ".$Format[$i];
		if($Zobrazit_rozmery[$i] and $Rozmer_sirka[$i]) $popis_sluzby.=", šírka:".$Rozmer_sirka[$i].'mm';
		if($Zobrazit_rozmery[$i] and $Rozmer_vyska[$i]) $popis_sluzby.=", výška:".$Rozmer_vyska[$i].'mm';
		if($Zobrazit_velkost[$i] and $Velkost[$i]) $popis_sluzby.=", ".$Velkost[$i];
		$sablona.='
		<tr><td>'.$popis_sluzby.'</td><td class="cisla">'.$Cena_za_jednotku[$i].'</td><td <td class="cisla">'.$Pocet_kusov[$i].'</td><td class="cisla">'.Cena_sluzbu[$i].'</td></tr>';
	}
	$sablona.='</table>
		</td></tr>
		<tr><td>Celková cena s DPH:</td><td class="cisla">'.$Celkova_cena_s_DPH.'</br></td></tr>'.
		
		'<tr><td style = "vertical-align:top !important;">Poznámka objednávky</td><td>'.$Poznamka_objednavky.'</td></tr>
		<tr><td colspan="2"><br></td></tr>	
		<tr><td style = "vertical-align:top !important;">Poznámka cenovej ponuky</td><td>'.$Poznamka_cenovej_ponuky.'</td></tr>
		<tr><td colspan="2"><br></td></tr>
		<tr><td colspan="2">
		</td></tr>
		</table>
</p>
</body>
</html>';
/* END SABLONA*/
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=windows-1250' . "\r\n";

/*if ( !(empty($$mail)) and !(empty($Meno)) and !(empty($Priezvisko)) )
      {$headers .= 'From: User Apache'. "\r\n";
       $headers .= 'Reply-To: '.trim($Meno).' '.trim($Priezvisko).' '.trim($Nazov_firmy).'<'.$Email.'>';}*/

$headers .= 'From: Reklamné štúdio'. "\r\n";
$headers .= 'Reply-To: '.$mail_od; 
$uspech=mail("renkap@gmail.com","Cenová ponuka od Reklamné štúdio", $sablona,$headers);
if ($uspech==false)
   print "<script type='text/javascript'>history.go(-1);alert('Nepodarilo sa odoslať Vašu cenovú ponuku.');</script>";
else
   {print "<script type='text/javascript'>alert('Cenová ponuka bola odoslaná.');</script>";
    

}





