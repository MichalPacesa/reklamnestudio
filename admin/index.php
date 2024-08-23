<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head><?php include "head.php" ?></head>

<body>
<?php
include "config.php";  // konfiguracia
include "lib.php";	//	funkcie    
include 'login.php';

if (!isset($_SESSION['Login_Prihlasovacie_meno']) )  // Ak nie je pouzivatel prihlaseny tak exit
{
	exit;
}

$dblink = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db); // pripojenie do databazy

include "navbar.php"; // navigacia

if(ZistiPrava("Zobraz_objednavky",$dblink) == 0){ 
	echo "<p class='oznam'>Nemáte práva na zobrazenie objednavok.</p>";
	exit;
}   

$server=$_SERVER['HTTP_HOST'];
if($_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name."/admin/objednavka.php")  
{
	$_SESSION["hlaska"] = "";
}

if($_SERVER['HTTP_REFERER'] !="http://".$server.'/'.$dir_name.'/admin/objednavka.php' AND $_SERVER['HTTP_REFERER'] !="http://".$server.'/'.$dir_name.'/'.'admin/index.php'
AND $_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name.'/'."admin/index.php?doprava=1" AND  $_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name.'/'."admin/index.php?dolava=1")
{
	$_SESSION["offset"]=0;
	$_SESSION["stranka"]=1;
	$offset = 0;
	$stranka = 1;
}

if(!isset($_POST['skupina']))
	$skupina=0;
else
	$skupina=$_POST['skupina'];


if(!isset($_POST['hladaj']))
	$hladaj="";
else
	$hladaj=$_POST['hladaj'];

if(!isset($_POST['Stav_objednavky']))
	$Stav_objednavky=0;
else
	$Stav_objednavky=$_POST['Stav_objednavky'];

if (!$dblink) { // kontrola ci je pripojenie na databazu dobre ak nie tak napise chybu
	echo "Chyba pripojenia na DB!</br>";
	exit;
}

$sql_pocet="SELECT count(1) as pocet_riadkov FROM objednavka"; // pocet vsetkych zaznamov
$pocet_riadkov = zisti_pocet_riadkov($dblink,$sql_pocet);

// limit na stranke je nastaveny v config.php
if($skupina == 0 AND $Stav_objednavky == 0){
	$podmienka="";
}	
elseif($skupina)
{
		$podmienka=" AND o.ID_zamestnanca=".$skupina;
		$offset = 0;
		$stranka=1;
		$_SESSION["offset"]=0;
		$_SESSION["stranka"]=1;
        $sql_pocet="SELECT count(1) as pocet_riadkov FROM objednavka o WHERE 1 $podmienka";
		//echo $sql_pocet;
        $pocet_riadkov = zisti_pocet_riadkov($dblink,$sql_pocet);	
		//echo 'pocet riadkov'.$pocet_riadkov;
        $limit=$pocet_riadkov; // limit na stranke budu vsetky zaznamy podla vyberu
		
}

elseif($Stav_objednavky)
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

if($hladaj)	{
	$hladaj2=str_replace( ' ','',$hladaj);  // vymaze vsetky medzery
    $podmienka.=" AND (o.ID_objednavky='$hladaj' OR o.Obj_Nazov_firmy LIKE '%$hladaj%'";  // vyhlada nazov firmy aj s medzerami napr. Firma s.r.o.
	$podmienka.=" OR o.Obj_Meno LIKE '%$hladaj%' OR o.Obj_Priezvisko LIKE '%$hladaj%'  OR o.Obj_Mesto_fakturacna LIKE '%$hladaj%'"; // vyhlada meno alebo priezvisko alebo mesto
	$podmienka.=" OR    CONCAT(o.Obj_Meno,o.Obj_Priezvisko) like '%$hladaj2%')"; // vyhlada meno a priezvisko spolu
	$sql_pocet="SELECT count(1) as pocet_riadkov FROM objednavka o WHERE 1 $podmienka";
    $pocet_riadkov = zisti_pocet_riadkov($dblink,$sql_pocet);	
    $limit=$pocet_riadkov; // limit na stranku budu vsetky zaznamy podla vyberu
}

?>

<h1>Objednávky</h1>
<table id="filtre" class = "zoznam" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<?php if(ZistiPrava("Edit_objednavky",$dblink) == 1):  ?>
		<td style = "text-align:center !important; width:15%;"><a href="objednavka.php">Nová objednávka</a></td>
		<?php endif;  ?>
		<td class = "text-center" style = "text-align:center !important; width:25%;">
		<p>
			<form action="index.php" method="post" >
				Hľadaj:
					<input type="text" id="hladaj" name="hladaj" class="select_height" autofocus value="<?php echo $_POST['hladaj']?>">
					<input type="image" src="images/magnifying-glass-solid.png" title="Vyhľadaj" style="width:17px;">
					<input type="image" src="images/xmark-solid.png" title="Vymazať" style="width:15px;" onclick="forms[0].hladaj.value='';">
					<input type="hidden" name="skupina" value="<?php echo $skupina;?>">
                    <input type="hidden" name="Stav_objednavky" value="<?php echo $Stav_objednavky;?>">
			</form>
		</p>	
		</td>
		
		<td style = "text-align:center !important; width:30%;">
		<p><form action="index.php" method="post">
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
		
		<td style = "text-align:center !important; width:30%;">
		<?php
			/* zamestnanci filter */			
			$sql = "Select * FROM zamestnanec"; 
			$vysledok=mysqli_query($dblink,$sql);
			if (!$vysledok):
				echo "Doslo k chybe pri vytvarani SQL dotazu !";
			else():
			?>
			<p><form action="index.php" method="post" >
				Pridelený zamestnanec:
				<select class = "select_height" name="skupina" onChange="forms[2].submit()">
					<option value="0">Všetci</option> 
				<?php while($riadok=mysqli_fetch_assoc($vysledok)): ?>
					<option value="<?php echo $riadok["ID_zamestnanca"];?>"<?php echo selected($skupina,$riadok["ID_zamestnanca"]); ?>><?php echo $riadok["Zam_Meno"]." ".$riadok["Zam_Priezvisko"]; ?></option>
				<?php endwhile; ?>
				</select>
				<input type="hidden" name="hladaj" value="<?php echo $hladaj;?>">
			</form></p>	
			<?php endif; /* end zamestnanci filter */ ?>
		
		</td>
	</tr>
</table>

<?php


if (!$dblink) { // kontrola ci je pripojenie na db dobre ak nie tak vypise chybu
	echo "Chyba pripojenia na DB!</br>";exit;
} 


	mysqli_set_charset($dblink, "utf8mb4"); // nastavit znakovu sadu.
	
	// ---------------- Idem deletovat zaznam do tabuluky ------------------
	if ($_POST["akcia"]=="delete" && $_POST["ID_objednavky"]!="" && $_POST["back"] != "Späť"): // chcem akciu delete z hidden parametru formulara a id nesmie byt prazdne
 	$id=$_POST["ID_objednavky"];
	$meno=$_POST["Obj_Meno"];
	$priezvisko=$_POST["Obj_Priezvisko"];
	$ma_cp=$_POST["ma_cp"];
	
	if($ma_cp == true)
	{	
		$sql = "DELETE FROM cenova_ponuka WHERE ID_objednavky=$id"; // insert dat
		$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz delete a vysledky nacitame do premennej $vysledok
		if (!$vysledok)
		{  
			echo "<p class='cervene'>Chyba pri vymazávaní cenovej ponuky!</p>";
		}
		else 
		{ 
			echo "<p class='oznam'>Cenová ponuka od zákazníka $meno $priezvisko bola odstránená z databázy.</p>";
		}
	}
	
	$sql = "DELETE FROM objednavka WHERE ID_objednavky=$id"; // insert dat
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz delete a vysledky nacitame do premennej $vysledok
	if (!$vysledok)
	{  
		echo "<p class='cervene'>Chyba pri vymazávaní objednávky!</p>";
	}
	else 
	{ 
		echo "<p class='oznam'>Objednávka číslo $id od zákazníka $meno $priezvisko bola odstránená z databázy.</p>";
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
		if(!$hladaj and !$skupina and !$Stav_objednavky)
		echo "<p class=\"cervene  \">⠀⠀Viac záznamov smerom doprava neexistuje.</p>";
	}
	/* hlavny select zoznamu */
	$sql="SELECT * FROM objednavka o LEFT JOIN zamestnanec z ON o.ID_zamestnanca = z.ID_zamestnanca WHERE 1 $podmienka ORDER BY ID_objednavky DESC LIMIT $limit OFFSET $offset";
	
?>	

<br>

<div id="tabulka">
<table  class="zoznam" border="1" align="center" cellpadding="0" cellspacing="0">
<tr>
	<th width="10%">Číslo objednávky</th>
	<th width="10%">Názov firmy</th>
	<th width="15%">Meno a priezvisko</th>
	<th width="15%">Mesto</th>
	<th width="15%">Dátum vytvorenia</th>
	<th width="15%">Stav objednávky</th>
	<th width="15%">Pridelený zamestnanec</th>
	<th width="5%" colspan="2"></th>
</tr>
	<?php
	
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz - select a vysledky nacitame do premennej $vysledok	
	while ($riadok = mysqli_fetch_assoc($vysledok)) { // mysqli_fetch_assoc - vrati hodnty zo selectu a priradi im mena vybranych stlpcov
	
	$ID_objednavky_pocet_CP = $riadok["ID_objednavky"];
	$sql_pocet_CP ="SELECT count(1) as pocet_cp FROM cenova_ponuka WHERE ID_objednavky=$ID_objednavky_pocet_CP";
	$vysledok_pocet_CP = mysqli_query($dblink, $sql_pocet_CP); 
	$riadok_pocet_CP = mysqli_fetch_row($vysledok_pocet_CP);
	
	$Upraveny_Datum_vytvorenia = date("d.m.Y", strtotime($riadok["Obj_Datum_vytvorenia"]));
	
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
			<td><?php echo $riadok["Obj_Mesto_fakturacna"];?></td>
			<td><?php echo "$Upraveny_Datum_vytvorenia";?></td>
			<td><?php echo "$Stav_objednavky_text";?></td>
			<td><?php echo $riadok["Zam_Meno"]." ".$riadok["Zam_Priezvisko"];?></td>
			<td style = "text-align: center;">
				<script>
					$(document).ready(function() {
				<?php
				echo "$('#zmazat_riadok".$riadok["ID_objednavky"]."').submit(function() {";
				?>
		
				var c = confirm("Ste si istý že chcete zmazať objednávku číslo"+
				" "+<?php echo '"'.$riadok["ID_objednavky"].'"'?>+
				" "+"od zákazníka:"+" "+<?php echo '"'.$riadok["Obj_Nazov_firmy"].' '.$riadok["Obj_Meno"].' '.$riadok["Obj_Priezvisko"].'"'?>+" ?");
				
				<?php if($riadok_pocet_CP[0]):  ?>
				if(c)
				{ 
					c = confirm("Objednávka číslo "+<?php echo '"'.$riadok["ID_objednavky"].' od zákazníka '.$riadok["Obj_Nazov_firmy"].' '.$riadok["Obj_Meno"].' '.$riadok["Obj_Priezvisko"].'"'?>+
					" má v systéme cenovú ponuku. Ste si istý, že chcete zmazať aj cenovú ponuku?");
				}
				<?php endif;?>	
				return c; 
		
					});
				});
				</script>
				
				<?php
				if($riadok_pocet_CP[0])
				{
					$ma_cp = true;	
				}
				else
				{
					$ma_cp = false;
				}	
					
				?>
				
				<div class="container d-flex ms-3">
					<form  class="me-4" action="objednavka.php" method="post">
						<input class="mt-1" type="image" name="zobrazit" Value="Zobraziť" src="images/eye-solid.png" title="Zobraziť" style="width:35px;">
						<input type="hidden" name="ID_objednavky" value="<?php echo $riadok["ID_objednavky"];?>">
					</form>
					
					<?php if(ZistiPrava("Edit_objednavky",$dblink) == 1):  ?>		
					<form  class="me-4" action="objednavka.php" method="post">
						<input class="mt-1" type="image" name="editovat" Value="Editovať" src="images/pen-to-square-solid.png" title="Editovať" style="width:30px;">
						<input type="hidden" name="ID_objednavky" value="<?php echo $riadok["ID_objednavky"];?>">
					</form>	
	
					<form class="me-4" id="zmazat_riadok<?php echo $riadok["ID_objednavky"];?>" action="index.php" method="post">
						<input class="mt-1" type="image" name="delete" Value="delete" src="images/trash-can-solid.png" title="Zmazať" style="width:25px;">
						<input type="hidden" name="ID_objednavky" value="<?php echo $riadok["ID_objednavky"];?>">
						<input type="hidden" name="Obj_Meno" value="<?php echo $riadok["Obj_Meno"];?>">
						<input type="hidden" name="Obj_Priezvisko" value="<?php echo $riadok["Obj_Priezvisko"];?>">
						<input type="hidden" name="akcia" value="delete">
						<input type="hidden" name="ma_cp" value="<?php echo $ma_cp; ?>">
					</form>
					<?php endif;  ?>
				</div>	
			 
		   
  
			</td>
			
		
		</tr>
		
		<?php
	}
	mysqli_close($dblink); //odpojenie od databazy


?>
</table>
</div>
<br>
<?php
if(!$skupina AND !$hladaj AND !$Stav_objednavky):
?>
<div>
	<table id="sipky" border="1" align="center" cellpadding="0" cellspacing="0">
		<tr><td align="left">
		<a href="index.php?dolava=1"><img src="images/left-long-solid.png" title="Doľava" style="width:35px; margin-left:4px;"></a>
		</td>
		<td align="center">Stránka: <?php echo $stranka;?></td>
		<td align="right">
		<a href="index.php?doprava=1"><img src="images/right-long-solid.png" title="Doprava" style="width:35px; margin-right:4px;"></a>
		</td></tr>
	</table>
</div>

<hr>	
	
<?php endif; ?>

</body>
</html>