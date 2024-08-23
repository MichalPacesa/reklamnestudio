<!DOCTYPE html>
<?php session_start(); ?>
<html>
<head>
<?php include "head.php";?>
</head>
<body>

<?php
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

if($ID_cenovej_ponuky)
{
	$zobrazit=$_POST["zobrazit_x"];
	$sql="SELECT * FROM cenova_ponuka cp LEFT JOIN objednavka o ON cp.ID_objednavky = o.ID_objednavky LEFT JOIN registrovany_pouzivatel r ON cp.ID_pouzivatela=r.ID_pouzivatela WHERE cp.ID_cenovej_ponuky = $ID_cenovej_ponuky";
	if($zobrazit) 
	{
		$akcia="preview";
		$nadpis = "Cenová ponuka č. ".$ID_cenovej_ponuky;
	}	
	else
	{
		if(ZistiPrava("Edit_cenove_ponuky",$dblink) == 0) 
		{ 
			include "navbar.php";
			echo "<p class='oznam'>Nemáte práva na editáciu a pridávanie cenových ponúk</p>";exit;
		}	
		$akcia = "update"; 
		$nadpis = "Editácia cenovej ponuky č. ".$ID_cenovej_ponuky;
	}	
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
	
	$Upraveny_Datum_upravy = date("d.m.Y", strtotime($riadok["Cp_Datum_upravy"]));	
}
else
{
	if(ZistiPrava("Edit_cenove_ponuky",$dblink) == 0) 
	{ 
		include "navbar.php";
		echo "<p class='oznam'>Nemáte práva na editáciu a pridávanie cenových ponúk</p>";exit;
	}
	$akcia = "insert";
	$nadpis = "Nová cenová ponuka";
	$ID_cenovej_ponuky = "";
	$Upraveny_Datum_upravy = "";
	$Nazov_firmy = "";
	$Meno = "";
	$Priezvisko = "";
	$Email = "";
	$Telefon = "";
	$Ulica_cislo_fakturacna = "";
	$Mesto_fakturacna = "";
	$PSC_fakturacna = "";
	$Ulica_cislo_dodacia = "";
	$Mesto_dodacia = "";
	$PSC_dodacia = "";
	$ICO = "";
	$DIC = "";
	$IC_DPH = "";
	$Posledny_pouzivatel = "";
	$Obj_Poznamka = "";
	
	$Celkova_cena_s_DPH = "";
	$Cp_Poznamka = "";
}
?>

<h1><?php echo $nadpis; ?></h1>
<p>

	<?php if($Upraveny_Datum_upravy): ?>
	<div class="container d-flex">
		<p class="oznam text-grey text-start" style="margin-top: 5px !important;margin-left:-10px!important;">Položky označené <span class="red bold">*</span> sú povinné</p>
		<p class="oznam text-grey text-end " style="margin-top: 5px !important;margin-right:-10px!important;">Dátum poslednej úpravy: <?php echo $Upraveny_Datum_upravy;?> Upravil: <?php echo $Posledny_pouzivatel;?></p>
	</div>	
	<?php else: ?>
	<p class="oznam text-grey text-start">Položky označené <span class="red bold">*</span> sú povinné</p>
	<?php endif; ?>
	<?php if($akcia!="preview"): ?>
	<form action="vyber_objednavky.php" method="POST">
	<input style="margin-left: 17.8rem" type="submit" value="Výber objednávky">
	<input type="hidden" name="ID_cenovej_ponuky" value="<?php echo $ID_cenovej_ponuky;?>">
	<input type="hidden" name="akcia" value="<?php echo $akcia;?>">
	<br>
	<br>
	</form>
	<?php endif; ?>
	
	<?php	
	if($_POST["ID_vybratej_objednavky"])
	{
	$ID_objednavky=$_POST["ID_vybratej_objednavky"];
	$ID_objednavky=intval($ID_objednavky);
	}
	if($_POST["ID_vybratej_objednavky"]) 
	{
		$akcia="vyber";
		$sql="SELECT * FROM objednavka o LEFT JOIN zamestnanec z ON o.ID_zamestnanca = z.ID_zamestnanca LEFT JOIN registrovany_pouzivatel r ON o.ID_pouzivatela=r.ID_POUZIVATELA WHERE o.ID_objednavky = $ID_objednavky";
		$vysledok = mysqli_query($dblink,$sql); 
		$riadok = mysqli_fetch_assoc($vysledok);
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
		$Obj_Poznamka = $riadok["Obj_Poznamka"];
	}
	?>

<form action="zmena_cenovej_ponuky.php" method="POST">

<table class="zoznam" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr><td colspan="2"><b>Kontaktné údaje:</b></td></tr>
	<tr><td>Názov firmy:<span class="hviezdicka">*</span></td><td style="width:50%;"> <input type="text" name="Obj_Nazov_firmy" value="<?php echo $Nazov_firmy;?>" disabled></br></td></tr>
	<tr><td>Meno:<span class="hviezdicka">*</span></td><td> <input type="text" name="Obj_Meno" value="<?php echo $Meno;?>" disabled></br></td></tr>
	<tr><td>Priezvisko:<span class="hviezdicka">*</span></td><td> <input type="text" name="Obj_Priezvisko" value="<?php echo $Priezvisko;?>" disabled></br></td></tr>
	<tr><td>Email:<span class="hviezdicka">*</span></td><td> <input type="text" name="Obj_Email" value="<?php echo $Email;?>" disabled></br></td></tr>
	<tr><td>Telefon:<span class="hviezdicka">*</span></td><td> <input type="text" name="Obj_Telefon" value="<?php echo $Telefon;?>" disabled></br></td></tr>

	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Fakturačná adresa:</b></td></tr>
	<tr><td>Ulica a číslo domu (fakturačná adresa):<span class="hviezdicka">*</span></td><td> <input type="text" name="Obj_Ulica_cislo_fakturacna" value="<?php echo $Ulica_cislo_fakturacna;?>" disabled></br></td></tr>
	<tr><td>Mesto (fakturačná adresa):<span class="hviezdicka">*</span></td><td> <input type="text" name="Obj_Mesto_fakturacna" value="<?php echo $Mesto_fakturacna;?>" disabled></br></td></tr>
	<tr><td>PSČ (fakturačná adresa):<span class="hviezdicka">*</span></td><td> <input type="text" name="Obj_PSC_fakturacna" value="<?php echo $PSC_fakturacna;?>" disabled></br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Dodacia adresa:</b></td></tr>
	<tr><td>Ulica a číslo domu (dodacia adresa):</td><td> <input type="text" name="Obj_Ulica_cislo_dodacia" value="<?php echo $Ulica_cislo_dodacia;?>" disabled></br></td></tr>
	<tr><td>Mesto (dodacia adresa):</td><td> <input type="text" name="Obj_Mesto_dodacia" value="<?php echo $Mesto_dodacia;?>" disabled></br></td></tr>
	<tr><td>PSČ (dodacia adresa):</td><td> <input type="text" name="Obj_PSC_dodacia" value="<?php echo $PSC_dodacia;?>" disabled></br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Firemné údaje:</b></td></tr>
	<tr><td>IČO:</td><td> <input type="text" name="Obj_ICO" value="<?php echo $ICO;?>" disabled></br></td></tr>
	<tr><td>DIČ:</td><td> <input type="text" name="Obj_DIC" value="<?php echo $DIC;?>" disabled></br></td></tr>
	<tr><td>IČ DPH:</td><td> <input type="text" name="Obj_IC_DPH" value="<?php echo $IC_DPH;?>" disabled></br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Ceny za služby:</b><br><br>
		<table class="zoznam w-100" border="1" align="center" cellpadding="0" cellspacing="0">
		<tr><td>Popis služby</td><td>Cena za jednotku</td><td>Počet ks</td><td>Cena za službu</td></tr>
		<?php
		IF($ID_objednavky){		
		
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
				if($akcia=="preview"):
				?>	
				<tr><td><?php echo $popis_sluzby; ?></td><td class="text-end me-1"><?php echo $Cena_za_jednotku[$i]; ?></td><td><?php echo $Pocet_kusov[$i]; ?></td><td class="text-end"><?php echo $Cena_za_sluzbu[$i];?></td></tr>
				<?php else: ?>
				<tr><td><?php echo $popis_sluzby; ?></td><td><input required type="number" step="0.01" name="Cena_za_jednotku[<?php echo $i;?>]" style="margin-left:-3px;"class="text-end w-100" value="<?php echo $Cena_za_jednotku[$i];?>"></td><td class="text-start"><?php echo $Pocet_kusov[$i]; ?></td>
				<td><input required type="number" step="0.01" name="Cena_za_sluzbu[<?php echo $i;?>]" style="margin-left:-3px;" class="text-end w-100 me-1" value="<?php echo $Cena_za_sluzbu[$i];?>"></td></tr>
				<?php endif; ?>
				<input type="hidden" name="ID_sluzby_na_cenovej_ponuke[<?php echo $i;?>]"  value="<?php echo $ID_sluzby_na_cenovej_ponuke[$i];?>">
				<input type="hidden" name="ID_sluzby_na_objednavke[<?php echo $i;?>]"  value="<?php echo $ID_sluzby_na_objednavke[$i];?>">
				<?php		
				$i++;
			}
		}
			
		?>

		
	</td></tr>
	</table>
	<tr><td>Celková cena s DPH:</td><td class="text-end"><input required <?php echo disabled_vyber($akcia);?> type="number" step="0.01" name="Celkova_cena_s_DPH" style="margin-right:3px;width:310px;" class="text-end" value="<?php echo $Celkova_cena_s_DPH;?>"></br></td></tr>
	
	<tr><td colspan="2"><br></td></tr>
	<tr><td style = "vertical-align:top !important;">Poznámka objednávky:</td><td><textarea type="text" name="Obj_Poznamka" cols="25" rows="5" disabled><?php echo $Obj_Poznamka;?></textarea></td></tr>
	<tr><td style = "vertical-align:top !important;">Poznámka cenovej ponuky:</td><td><textarea type="text" name="Cp_Poznamka" cols="25" rows="5" <?php echo disabled_vyber($akcia); ?>><?php echo $Cp_Poznamka;?></textarea></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2">
	<?php 
	if($_POST["ID_vybratej_objednavky"] )
	{
		$ID_cenovej_ponuky=$_POST["ID_cenovej_ponuky"];
		$akcia=$_POST["akcia"];
	 	?>
		<input style="float:left;" type="submit" value="Uložiť">
		<?php 
	}
	elseif($akcia == "update")
	{
		?>
		<input style="float:left;" type="submit" value="Uložiť">	
		<?php 
	}
	?>
	<input type="hidden" name="akcia" value="<?php echo $akcia;?>">
	<input type="hidden" name="ID_cenovej_ponuky" value="<?php echo $ID_cenovej_ponuky;?>">
	<input type="hidden" name="ID_objednavky" value="<?php echo $ID_objednavky;?>">
	<input type="hidden" name="Obj_Meno" value="<?php echo $Obj_Nazov_firmy;?>">
	<input type="hidden" name="Obj_Meno" value="<?php echo $Obj_Meno;?>">
	<input type="hidden" name="Obj_Meno" value="<?php echo $Obj_Priezvisko;?>">
	
	<input style="float:left;" form="back" type="submit" name="back" class = "button2" value="Späť">
	</td></tr>
</table>
</form>

<form action="zmena_cenovej_ponuky.php" id="back" method="POST"></form>
</p>
<?php
mysqli_close($dblink); // odpojit sa z DB
?>
</body>
</html>