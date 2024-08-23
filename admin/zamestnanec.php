<!DOCTYPE html>
<?php session_start(); ?>
<html>
<head>

<?php include "head.php";

include 'login.php';

if (!isset($_SESSION['Login_Prihlasovacie_meno']))  // nie je prihlaseny
{
exit;
}
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />


<script>
  $( function() {
    $("#datepicker,#datepicker2").datepicker({
	  dateFormat: "dd.mm.yy",
	  yearRange: "1900:",
      changeMonth: true,
      changeYear: true
    });
  } );
  
 </script>
 
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

$ID_zamestnanca=$_POST["ID_zamestnanca"];
$ID_zamestnanca=intval($ID_zamestnanca);

if($ID_zamestnanca)
{
	$zobrazit=$_POST["zobrazit_x"];
	$sql="SELECT * FROM zamestnanec z LEFT JOIN registrovany_pouzivatel r ON z.ID_pouzivatela = r.ID_pouzivatela WHERE z.ID_zamestnanca = $ID_zamestnanca";
	if($zobrazit) 
	{
		$akcia="preview";
		$nadpis = "Zamestnanec č. ".$ID_zamestnanca;
		
	}	
	else
	{
		if(ZistiPrava("Edit_zakaznikov",$dblink) == 0) 
		{ 
			include "navbar.php";
			echo "<p class='oznam'>Nemáte práva na editáciu a pridávanie zamestnancov</p>";exit;
		}	
		$akcia = "update"; 
		$nadpis = "Editácia zamestnanca č. ".$ID_zamestnanca;
	}
	$vysledok = mysqli_query($dblink,$sql); 
	$riadok = mysqli_fetch_assoc($vysledok);
	
	$Meno = $riadok["Zam_Meno"];
	$Priezvisko = $riadok["Zam_Priezvisko"];
	$Datum_narodenia = $riadok["Zam_Datum_narodenia"];
	$Email = $riadok["Zam_Email"];
	$Telefon = $riadok["Zam_Telefon"];
	$Ulica_cislo = $riadok["Zam_Ulica_cislo"];
	$Mesto = $riadok["Zam_Mesto"];
	$PSC = $riadok["Zam_PSC"];
	$Uvazok = $riadok["Uvazok"];
	$Druh_zamestnania = $riadok["Druh_zamestnania"];
	$Pracovna_pozicia = $riadok["Pracovna_pozicia"];
	$Datum_nastupu = $riadok["Datum_nastupu"];
	$Nazov_firmy = $riadok["Zam_Nazov_firmy"];
	$ICO = $riadok["Zam_ICO"];
	$DIC = $riadok["Zam_DIC"];
	$IC_DPH = $riadok["Zam_IC_DPH"];
	$Nazov_banky = $riadok["Zam_Nazov_banky"];
	$Cislo_uctu = $riadok["Zam_Cislo_uctu"];
	$Poznamka = $riadok["Zam_Poznamka"];
	
	$ID_pouzivatela = $riadok["ID_pouzivatela"];
	$Prihlasovacie_meno =  $riadok["Prihlasovacie_meno"];
	$Prihlasovacie_heslo =  "";
	$ID_prava =  $riadok["ID_prava"];
	
	if($Datum_narodenia == "0000-00-00") $Datum_narodenia = ""; 
	else $Upraveny_Datum_narodenia = date("d.m.Y", strtotime($Datum_narodenia));
	
	if($Datum_nastupu == "0000-00-00") $Datum_nastupu = "";
	else $Upraveny_Datum_nastupu = date("d.m.Y", strtotime($Datum_nastupu));
}
else
{
	if(ZistiPrava("Edit_zakaznikov",$dblink) == 0) 
	{ 
		include "navbar.php";
		echo "<p class='oznam'>Nemáte práva na editáciu a pridávanie zamestnancov</p>";exit;
	}
	$akcia = "insert";
	$nadpis = "Nový zamestnanec";
	$ID_zamestnanca = "";
	$Meno = "";
	$Priezvisko = "";
	$Datum_narodenia = "";
	$Email = "";
	$Telefon = "";
	$Ulica_cislo = "";
	$Mesto = "";
	$PSC = "";
	$Druh_zamestnania = "";
	$Uvazok = "";
	$Pracovna_pozicia = "";
	$Datum_nastupu = "";
	$Nazov_firmy = "";
	$ICO = "";
	$DIC = "";
	$IC_DPH = "";
	$Nazov_banky = "";
	$Cislo_uctu = "";
	$Poznamka = "";
	
	$Prihlasovacie_meno =  "";
	$Prihlasovacie_heslo =  "";
	$ID_prava =  "";
	
	$Upraveny_Datum_narodenia = "";
	$Upraveny_Datum_nastupu = "";
}
?>

<h1><?php echo $nadpis; ?></h1>

<p>
<form action="zmena_zamestnanca.php" method="POST">

<p class="oznam text-grey text-start">Položky označené <span class="red bold">*</span> sú povinné</p>

<?php 
if($_GET["vysledok"] == "chyba")
$hlaska = "Zadané prihlasovacie meno už existuje.";
?>
<p class="oznam red text-start"><?php echo $hlaska;?></p>

<table  class="zoznam" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr><td colspan="2"><b>Kontaktné údaje:</b></td></tr>
	<tr><td>Meno:<span class="hviezdicka">*</span></td><td style="width:50%;"><input required type="text" name="Zam_Meno" value="<?php echo $Meno;?>" <?php echo disabled($akcia);?>  /></td></tr>
	<tr><td>Priezvisko:<span class="hviezdicka">*</span></td><td> <input required type="text" name="Zam_Priezvisko" value="<?php echo $Priezvisko;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>Email:<span class="hviezdicka">*</span></td><td> <input required type="text" name="Zam_Email" value="<?php echo $Email;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>Telefon:<span class="hviezdicka">*</span></td><td> <input required type="text" name="Zam_Telefon" value="<?php echo $Telefon;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>Dátum narodenia:<span class="hviezdicka">*</span></td><td> <input required type="text" id="datepicker" name="Zam_Datum_narodenia" value="<?php echo $Upraveny_Datum_narodenia;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Adresa:</b></td></tr>
	<tr><td>Ulica a číslo domu:<span class="hviezdicka">*</span></td><td> <input required type="text" name="Zam_Ulica_cislo" value="<?php echo $Ulica_cislo;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>Mesto:<span class="hviezdicka">*</span></td><td> <input required type="text" name="Zam_Mesto" value="<?php echo $Mesto;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>PSČ:<span class="hviezdicka">*</span></td><td> <input required type="text" name="Zam_PSC" value="<?php echo $PSC;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Údaje o zamestnaní:</b></td></tr>
		
	<!-- DRUH ZAMESTNANIA -->
	<tr><td style = "vertical-align:middle !important;">Druh zamestnania<span class="hviezdicka">*</span></td>
	<td style = "vertical-align:middle !important;">

	<select required class="vyber" name="Druh zamestnania" <?php echo disabled($akcia);?>>
	
	<?php 
	if($akcia == "insert")
	{ 
	$Druh_zamestnania = 1;
	}
	?>
	<option value="1" <?php echo selected($Druh_zamestnania,'1') ?> > Zamestnanec </option>
	<option value="2" <?php echo selected($Druh_zamestnania,'2') ?> > Podnikateľ </option>
	<option value="3" <?php echo selected($Druh_zamestnania,'3') ?> > Brigádnik </option>

	</select>
	</td></tr>
	<!-- /DRUH ZAMESTNANIA -->
	
	<tr><td>Úväzok (v hod./za týždeň):<span class="hviezdicka">*</span></td> <td><input required type="number" max="99" name="Uvazok" placehodler="Zadajte počet hodín do týždňa" value="<?php echo $Uvazok;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>Pracovná pozícia:</td> <td><input type="text" name="Pracovna_pozicia" value="<?php echo $Pracovna_pozicia;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>Dátum nástupu:</td> <td><input type="text" id="datepicker2" name="Datum_nastupu" value="<?php echo $Upraveny_Datum_nastupu;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Firemné údaje:</b></td></tr>
	<tr><td>Názov firmy:</td><td><input type="text" name="Zam_Nazov_firmy" value="<?php echo $Nazov_firmy;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>IČO:</td><td> <input type="text" name="Zam_ICO" value="<?php echo $ICO;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>DIČ:</td><td> <input type="text" name="Zam_DIC" value="<?php echo $DIC;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>IČ DPH:</td><td> <input type="text" name="Zam_IC_DPH" value="<?php echo $IC_DPH;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Bankové spojenie:</b></td></tr>
	<tr><td>Názov banky:</td><td><input type="text" name="Zam_Nazov_banky" value="<?php echo $Nazov_banky;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>Číslo účtu:</td><td><input type="text" name="Zam_Cislo_uctu" value="<?php echo $Cislo_uctu;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Prihlasovacie údaje:</b>&nbsp;&nbsp;<span id="txtHint"></span></td></tr>
	<tr><td>Prihlasovacie meno:<span class="hviezdicka">*</span></td><td>
		<input required type="text" name="Prihlasovacie_meno" onchange="OnChangeLogin(this.value,'<?php echo $Prihlasovacie_meno;?>')" value="<?php echo $Prihlasovacie_meno;?>" <?php echo disabled($akcia);?>>
		<input type="hidden" name="Prihlasovacie_meno_old" value="<?php echo $Prihlasovacie_meno;?>"> 
	</br></td></tr>
	<?php if($akcia=="insert"):?>
	<tr><td>Prihlasovacie heslo:<span class="hviezdicka">*</span></td><td><input required type="password" name="Prihlasovacie_heslo" id="password" value="<?php echo $Prihlasovacie_heslo;?>" <?php echo disabled($akcia);?>>   
	<?php else:?>
	<tr><td>Prihlasovacie heslo (nechajte prázdne ak sa nemení):</span></td><td><input type="password" name="Prihlasovacie_heslo" id="password" value="<?php echo $Prihlasovacie_heslo;?>" <?php echo disabled($akcia);?>>   
	<?php endif; ?>
	<input type="checkbox" class="ZobrazitHeslo" onclick="ShowPassword()" <?php echo disabled($akcia);?>><span style="vertical-align:middle;">Zobraziť heslo</span></td></tr>
	
	<!-- Rola -->
	<tr><td style = "vertical-align:middle !important;">Rola:<span class="hviezdicka">*</span></td>
	<td style = "vertical-align:middle !important;">
	<?php $sql = "Select * FROM prava WHERE ID_prava != 3";
			$vysledok=mysqli_query($dblink,$sql);
			if (!$vysledok):
				echo "Doslo k chybe pri vytvarani SQL dotazu !";
			else:
?>
			<form style = "margin-top: 12px;">
				<select class = "vyber filter" name="ID_prava" onChange="forms[2].submit()" <?php echo disabled($akcia);?>>
				<?php while($riadok=mysqli_fetch_assoc($vysledok)): ?>
					<option value="<?php echo $riadok["ID_prava"];?>" <?php echo selected($ID_prava,$riadok["ID_prava"]); ?>><?php echo $riadok["Rola"]; ?></option>
				<?php endwhile; ?>
				</select>
			</form>	
			<?php endif; /* end Rola */ ?>
	</td></tr>
	
	<tr><td colspan="2"><br></td></tr>
	<tr><td style = "vertical-align:top !important;">Poznámka:</td><td><textarea type="text" name="Zam_Poznamka" rows="5" <?php echo disabled($akcia); ?>><?php echo $Poznamka;?></textarea></td></tr>
	<tr><td colspan="2"><br></td></tr>
	
	<tr><td colspan="2">
	<?php if($akcia !='preview'):?>
	<input style="float:left;" type="submit" value="Uložiť">
	<?php endif; ?>
	<input type="hidden" name="akcia" value="<?php echo $akcia;?>">
	<input type="hidden" name="ID_zamestnanca" value="<?php echo $ID_zamestnanca;?>">
	<input type="hidden" name="ID_pouzivatela" value="<?php echo $ID_pouzivatela;?>">
	<input style="float:left;" form="back" type="submit" name="back" class="button2" value="Späť">
	</td></tr>
	</table>
	</form>
	
	<form id="back" action="zmena_zamestnanca.php" method="POST"></form>
</p>
<?php
mysqli_close($dblink); // odpojit sa z DB
?>

</body>
</html>