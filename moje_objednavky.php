<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<?php include "head.php" ?>
</head>

<body>
<?php

include "config.php";  // konfiguracia
include "lib.php";	//	funkcie    

if (!isset($_SESSION['Login_Prihlasovacie_meno_web']) )  // nie je prihlaseny
{
include "navbar.php";
echo "<p class='oznam'>Nemáte práva na prezeranie svojich objednávok &nbsp; <a href='login.php'>Prihlásiť sa</a></p>";
exit;
}

include "navbar_log.php"; // navigacia

$server=$_SERVER['HTTP_HOST'];
if($_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name."/admin/objednavka.php")  
{
	$_SESSION["hlaska"] = "";
}

if($_SERVER['HTTP_REFERER'] !="http://".$server.'/'.$dir_name.'admin/objednavka.php' 
AND $_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name.'/'."moje_objednavky?doprava=1" AND  $_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name.'/'."moje_objednavky.php?dolava=1")  
{
	$_SESSION["offset"]=0;
	$_SESSION["stranka"]=1;
	$offset = 0;
	$stranka = 1;
}

if(!isset($_POST['hladaj']))
	$hladaj="";
else
	$hladaj=$_POST['hladaj'];

if(!isset($_POST['Stav_objednavky']))
	$Stav_objednavky=0;
else
	$Stav_objednavky=$_POST['Stav_objednavky'];



$dblink = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db); // pripojenie do databazy

if (!$dblink) { // kontrola ci je pripojenie na databazu dobre ak nie tak napise chybu
	echo "Chyba pripojenia na DB!</br>";
	exit;
}

$sql_pocet="SELECT count(1) as pocet_riadkov FROM objednavka"; // pocet vsetkych zaznamov
$pocet_riadkov = zisti_pocet_riadkov($dblink,$sql_pocet);	
// limit na stranke je nastaveny v config.php
if($Stav_objednavky == 0)
{
	$podmienka="";
}	
else
{
		$podmienka=" AND o.Stav_objednavky=".$Stav_objednavky;
		$offset = 0;
		$stranka=1;
		$_SESSION["offset"]=0;
		$_SESSION["stranka"]=1;
        $sql_pocet="SELECT count(1) as pocet_riadkov FROM objednavka o WHERE 1 $podmienka";
        $pocet_riadkov = zisti_pocet_riadkov($dblink,$sql_pocet);	
        $limit=$pocet_riadkov;// limit na stranku budu vsetky zaznamy podla vyberu

	
}

if($hladaj)	
	{
	$hladaj2=str_replace( ' ','',$hladaj);  // vymaze vsetky medzery
    $podmienka.=" AND (o.ID_objednavky='$hladaj' OR o.Obj_Nazov_firmy LIKE '%$hladaj%'";  // vyhlada nazov firmy aj s medzerami napr. Firma s.r.o.
	$podmienka.=" OR o.Obj_Meno LIKE '%$hladaj%' OR o.Obj_Priezvisko LIKE '%$hladaj%'  OR o.Obj_Mesto_fakturacna LIKE '%$hladaj%'"; // vyhlada meno alebo priezvisko alebo mesto
	$podmienka.=" OR    CONCAT(o.Obj_Meno,o.Obj_Priezvisko) like '%$hladaj2%')"; // vyhlada meno a priezvisko spolu
	$sql_pocet="SELECT count(1) as pocet_riadkov FROM objednavka o WHERE 1 $podmienka";
    $pocet_riadkov = zisti_pocet_riadkov($dblink,$sql_pocet);	
    $limit=$pocet_riadkov; // limit na stranku budu vsetky zaznamy podla vyberu
    }

?>

<div class="container text-black">
<h2 class="mb-2">Moje objednávky</h2>
</div>
<br>
<table id="filtre" class = "zoznam" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td class = "text-center" style = "text-align:center !important; width:25%;">
		
		<p>
			<form action="moje_objednavky.php" method="post" >
				Hľadaj:
					<input type="text" id="hladaj" name="hladaj" class="select_height" autofocus value="<?php echo $_POST['hladaj']?>">
					<input type="image" src="images/magnifying-glass-solid.png" title="Vyhľadaj" style="width:16px;">
					<input type="image" src="images/xmark-solid.png" title="Vymazať" style="width:15px;" onclick="forms[0].hladaj.value='';">
                    <input type="hidden" name="Stav_objednavky" value="<?php echo $Stav_objednavky;?>">
			</form>
		</p>	
		</td>
		
		<td style = "text-align:center !important; width:30%;">
		<p><form action="moje_objednavky.php" method="post">
		Stav Objednávky:
		<select name="Stav_objednavky" class="select_height" onChange="forms[1].submit()">
	
			<option value="0" <?php echo selected($Stav_objednavky,'0') ?> > Všetky </option>
			<option value="1" <?php echo selected($Stav_objednavky,'1') ?> > Prijatá </option>
			<option value="2" <?php echo selected($Stav_objednavky,'2') ?> > Rozpracovaná </option>
			<option value="3" <?php echo selected($Stav_objednavky,'3') ?> > Dokončená - neuhradená </option>
			<option value="4" <?php echo selected($Stav_objednavky,'4') ?> > Dokončená - uhradená </option>
			<option value="5" <?php echo selected($Stav_objednavky,'5') ?> > Odoslaná </option>
			<option value="6" <?php echo selected($Stav_objednavky,'6') ?> > Vybavená </option>
			<option value="7" <?php echo selected($Stav_objednavky,'7') ?> > Stornovaná </option>
	
		</select>
		</form></p>
		</td>	
	</tr>
</table>
<br>
<?php

if (!$_SESSION["offset"]) // ak neexistuje premenna $_SESSION["offset"] tak ju nastavíme
{	$offset=0;
	$_SESSION["offset"]=$offset;
	$stranka=1;
	$_SESSION["stranka"]=1;
}
else // alebo ak existuje tak si nastavim hodnotu $offset na $_SESSION["offset"];
{
	$offset=$_SESSION["offset"];
	$stranka=$_SESSION["stranka"];
}

// vypocet strankovania
$dolava=$_GET["dolava"];
$doprava=$_GET["doprava"];

if ($doprava)
	{
	$offset=$offset+$limit; 
	$_SESSION["offset"]=$offset; // zapamatam si novu hodnotu offsetu do session premennej
	$stranka=$stranka+1;
	$_SESSION["stranka"]=$stranka;
	}

if ($dolava)
	{
	$offset=$offset-$limit; 
	$_SESSION["offset"]=$offset; // zapamatam si novu hodnotu offsetu do session premennej
	$stranka=$stranka-1;
	$_SESSION["stranka"]=$stranka; 
	}
	
if($pocet_riadkov==0)
     echo "<p class=\"oznam\">Nenašli sa žiadne záznamy.</p>";

// osetrit hranice strankovania
if ($offset<0): // ak $offset je mensi ako nula - nemozes do minusu resetujem to na nulu
	$offset=0;
	$stranka=1;
	$_SESSION["offset"]=$offset;
	$_SESSION["stranka"]=$stranka;
	echo "<p class=\"oznam\">Viac záznamov smerom doľava neexistuje.</p>";
endif;


if ($offset > $pocet_riadkov-1){ // ak $offset je vacsi ako pocet zaznamov 
		$offset=$offset-$limit;
		$stranka=$stranka-1;
		$_SESSION["offset"]=$offset;
		$_SESSION["stranka"]=$stranka;
		if(!$hladaj and !$Stav_objednavky)
		echo "<p class=\"oznam\">Viac záznamov smerom doprava neexistuje.</p>";
	}
	
	$ID_pouzivatela = $_SESSION['Login_ID_pouzivatela_web'];
	$ID_pouzivatela = intval($ID_pouzivatela);
	$sql2="SELECT * FROM zakaznik z WHERE ID_pouzivatela = $ID_pouzivatela";
	$vysledok2 = mysqli_query($dblink,$sql2);
	$riadok2 = mysqli_fetch_assoc($vysledok2);
	$ID_zakaznika = $riadok2["ID_zakaznika"];
	
	/* hlavny select zoznamu */
	$sql="SELECT * FROM objednavka o WHERE ID_zakaznika = $ID_zakaznika $podmienka ORDER BY ID_objednavky DESC LIMIT $limit OFFSET $offset";
	?>	

<div id="tabulka">
<table class="zoznam" border="1" align="center" cellpadding="0" cellspacing="0">
<tr>
	<th width="10%">Číslo objednávky</th>
	<th width="10%">Názov firmy</th>
	<th width="15%">Meno a priezvisko</th>
	<th width="15%">Dátum vytvorenia</th>
	<th width="15%">Dátum úpravy</th>
	<th width="15%">Stav objednávky</th>
	<th width="5%" colspan="2"></th>
</tr>
	<?php
	
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz - select a vysledky nacitame do premennej $vysledok
    // Vo vysledkoch moze byt viac riadkov, takze ich musime prezriet v cykle, riadok po riadku a vypisat ich.	
	while ($riadok = mysqli_fetch_assoc($vysledok)) { // mysqli_fetch_assoc - vrati hodnty zo selectu a priradi im mena vybranych stlpcov
	
	$Upraveny_Datum_vytvorenia = date("d.m.Y", strtotime($riadok["Obj_Datum_vytvorenia"]));
	$Upraveny_Datum_upravy = date("d.m.Y", strtotime($riadok["Obj_Datum_upravy"]));
	
	switch ($riadok["Stav_objednavky"])
	{
	case 1:
		$Stav_objednavky_text = "Prijatá";
		break;
	case 2:
        $Stav_objednavky_text = "Rozpracovaná";
        break;
    case 3:
        $Stav_objednavky_text = "Dokončená - neuhradená";
        break;
	
	case 4:
        $Stav_objednavky_text = "Dokončená - uhradená";
        break;
	
	case 5:
        $Stav_objednavky_text = "Odoslaná";
        break;
	
	case 6:
        $Stav_objednavky_text = "Vybavená";
        break;
	
	case 7:
        $Stav_objednavky_text = "Stornovaná";
        break;
		
	default:
		$Stav_objednavky_text = "";
		break;
	}
		?>
		<tr>
			<td><?php echo $riadok["ID_objednavky"];?></td>
			<td><?php echo $riadok["Obj_Nazov_firmy"];?></td>
			<td><?php echo $riadok["Obj_Meno"]." ".$riadok["Obj_Priezvisko"];?></td>
			<td><?php echo "$Upraveny_Datum_vytvorenia";?></td>
			<td><?php echo "$Upraveny_Datum_upravy";?></td>
			<td><?php echo "$Stav_objednavky_text";?></td>
			<td style = "text-align: center; ">
			
				<div class="container d-flex ms-3">
					<form  class="me-4" action="objednavka.php" method="post">
						<input class="mt-1" type="image" name="zobrazit" Value="Zobraziť" src="images/eye-solid.png" title="Zobraziť" style="width:35px;">
						<input type="hidden" name="ID_objednavky" value="<?php echo $riadok["ID_objednavky"];?>">
					</form>	
				</div>	

			</td>
			
		</tr>
		
		<?php
	}
	mysqli_close($dblink); //odpojim sa od databazy


?>
</table>
</div>
<br>
<?php
if(!$hladaj AND !$Stav_objednavky):
?>
<div id="tabulka-sipky">
	<table id="sipky" border="1" align="center" cellpadding="0" cellspacing="0">
		<tr><td align="left">
		<a href="moje_objednavky.php?dolava=1"><img src="images/left-long-solid.png" title="Doľava" style="width:35px; margin-left:4px;"></a>
		</td>
		<td align="center">Stránka: <?php echo $stranka;?></td>
		<td align="right">
		<a href="moje_objednavky.php?doprava=1"><img src="images/right-long-solid.png" title="Doprava" style="width:35px; margin-right:4px;"></a>
		</td></tr>
	</table>
</div>

<hr>	
	
<?php endif; ?>

</body>
</html>