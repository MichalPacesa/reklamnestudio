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

if(ZistiPrava("Zobraz_zamestnancov",$dblink) == 0){ 
echo "<p class='oznam'>Nemáte práva na zobrazenie zamestnancov.</p>";
exit;
}   

$server=$_SERVER['HTTP_HOST'];
if($_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name."/admin/zamestnanec.php")  
{
	$_SESSION["hlaska"] = "";
}

if($_SERVER['HTTP_REFERER'] !="http://".$server.'/'.$dir_name.'/admin/zamestnanec.php' AND $_SERVER['HTTP_REFERER'] !="http://".$server.'/'.$dir_name.'/'.'admin/index_zamestnanec.php'
AND $_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name.'/'."admin/index_zamestnanec.php?doprava=1" AND  $_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name.'/'."admin/index_zamestnanec.php?dolava=1")
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

if(!isset($_POST['pracovna_pozicia']))
	$pracovna_pozicia="";
else
	$pracovna_pozicia=$_POST['pracovna_pozicia'];



if (!$dblink) { // kontrola ci je pripojenie na databazu dobre ak nie tak napise chybu
	echo "Chyba pripojenia na DB!</br>";
	exit;
}

$sql_pocet="SELECT count(1) as pocet_riadkov FROM zamestnanec z"; // pocet vsetkych zaznamov
$pocet_riadkov = zisti_pocet_riadkov($dblink,$sql_pocet);	
// limit na stranke je nastaveny v config.php
if($skupina == "" AND $pracovna_pozicia == "")
{
	$podmienka="";
}	
elseif($skupina)
{
		$podmienka=" AND z.Zam_Mesto='".$skupina."'";
		$offset = 0;
		$stranka = 1;
		$_SESSION["offset"] = 0;
		$_SESSION["stranka"] = 1;
        $sql_pocet="SELECT count(1) as pocet_riadkov FROM zamestnanec z WHERE 1 $podmienka";
        $pocet_riadkov = zisti_pocet_riadkov($dblink,$sql_pocet);	
        $limit=$pocet_riadkov; // limit na stranke budu vsetky zaznamy podla vyberu	
}

elseif($pracovna_pozicia)
{
		$podmienka=" AND z.Pracovna_pozicia='".$pracovna_pozicia."'";
		$offset = 0;
		$stranka = 1;
		$_SESSION["offset"]=0;
		$_SESSION["stranka"]=1;
        $sql_pocet="SELECT count(1) as pocet_riadkov FROM zamestnanec z WHERE 1 $podmienka";
        $pocet_riadkov = zisti_pocet_riadkov($dblink,$sql_pocet);	
        $limit=$pocet_riadkov; // limit na stranke budu vsetky zaznamy podla vyberu
}

if($hladaj)	
	{
	$hladaj2=str_replace( ' ','',$hladaj);  // vymaze vsetky medzery
    $podmienka.=" AND (z.ID_zamestnanca='$hladaj'";  // vyhlada nazov firmy aj s medzerami napr. Firma s.r.o.
	$podmienka.=" OR z.Zam_Meno LIKE '%$hladaj%' OR z.Zam_Priezvisko LIKE '%$hladaj%'  OR z.Zam_Mesto LIKE '%$hladaj%'"; // vyhlada meno alebo priezvisko alebo mesto 
	$podmienka.=" OR z.Zam_Email LIKE '%$hladaj%' OR z.Zam_Telefon LIKE '%$hladaj%' OR z.Pracovna_pozicia LIKE '%$hladaj%' OR z.Uvazok LIKE '%$hladaj%'"; // vyhlada email alebo telefon alebo ico
	$podmienka.=" OR CONCAT(z.Zam_Meno,z.Zam_Priezvisko) like '%$hladaj2%')"; // vyhlada meno a priezvisko spolu
	$sql_pocet="SELECT count(1) as pocet_riadkov FROM zamestnanec z WHERE 1 $podmienka";
    $pocet_riadkov = zisti_pocet_riadkov($dblink,$sql_pocet);	
    $limit=$pocet_riadkov; // limit na stranku budu vsetky zaznamy podla vyberu
    }

?>

<h1>Zamestnanci</h1>
<table id="filtre" class = "zoznam" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<?php if(ZistiPrava("Edit_zamestnancov",$dblink) == 1):  ?>
		<td style = "text-align:center !important; width:15%;"><a href="zamestnanec.php">Nový zamestnanec</a></td>
		<?php endif; ?>
		<td class = "text-center" style = "text-align:center !important; width:25%;">
		
		<p>
			<form action="index_zamestnanec.php" method="post" >
				Hľadaj:
					<input type="text" id="hladaj" name="hladaj" class="select_height" autofocus value="<?php echo $_POST['hladaj']?>">
					<input type="image" src="images/magnifying-glass-solid.png" title="Vyhľadaj" style="width:17px;">
					<input type="image" src="images/xmark-solid.png" title="Vymazať" style="width:15px;" onclick="forms[0].hladaj.value='';">
					<input type="hidden" name="skupina" value="<?php echo $skupina;?>">
					<input type="hidden" name="pracovna_pozicia" value="<?php echo $pracovna_pozicia;?>">
			</form>
		</p>	
		</td>
			
		<td style = "text-align:center !important; width:30%;">
		<?php
			/* mestá filter */			
			$sql_filter = "Select * FROM zamestnanec group by Zam_Mesto";
			$vysledok=mysqli_query($dblink,$sql_filter);
			if (!$vysledok):
				echo "Doslo k chybe pri vytvarani SQL dotazu !";
			else:
		?>
			<p><form action="index_zamestnanec.php" method="post" >
				Mesto:
				<select class = "select_height" name="skupina" onChange="forms[1].submit()">
					<option value="">Všetky</option> 
				<?php while($riadok=mysqli_fetch_assoc($vysledok)): ?>
					<option value="<?php echo $riadok["Zam_Mesto"];?>"<?php echo selected($skupina,$riadok["Zam_Mesto"]);?>><?php echo $riadok["Zam_Mesto"]; ?></option>
				<?php endwhile; ?>
				</select>
				<input type="hidden" name="hladaj" value="<?php echo $hladaj;?>">
			</form></p>	
			<?php endif; 	/* end mesta filter */ ?>
		
		</td>
		
		<td style = "text-align:center !important; width:30%;">
		<?php
			/* pracovna pozicia filter */			
			$sql_filter = "Select * FROM zamestnanec WHERE Pracovna_pozicia != 'NULL' group by Pracovna_pozicia";
			$vysledok=mysqli_query($dblink,$sql_filter);
			if (!$vysledok):
				echo "Doslo k chybe pri vytvarani SQL dotazu !";
			else:
		?>
			<p><form action="index_zamestnanec.php" method="post">
				Pracovna pozícia:
				<select class = "select_height" name="pracovna_pozicia" onChange="forms[2].submit()">
					<option value="">Všetky</option> 
				<?php while($riadok=mysqli_fetch_assoc($vysledok)): ?>
					<option value="<?php echo $riadok["Pracovna_pozicia"];?>"<?php echo selected($pracovna_pozicia,$riadok["Pracovna_pozicia"]);?>><?php echo $riadok["Pracovna_pozicia"]; ?></option>
				<?php endwhile; ?>
				</select>
				<input type="hidden" name="hladaj" value="<?php echo $hladaj;?>">
			</form></p>	
			<?php endif; 	/* end pracovna pozicia filter */ ?>
		
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
	if ($_POST["akcia"]=="delete" && $_POST["ID_zamestnanca"]!="" && $_POST["back"] != "Späť"): // chcem akciu delete z hidden parametru formulara a id nesmie byt prazdne
 	$id=$_POST["ID_zamestnanca"];
	$meno=$_POST["Zam_Meno"];
	$priezvisko=$_POST["Zam_Priezvisko"];
	$id_pouzivatela=$_POST["ID_pouzivatela"];
	
	$sql = "DELETE FROM zamestnanec WHERE ID_zamestnanca=$id"; 
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz delete a vysledky nacitame do premennej $vysledok
	if (!$vysledok)
	{  
		echo "Chyba pri vymazávaní zamestnanca! </br>";
	}
	else 
	{ 
		echo "<p class='oznam'>Zamestnanec $meno $priezvisko bol odstránený z databázy.</p>";
	}
	
	if($id_pouzivatela)
	{
		$sql = "DELETE FROM registrovany_pouzivatel WHERE ID_pouzivatela=$id_pouzivatela"; // insert dat
		$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz delete a vysledky nacitame do premennej $vysledok
		if (!$vysledok)
		{  
			echo "Chyba pri vymazávaní registrovaného pouzivatela! </br>";
		}
		else 
		{ 
			echo "<p class='oznam'>Registrovaný pouzivatel $meno $priezvisko bol odstránený z databázy.</p>";
		}
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
		if(!$hladaj and !$skupina and !$pracovna_pozicia)
		echo "<p class=\"cervene  \">⠀⠀Viac záznamov smerom doprava neexistuje.</p>";
	}
	
	/* hlavny select zoznamu */
	$sql="SELECT * FROM zamestnanec z LEFT JOIN registrovany_pouzivatel r ON z.ID_pouzivatela = r.ID_pouzivatela WHERE 1 $podmienka ORDER BY ID_zamestnanca DESC LIMIT $limit OFFSET $offset";
	//echo "Hlavny select: ".$sql;
	
?>	

<br>

<div id="tabulka">
<table  class="zoznam" border="1" align="center" cellpadding="0" cellspacing="0">
<tr>
	<th width="10%">Číslo zamestnanca</th>
	<th width="15%">Meno a priezvisko</th>
	<th width="15%">Mesto</th>
	<th width="15%">Email</th>
	<th width="15%">Telefón</th>
	<th width="15%">Pracovná pozícia</th>
	<th width="10%">Úväzok</th>
	<th width="5%" colspan="2"></th>
</tr>
	<?php
	
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz - select a vysledky nacitame do premennej $vysledok
    // Vo vysledkoch moze byt viac riadkov, takze ich musime prezriet v cykle, riadok po riadku a vypisat ich.	
	while ($riadok = mysqli_fetch_assoc($vysledok)) { // mysqli_fetch_assoc - vrati hodnty zo selectu a priradi im mena vybranych stlpcov
	
	?>
		<tr>
			<td><?php echo $riadok["ID_zamestnanca"];?></td>
			<td><?php echo $riadok["Zam_Meno"]." ".$riadok["Zam_Priezvisko"];?></td>
			<td><?php echo $riadok["Zam_Mesto"];?></td>
			<td><?php echo $riadok["Zam_Email"];?></td>
			<td><?php echo $riadok["Zam_Telefon"];?></td>
			<td><?php echo $riadok["Pracovna_pozicia"];?></td>
			<td><?php echo $riadok["Uvazok"];?></td>
			<td style = "text-align: center; ">
			
			
			<script>
					$(document).ready(function() {
				<?php
				echo "$('#zmazat_riadok".$riadok["ID_zamestnanca"]."').submit(function() {";
				?>
		
				var c = confirm("Ste si istý, že chcete zmazať zamestnanca číslo"+
				" "+<?php echo '"'.$riadok["ID_zamestnanca"].'"'?>+
				" "+"-"+" "+<?php echo '"'.$riadok["Zam_Nazov_firmy"].' '.$riadok["Zam_Meno"].' '.$riadok["Zam_Priezvisko"].'"'?>+" ?");
				
									
				return c; 
		
					});
				});
				</script>
				

				<div class="container d-flex ms-3">
					<form  class="me-4" action="zamestnanec.php" method="post">
						<input class="mt-1" type="image" name="zobrazit" Value="Zobraziť" src="images/eye-solid.png" title="Zobraziť" style="width:35px;">
						<input type="hidden" name="ID_zamestnanca" value="<?php echo $riadok["ID_zamestnanca"];?>">
					</form>
					<?php if(ZistiPrava("Edit_zamestnancov",$dblink) == 1):  ?>
					<form  class="me-4" action="zamestnanec.php" method="post">
						<input class="mt-1" type="image" name="editovat" Value="Editovať" src="images/pen-to-square-solid.png" title="Editovať" style="width:30px;">
						<input type="hidden" name="ID_zamestnanca" value="<?php echo $riadok["ID_zamestnanca"];?>">
					</form>	
	
					<form class="me-4" id="zmazat_riadok<?php echo $riadok["ID_zamestnanca"];?>" action="index_zamestnanec.php" method="post">
						<input class="mt-1" type="image" name="delete" Value="delete" src="images/trash-can-solid.png" title="Zmazať" style="width:25px;">
						<input type="hidden" name="ID_zamestnanca" value="<?php echo $riadok["ID_zamestnanca"];?>">
						<input type="hidden" name="Zam_Meno" value="<?php echo $riadok["Zam_Meno"];?>">
						<input type="hidden" name="Zam_Priezvisko" value="<?php echo $riadok["Zam_Priezvisko"];?>">
						<input type="hidden" name="ID_pouzivatela" value="<?php echo $riadok["ID_pouzivatela"];?>">
						<input type="hidden" name="akcia" value="delete">
					</form>
					<?php endif; ?>
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
if(!$skupina AND !$hladaj AND !$pracovna_pozicia):
?>
<div id="tabulka-sipky">
	<table id="sipky" border="1" align="center" cellpadding="0" cellspacing="0">
		<tr><td align="left">
		<a href="index_zamestnanec.php?dolava=1"><img src="images/left-long-solid.png" title="Doľava" style="width:35px; margin-left:4px;"></a>
		</td>
		<td align="center">Stránka: <?php echo $stranka;?></td>
		<td align="right">
		<a href="index_zamestnanec.php?doprava=1"><img src="images/right-long-solid.png" title="Doprava" style="width:35px; margin-right:4px;"></a>
		</td></tr>
	</table>
</div>

<hr>	
	
<?php endif; ?>

</body>
</html>