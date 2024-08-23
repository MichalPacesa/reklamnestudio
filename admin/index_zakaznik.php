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

//define( '_VALID_MOS', 1 );
include 'login.php';

if (!isset($_SESSION['Login_Prihlasovacie_meno']))  // nie je prihlaseny
{
exit;
}
$dblink = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db); // pripojenie do databazy

include "navbar.php"; // navigacia

if(ZistiPrava("Zobraz_zakaznikov",$dblink) == 0){ 
echo "<p class='oznam'>Nemáte práva na zobrazenie zákazníkov.</p>";
exit;
}   

$server=$_SERVER['HTTP_HOST'];
if($_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name."/admin/zakaznik.php")  
{
	$_SESSION["hlaska"] = "";
}

if($_SERVER['HTTP_REFERER'] !="http://".$server.'/'.$dir_name.'/admin/zakaznik.php' AND $_SERVER['HTTP_REFERER'] !="http://".$server.'/'.$dir_name.'/'.'admin/index_zakaznik.php'
AND $_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name.'/'."admin/index_zakaznik.php?doprava=1" AND  $_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name.'/'."admin/index_zakaznik.php?dolava=1")
{
	$_SESSION["offset"] = 0;
	$_SESSION["stranka"] = 1;
	$offset = 0;
	$stranka = 1;
}


if(!isset($_POST['skupina']))
	$skupina="";
else
	$skupina=$_POST['skupina'];

if(!isset($_POST['hladaj']))
	$hladaj="";
else
	$hladaj=$_POST['hladaj'];

if (!$dblink) { // kontrola ci je pripojenie na databazu dobre ak nie tak napise chybu
	echo "Chyba pripojenia na DB!</br>";
	exit;
}

$sql_pocet="SELECT count(1) as pocet_riadkov FROM zakaznik z"; // pocet vsetkych zaznamov
$pocet_riadkov = zisti_pocet_riadkov($dblink,$sql_pocet);	
// limit na stranke je nastaveny v config.php
if($skupina == "")
{
	$podmienka="";
}	
else
{
		$podmienka=" AND z.Zak_Mesto_fakturacna='".$skupina."'";
		$offset = 0;
		$stranka = 1;
		$_SESSION["offset"]=0;
		$_SESSION["stranka"]=1;
        $sql_pocet="SELECT count(1) as pocet_riadkov FROM zakaznik z WHERE 1 $podmienka";
        $pocet_riadkov = zisti_pocet_riadkov($dblink,$sql_pocet);	
        $limit=$pocet_riadkov; // limit na stranke budu vsetky zaznamy podla vyberu
		
}

if($hladaj)	
	{
	$hladaj2=str_replace( ' ','',$hladaj);  // vymaze vsetky medzery
    $podmienka.=" AND (z.ID_zakaznika='$hladaj' OR z.Zak_Nazov_firmy LIKE '%$hladaj%'";  // vyhlada nazov firmy aj s medzerami napr. Firma s.r.o.
	$podmienka.=" OR z.Zak_Meno LIKE '%$hladaj%' OR z.Zak_Priezvisko LIKE '%$hladaj%'  OR z.Zak_Mesto_fakturacna LIKE '%$hladaj%'"; // vyhlada meno alebo priezvisko alebo mesto 
	$podmienka.=" OR z.Zak_Email LIKE '%$hladaj%' OR z.Zak_Telefon LIKE '%$hladaj%' OR z.Zak_ICO LIKE '%$hladaj%'"; // vyhlada email alebo telefon alebo ico
	$podmienka.=" OR CONCAT(z.Zak_Meno,z.Zak_Priezvisko) like '%$hladaj2%')"; // vyhlada meno a priezvisko spolu
	$sql_pocet="SELECT count(1) as pocet_riadkov FROM zakaznik z WHERE 1 $podmienka";
    $pocet_riadkov = zisti_pocet_riadkov($dblink,$sql_pocet);	
    $limit=$pocet_riadkov; // limit na stranku budu vsetky zaznamy podla vyberu
    }

?>

<h1>Zákazníci</h1>
<table id="filtre" class = "zoznam" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<?php if(ZistiPrava("Edit_zakaznikov",$dblink) == 1):  ?>
		<td style = "text-align:center !important; width:15%;"><a href="zakaznik.php">Nový zákazník</a></td>
		<?php endif;  ?>
		<td class = "text-center" style = "text-align:center !important; width:25%;">
		
		<p>
			<form action="index_zakaznik.php" method="post" >
				Hľadaj:
					<input type="text" id="hladaj" name="hladaj" class="select_height" autofocus value="<?php echo $_POST['hladaj']?>">
					<input type="image" src="images/magnifying-glass-solid.png" title="Vyhľadaj" style="width:17px;">
					<input type="image" src="images/xmark-solid.png" title="Vymazať" style="width:15px;" onclick="forms[0].hladaj.value='';">
					<input type="hidden" name="skupina" value="<?php echo $skupina;?>">
			</form>
		</p>	
		</td>
			
		<td style = "text-align:center !important; width:30%;">
<?php
			/* mestá filter */			
			$sql_filter = "Select * FROM zakaznik group by Zak_Mesto_fakturacna ";
			$vysledok=mysqli_query($dblink,$sql_filter);
			if (!$vysledok):
				echo "Doslo k chybe pri vytvarani SQL dotazu !";
			else:
?>
			<p><form action="index_zakaznik.php" method="post" >
				Mesto:
				<select class = "select_height" name="skupina" onChange="forms[1].submit()">
					<option value="">Všetky</option> 
				<?php while($riadok=mysqli_fetch_assoc($vysledok)): ?>
					<option value="<?php echo $riadok["Zak_Mesto_fakturacna"];?>"<?php echo selected($skupina,$riadok["Zak_Mesto_fakturacna"]);?>><?php echo $riadok["Zak_Mesto_fakturacna"]; ?></option>
				<?php endwhile; ?>
				</select>
				<input type="hidden" name="hladaj" value="<?php echo $hladaj;?>">
			</form></p>	
			<?php endif; 	/* end mesta filter */ ?>
		
		</td>
	</tr>
</table>

<?php


if (!$dblink) { // kontrola ci je pripojenie na db dobre ak nie tak napise chybu
	echo "Chyba pripojenia na DB!</br>";exit;
} 
 // pripojenie na db je ok a mozeme pracovat s db

	mysqli_set_charset($dblink, "utf8mb4"); // nastavit znakovu sadu.
	
	// ---------------- Idem deletovat zaznam do tabuluky ------------------
	if ($_POST["akcia"]=="delete" && $_POST["ID_zakaznika"]!="" && $_POST["back"] != "Späť"): // chcem akciu delete z hidden parametru formulara a id nesmie byt prazdne
 	$id=$_POST["ID_zakaznika"];
	$meno=$_POST["Zak_Meno"];
	$priezvisko=$_POST["Zak_Priezvisko"];
	
	$sql = "DELETE FROM objednavka WHERE ID_zakaznika=$id"; // insert dat
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz delete a vysledky nacitame do premennej $vysledok
	if (!$vysledok)
	{  
		echo "Chyba pri vymazávaní z objednávky! </br>";
	}
	else 
	{ 
		echo "<p class='oznam'>Objednávky od zákazníka $meno $priezvisko boli odstránené z databázy.</p>";
	}
	
	$sql = "DELETE FROM zakaznik WHERE ID_zakaznika=$id"; // insert dat
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz delete a vysledky nacitame do premennej $vysledok
	if (!$vysledok)
	{  
		echo "Chyba pri vymazávaní záznamu! </br>";
	}
	else 
	{ 
		echo "<p class='oznam'>Zákazník $meno $priezvisko bol odstránený z databázy.</p>";
	}

endif;
//-----------------------------------------------------------------------	


if($_SESSION["hlaska"])	
{ 
	echo $_SESSION["hlaska"];
}


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
     echo "<p class=\"cervene  \">⠀⠀Nenašli sa žiadne záznamy.</p>";

// osetrit hranice strankovania
if ($offset<0): // ak $offset je mensi ako nula - nemozes do minusu resetujem to na nulu
	$offset=0;
	$stranka=1;
	$_SESSION["offset"]=$offset;
	$_SESSION["stranka"]=$stranka;
	echo "<p class=\"cervene\">⠀⠀Viac záznamov smerom doľava neexistuje.</p>";
endif;


if ($offset > $pocet_riadkov-1){ // ak $offset je vacsi ako pocet zaznamov 
		$offset=$offset-$limit;
		$stranka=$stranka-1;
		$_SESSION["offset"]=$offset;
		$_SESSION["stranka"]=$stranka;
		if(!$hladaj and !$skupina)
		echo "<p class=\"cervene  \">⠀⠀Viac záznamov smerom doprava neexistuje.</p>";
	}
	
	/* hlavny select zoznamu */
	$sql="SELECT * FROM zakaznik z LEFT JOIN registrovany_pouzivatel r ON z.ID_pouzivatela = r.ID_pouzivatela WHERE 1 $podmienka ORDER BY ID_zakaznika DESC LIMIT $limit OFFSET $offset";
	//echo "Hlavny select: ".$sql;
	
?>	

<br>

<div id="tabulka">
<table  class="zoznam" border="1" align="center" cellpadding="0" cellspacing="0">
<tr>
	<th width="10%">Číslo zákazníka</th>
	<th width="10%">Názov firmy</th>
	<th width="15%">Meno a priezvisko</th>
	<th width="15%">Mesto</th>
	<th width="15%">Email</th>
	<th width="15%">Telefón</th>
	<th width="15%">IČO</th>
	<th width="5%" colspan="2"></th>
</tr>
	<?php
	
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz - select a vysledky nacitame do premennej $vysledok
    // Vo vysledkoch moze byt viac riadkov, takze ich musime prezriet v cykle, riadok po riadku a vypisat ich.	
	while ($riadok = mysqli_fetch_assoc($vysledok)) { // mysqli_fetch_assoc - vrati hodnty zo selectu a priradi im mena vybranych stlpcov
	
	$ID_zakaznika_pocet_objednavok = $riadok["ID_zakaznika"];
	$sql_pocet_objednavok="SELECT count(1) as pocet_objednavok FROM objednavka WHERE ID_zakaznika=$ID_zakaznika_pocet_objednavok";
	$vysledok_pocet_objednavok = mysqli_query($dblink, $sql_pocet_objednavok); 
	$riadok_pocet_objednavok = mysqli_fetch_row($vysledok_pocet_objednavok);
	
	
	?>
		<tr>
			<td><?php echo $riadok["ID_zakaznika"];?></td>
			<td><?php echo $riadok["Zak_Nazov_firmy"];?></td>
			<td><?php echo $riadok["Zak_Meno"]." ".$riadok["Zak_Priezvisko"];?></td>
			<td><?php echo $riadok["Zak_Mesto_fakturacna"];?></td>
			<td><?php echo $riadok["Zak_Email"];?></td>
			<td><?php echo $riadok["Zak_Telefon"];?></td>
			<td><?php echo $riadok["Zak_ICO"];?></td>
			<td style = "text-align: center; ">
			
			
			<script>
					$(document).ready(function() {
				<?php
				echo "$('#zmazat_riadok".$riadok["ID_zakaznika"]."').submit(function() {";
				?>
		
				var c = confirm("Ste si istý, že chcete zmazať zákazníka číslo"+
				" "+<?php echo '"'.$riadok["ID_zakaznika"].'"'?>+
				" "+"-"+" "+<?php echo '"'.$riadok["Zak_Nazov_firmy"].' '.$riadok["Zak_Meno"].' '.$riadok["Zak_Priezvisko"].'"'?>+" ?");
				
				<?php if($riadok_pocet_objednavok[0]): ?>
				if(c)
				{ 
					c = confirm("Zákazník "+<?php echo '"'.$riadok["Zak_Nazov_firmy"].' '.$riadok["Zak_Meno"].' '.$riadok["Zak_Priezvisko"].'"'?>+
					" má v systéme objednávky. Ste si istý, že chcete zmazať všetky jeho objednávky?");
				}
				<?php endif; ?>
									
				return c; 
		
					});
				});
				</script>
				

				<div class="container d-flex ms-3">
					<form  class="me-4" action="zakaznik.php" method="post">
						<input class="mt-1" type="image" name="zobrazit" Value="Zobraziť" src="images/eye-solid.png" title="Zobraziť" style="width:35px;">
						<input type="hidden" name="ID_zakaznika" value="<?php echo $riadok["ID_zakaznika"];?>">
					</form>
					<?php if(ZistiPrava("Edit_zakaznikov",$dblink) == 1):  ?>
					<form  class="me-4" action="zakaznik.php" method="post">
						<input class="mt-1" type="image" name="editovat" Value="Editovať" src="images/pen-to-square-solid.png" title="Editovať" style="width:30px;">
						<input type="hidden" name="ID_zakaznika" value="<?php echo $riadok["ID_zakaznika"];?>">
					</form>	
	
					<form class="me-4" id="zmazat_riadok<?php echo $riadok["ID_zakaznika"];?>" action="index_zakaznik.php" method="post">
						<input class="mt-1" type="image" name="delete" Value="delete" src="images/trash-can-solid.png" title="Zmazať" style="width:25px;">
						<input type="hidden" name="ID_zakaznika" value="<?php echo $riadok["ID_zakaznika"];?>">
						<input type="hidden" name="Zak_Meno" value="<?php echo $riadok["Zak_Meno"];?>">
						<input type="hidden" name="Zak_Priezvisko" value="<?php echo $riadok["Zak_Priezvisko"];?>">
						<input type="hidden" name="akcia" value="delete">
					</form>
					<?php endif;  ?>
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
if(!$skupina AND !$hladaj):
?>
<div id="tabulka-sipky">
	<table id="sipky" border="1" align="center" cellpadding="0" cellspacing="0">
		<tr><td align="left">
		<a href="index_zakaznik.php?dolava=1"><img src="images/left-long-solid.png" title="Doľava" style="width:35px; margin-left:4px;"></a>
		</td>
		<td align="center">Stránka: <?php echo $stranka;?></td>
		<td align="right">
		<a href="index_zakaznik.php?doprava=1"><img src="images/right-long-solid.png" title="Doprava" style="width:35px; margin-right:4px;"></a>
		</td></tr>
	</table>
</div>

<hr>	
	
<?php endif; ?>

</body>
</html>