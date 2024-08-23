<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<?php include "head.php";?>
</head>
<body>

<?php
include "navbar.php";
include "config.php"; 
include "lib.php";



// pripojenie na DB server a zapamatame si pripojenie do premennej $dblink
$dblink = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db);

if (!$dblink) { // kontrola ci je pripojenie na db dobre ak nie tak napise chybu
	echo "Chyba pripojenia na DB!</br>";
	die(); // ukonci vykonanie php
}

mysqli_set_charset($dblink, "utf8mb4");

?>
<div class="container text-black">
<h2 class="mb-2">Registrácia</h2>
</div>
<p>
<form action="zmena_zakaznika.php" method="POST">

<p class="oznam text-grey text-start">Položky označené <span class="hviezdicka">*</span> sú povinné</p>

<?php 
if($_GET["vysledok"] == "chyba")
$hlaska = "Zadané prihlasovacie meno už existuje.";
?>
<p class="oznam red text-start"><?php echo $hlaska;?></p>

<table  class="zoznam" border="1" align="center" cellpadding="0" cellspacing="0">
	<tr><td colspan="2"><b>Kontaktné údaje:</b></td></tr>
	<tr><td>Názov firmy:</td><td style="width:50%;"> <input type="text" name="Zak_Nazov_firmy" value="<?php echo $Nazov_firmy;?>" <?php echo disabled($akcia);?> /></br></td></tr>
	<tr><td>Meno:<span class="hviezdicka">*</span></td><td> <input required type="text" name="Zak_Meno" value="<?php echo $Meno;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>Priezvisko:<span class="hviezdicka">*</span></td><td> <input required type="text" name="Zak_Priezvisko" value="<?php echo $Priezvisko;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>Email:<span class="hviezdicka">*</span></td><td> <input required type="text" name="Zak_Email" value="<?php echo $Email;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>Telefon:<span class="hviezdicka">*</span></td><td> <input required type="text" name="Zak_Telefon" value="<?php echo $Telefon;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Prihlasovacie údaje:</b>&nbsp;&nbsp;<span id="txtHint"><br></span></td></tr>
	<tr><td>Prihlasovacie meno:<span class="hviezdicka">*</span></td><td><input required type="text" name="Prihlasovacie_meno" onchange="OnChangeLogin(this.value,'<?php echo $Prihlasovacie_meno;?>')" value="<?php echo $Prihlasovacie_meno;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>Prihlasovacie heslo:<span class="hviezdicka">*</span></td><td><input required type="password" name="Prihlasovacie_heslo" style="padding-right: 40px;" id="password" value="<?php echo $Prihlasovacie_heslo;?>" <?php echo disabled($akcia);?>>   
	<input type="checkbox" class="ZobrazitHeslo" onclick="ShowPassword()"><span style="vertical-align:middle;">Zobraziť heslo</span></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Fakturačná adresa:</b></td></tr>
	<tr><td>Ulica a číslo domu (fakturačná adresa):<span class="hviezdicka">*</span></td><td> <input required type="text" name="Zak_Ulica_cislo_fakturacna" value="<?php echo $Ulica_cislo_fakturacna;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>Mesto (fakturačná adresa):<span class="hviezdicka">*</span></td><td> <input required type="text" name="Zak_Mesto_fakturacna" value="<?php echo $Mesto_fakturacna;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>PSČ (fakturačná adresa):<span class="hviezdicka">*</span></td><td> <input required type="text" name="Zak_PSC_fakturacna" value="<?php echo $PSC_fakturacna;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Dodacia adresa:</b><span class="grey"> (Vyplňte len v prípade, že sa líši od fakturačnej.)</span></td></tr>
	<tr><td>Ulica a číslo domu (dodacia adresa):</td><td> <input type="text" name="Zak_Ulica_cislo_dodacia" value="<?php echo $Ulica_cislo_dodacia;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>Mesto (dodacia adresa):</td><td> <input type="text" name="Zak_Mesto_dodacia" value="<?php echo $Mesto_dodacia;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>PSČ (dodacia adresa):</td><td> <input type="text" name="Zak_PSC_dodacia" value="<?php echo $PSC_dodacia;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Firemné údaje:</b></td></tr>
	<tr><td>IČO:</td><td> <input type="text" name="Zak_ICO" value="<?php echo $ICO;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>DIČ:</td><td> <input type="text" name="Zak_DIC" value="<?php echo $DIC;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>IČ DPH:</td><td> <input type="text" name="Zak_IC_DPH" value="<?php echo $IC_DPH;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Bankové spojenie:</b></td></tr>
	<tr><td>Názov banky:</td><td><input type="text" name="Zak_Nazov_banky" value="<?php echo $Nazov_banky;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td>Číslo účtu:</td><td><input type="text" name="Zak_Cislo_uctu" value="<?php echo $Cislo_uctu;?>" <?php echo disabled($akcia);?>></br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td style = "vertical-align:top !important;">Poznámka:</td><td><textarea type="text" name="Zak_Poznamka" cols="25" rows="5" <?php echo disabled($akcia); ?>><?php echo $Poznamka;?></textarea></td></tr>
	<tr><td colspan="2"><br></td></tr>

	<tr><td colspan="2">
	<input style="float:left;margin-left:0.5rem;" type="submit" value="Registrovať">
	<input type="hidden" name="akcia" value="insert">
	<input type="hidden" name="ID_zakaznika" value="<?php echo $ID_zakaznika;?>">
	<input type="hidden" name="ID_pouzivatela" value="<?php echo $ID_pouzivatela;?>">
	</td></tr>
</table>
</form>
<br>
<br>
<?php
include "footer.php";
mysqli_close($dblink); // odpojit sa z DB
?>
</body>
</html>