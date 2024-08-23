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

if(ZistiPrava("Zobraz_cenove_ponuky",$dblink) == 0){ 
echo "<p class='oznam'>Nemáte práva na zobrazenie cenových ponúk.</p>";
exit;
}   

//echo $_SESSION["hlaska"];
$server=$_SERVER['HTTP_HOST']; 
if($_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name."/admin/cenova_ponuka.php" AND $_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name."/admin/index_cenova_ponuka.php" AND $_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name."/admin/poslat_cenovu_ponuku.php")  
{
	$_SESSION["hlaska"] = "";
}
//echo $_SERVER['HTTP_REFERER'];
if($_SERVER['HTTP_REFERER'] !="http://".$server.'/'.$dir_name.'/admin/cenova_ponuka.php' AND $_SERVER['HTTP_REFERER'] !="http://".$server.'/'.$dir_name.'/'.'admin/index_cenova_ponuka.php'
AND $_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name.'/'."admin/index_cenova_ponuka.php?doprava=1" AND  $_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name.'/'."admin/index_cenova_ponuka.php?dolava=1")

{
	$_SESSION["offset"]=0;
	$_SESSION["stranka"]=1;
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

$sql_pocet="SELECT count(1) as pocet_riadkov FROM cenova_ponuka"; // pocet vsetkych zaznamov
$pocet_riadkov = zisti_pocet_riadkov($dblink,$sql_pocet);	
// limit na stranke je nastaveny v config.php
if($skupina == "")
{
	$podmienka="";
}	
else
{
		$podmienka=" AND o.Obj_Mesto_fakturacna='".$skupina."'";
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



if($hladaj)	
	{
	$hladaj=trim($hladaj);	
	$hladaj2=str_replace( ' ','',$hladaj);  // vymaze vsetky medzery pre meno a priezvisko
    $podmienka.=" AND (cp.ID_cenovej_ponuky='$hladaj' OR o.Obj_Nazov_firmy LIKE '%$hladaj%'";  // vyhlada nazov firmy aj s medzerami napr. Firma s.r.o.
	$podmienka.=" OR o.Obj_Meno LIKE '%$hladaj%' OR o.Obj_Priezvisko LIKE '%$hladaj%'  OR o.Obj_Mesto_fakturacna LIKE '%$hladaj%'"; // vyhlada meno alebo priezvisko alebo mesto
	$podmienka.=" OR    CONCAT(o.Obj_Meno,o.Obj_Priezvisko) like '%$hladaj2%')"; // vyhlada meno a priezvisko spolu
	$sql_pocet="SELECT count(1) as pocet_riadkov FROM cenova_ponuka cp LEFT JOIN objednavka o ON cp.ID_objednavky = o.ID_objednavky WHERE 1 $podmienka";
    $pocet_riadkov = zisti_pocet_riadkov($dblink,$sql_pocet);	
    $limit=$pocet_riadkov; // limit na stranku budu vsetky zaznamy podla vyberu
    }

//echo $pocet_riadkov;

?>

<h1>Cenové ponuky</h1>
<table id="filtre" class = "zoznam" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<?php if(ZistiPrava("Edit_cenove_ponuky",$dblink) == 1):  ?>
		<td style = "text-align:center !important; width:15%;"><a href="cenova_ponuka.php">Nová cenová ponuka</a></td>
		<?php endif;  ?>
		<td class = "text-center" style = "text-align:center !important; width:25%;">
		
		<p>
			<form action="index_cenova_ponuka.php" method="post" >
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
			/* mesta filter */			
			$sql = "Select * FROM objednavka group by Obj_Mesto_fakturacna"; 
			$vysledok=mysqli_query($dblink,$sql);
			if (!$vysledok):
				echo "Doslo k chybe pri vytvarani SQL dotazu !";
			else:
?>
			<p><form action="index_cenova_ponuka.php" method="post">
				Mesto:
				<select class = "select_height" name="skupina" onChange="forms[1].submit()">
					<option value="">Všetky</option> 
				<?php while($riadok=mysqli_fetch_assoc($vysledok)): ?>
					<option value="<?php echo $riadok["Obj_Mesto_fakturacna"];?>"<?php echo selected($skupina,$riadok["Obj_Mesto_fakturacna"]); ?>><?php echo $riadok["Obj_Mesto_fakturacna"];?></option>
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
	if ($_POST["akcia"]=="delete" && $_POST["ID_cenovej_ponuky"]!="" && $_POST["back"] != "Späť"): // chcem akciu delete z hidden parametru formulara a id nesmie byt prazdne
 	$id=$_POST["ID_cenovej_ponuky"];
	$meno=$_POST["Obj_Meno"];
	$priezvisko=$_POST["Obj_Priezvisko"];
	
	$sql = "DELETE FROM cenova_ponuka WHERE ID_cenovej_ponuky=$id"; // insert dat
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz delete a vysledky nacitame do premennej $vysledok
	if (!$vysledok)
	{  
		echo "<p class='cervene'>Chyba pri vymazávaní záznamu!</p>";
	}
	else 
	{ 
		echo "<p class='oznam'>Cenová ponuka číslo $id od zákazníka $meno $priezvisko bola odstránená z databázy.</p>";
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
	$sql="SELECT * FROM cenova_ponuka cp LEFT JOIN objednavka o ON cp.ID_objednavky = o.ID_objednavky WHERE 1 $podmienka ORDER BY ID_cenovej_ponuky DESC LIMIT $limit OFFSET $offset";
	//echo $sql;
	
?>	

<br>

<div id="tabulka">
<table  class="zoznam" border="1" align="center" cellpadding="0" cellspacing="0">
<tr>
	<th width="15%">Číslo cenovej ponuky</th>
	<th width="10%">Názov firmy</th>
	<th width="15%">Meno a priezvisko</th>
	<th width="10%">Mesto</th>
	<th width="15%">Dátum vytvorenia</th>
	<th width="15%">Dátum poslednej úpravy</th>
	<th width="15%">Celková cena s DPH</th>
	<th width="5%" colspan="2"></th>
</tr>
	<?php
	
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz - select a vysledky nacitame do premennej $vysledok
    // Vo vysledkoch moze byt viac riadkov, takze ich musime prezriet v cykle, riadok po riadku a vypisat ich.	
	while ($riadok = mysqli_fetch_assoc($vysledok)) { // mysqli_fetch_assoc - vrati hodnty zo selectu a priradi im mena vybranych stlpcov
	
	$Upraveny_Datum_vytvorenia = date("d.m.Y", strtotime($riadok["Cp_Datum_vytvorenia"]));
	$Upraveny_Datum_upravy = date("d.m.Y", strtotime($riadok["Cp_Datum_upravy"]));
	
		?>
		<tr>
			<td><?php echo $riadok["ID_cenovej_ponuky"];?></td>
			<td><?php echo $riadok["Obj_Nazov_firmy"];?></td>
			<td><?php echo $riadok["Obj_Meno"]." ".$riadok["Obj_Priezvisko"];?></td>
			<td><?php echo $riadok["Obj_Mesto_fakturacna"];?></td>
			<td><?php echo "$Upraveny_Datum_vytvorenia";?></td>
			<td><?php echo "$Upraveny_Datum_upravy";?></td>
			<td><?php echo $riadok["Celkova_cena_s_DPH"];?></td>
			<td style = "text-align: center; ">
			
				<script>
					$(document).ready(function() {
				<?php
				echo "$('#zmazat_riadok".$riadok["ID_cenovej_ponuky"]."').submit(function() {";
				?>
		
				var c = confirm("Ste si istý že chcete zmazať cenovú ponuku číslo"+
				" "+<?php echo '"'.$riadok["ID_cenovej_ponuky"].'"'?>+
				" "+"od zákazníka:"+" "+<?php echo '"'.$riadok["Obj_Nazov_firmy"].' '.$riadok["Obj_Meno"].' '.$riadok["Obj_Priezvisko"].'"'?>+" ?");
		
				return c; 
					});
				});
				</script>
				
				<div class="container d-flex ms-3">
					<form  class="me-4" action="cenova_ponuka.php" method="post">
						<input class="mt-1" type="image" name="zobrazit" Value="Zobraziť" src="images/eye-solid.png" title="Zobraziť" style="width:35px;">
						<input type="hidden" name="ID_cenovej_ponuky" value="<?php echo $riadok["ID_cenovej_ponuky"];?>">
					</form>
					<?php if(ZistiPrava("Edit_cenove_ponuky",$dblink) == 1):  ?>
					<form  class="me-4" action="cenova_ponuka.php" method="post">
						<input class="mt-1" type="image" name="editovat" Value="Editovať" src="images/pen-to-square-solid.png" title="Editovať" style="width:30px;">
						<input type="hidden" name="ID_cenovej_ponuky" value="<?php echo $riadok["ID_cenovej_ponuky"];?>">
					</form>	
					<form class="me-4" id="zmazat_riadok<?php echo $riadok["ID_cenovej_ponuky"];?>" action="index_cenova_ponuka.php" method="post">
						<input class="mt-1" type="image" name="delete" Value="delete" src="images/trash-can-solid.png" title="Zmazať" style="width:25px;">
						<input type="hidden" name="ID_cenovej_ponuky" value="<?php echo $riadok["ID_cenovej_ponuky"];?>">
						<input type="hidden" name="Obj_Meno" value="<?php echo $riadok["Obj_Meno"];?>">
						<input type="hidden" name="Obj_Priezvisko" value="<?php echo $riadok["Obj_Priezvisko"];?>">
						<input type="hidden" name="akcia" value="delete">
					</form>
					<?php endif; ?>	
					<form  class="me-4" action="poslat_cenovu_ponuku.php" method="post">
						<input class="mt-1" type="image" name="poslat" Value="Poslať" src="images/envelope-solid.png" title="Poslať zákazníkovi" style="width:32px;">
						<input type="hidden" name="ID_cenovej_ponuky" value="<?php echo $riadok["ID_cenovej_ponuky"];?>">
					</form>
					
					<form class="me-4" action="export_cenova_ponuka.php" method="post">
						<input class="mt-1" type="image" name="poslat_html" Value="PoslaťHTML" src="images/download-solid.png" title="Stiahnuť vo formáte HTML" style="width:29px;">
						<input type="hidden" name="ID_cenovej_ponuky" value="<?php echo $riadok["ID_cenovej_ponuky"];?>">
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
if(!$skupina AND !$hladaj):
?>
<div id="tabulka-sipky">
	<table id="sipky" border="1" align="center" cellpadding="0" cellspacing="0">
		<tr><td align="left">
		<a href="index_cenova_ponuka.php?dolava=1"><img src="images/left-long-solid.png" title="Doľava" style="width:35px; margin-left:4px;"></a>
		</td>
		<td align="center">Stránka: <?php echo $stranka;?></td>
		<td align="right">
		<a href="index_cenova_ponuka.php?doprava=1"><img src="images/right-long-solid.png" title="Doprava" style="width:35px; margin-right:4px;"></a>
		</td></tr>
	</table>
</div>

<hr>	
	
<?php endif; ?>

</body>
</html>