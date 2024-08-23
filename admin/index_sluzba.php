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

if(ZistiPrava("Zobraz_sluzby",$dblink) == 0){ 
echo "<p class='oznam'>Nemáte práva na zobrazenie služieb.</p>";
exit;
}   

$server=$_SERVER['HTTP_HOST'];
if($_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name."/admin/sluzba.php")  
{
	$_SESSION["hlaska"] = "";
}

if($_SERVER['HTTP_REFERER'] !="http://".$server.'/'.$dir_name.'/admin/sluzba.php' AND $_SERVER['HTTP_REFERER'] !="http://".$server.'/'.$dir_name.'/'.'admin/index_sluzba.php'
AND $_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name.'/'."admin/index_sluzba.php?doprava=1" AND  $_SERVER['HTTP_REFERER'] != "http://".$server.'/'.$dir_name.'/'."admin/index_sluzba.php?dolava=1")
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


if (!$dblink) { // kontrola ci je pripojenie na databazu dobre ak nie tak napise chybu
	echo "Chyba pripojenia na DB!</br>";
	exit;
}


$sql_pocet="SELECT count(1) as pocet_riadkov FROM sluzba";
$pocet_riadkov = zisti_pocet_riadkov($dblink,$sql_pocet);	
// limit na stranke je nastaveny v config.php
if($skupina == 0)
{
	$podmienka="";
	
}	
else
{
	$podmienka=" AND s.Zobrazovat_sluzbu=".$skupina;
	$offset = 0;
	$stranka=1;
	$_SESSION["offset"]=0;
	$_SESSION["stranka"]=1;
	$sql_pocet="SELECT count(1) as pocet_riadkov FROM sluzba s WHERE 1 $podmienka";
	$pocet_riadkov = zisti_pocet_riadkov($dblink,$sql_pocet);
	$limit=$pocet_riadkov; // limit na stranke budu vsetky zaznamy podla vyberu
}


if($hladaj)	
	{
$hladaj2=str_replace( ' ','',$hladaj);  // vymaze vsetky medzery
	$podmienka.=" AND (s.ID_sluzby='$hladaj' OR s.Nazov LIKE '%$hladaj%')";  // vyhlada nazov sluzby
	$sql_pocet="SELECT count(1) as pocet_riadkov FROM sluzba s WHERE 1 $podmienka";
	///echo $sql_pocet;
	$pocet_riadkov = zisti_pocet_riadkov($dblink,$sql_pocet);
	$limit=$pocet_riadkov; // limit na stranke budu vsetky zaznamy podla vyberu
	}
 	
?>

<h1>Služby</h1>
<table class = "zoznam" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<?php if(ZistiPrava("Edit_sluzby",$dblink) == 1):  ?>
		<td style = "text-align:center !important; width:15%;"><a href="sluzba.php">Nová služba</a></td>
		<?php endif; ?>
		<td class = "text-center" style = "text-align:center !important; width:25%;">
		<p>
			<form action="index_sluzba.php" method="post" >
				Hľadaj:
					<input type="text" id="hladaj" name="hladaj" class="select_height" autofocus value="<?php echo $_POST['hladaj']?>">
					<input type="image" src="images/magnifying-glass-solid.png" title="Vyhľadaj" style="width:17px;">
					<input type="image" src="images/xmark-solid.png" title="Vymazať" style="width:15px;" onclick="forms[0].hladaj.value='';">
					<input type="hidden" name="skupina" value="<?php echo $skupina;?>">
			</form>
		</p>	
		</td>
		
		<td style = "text-align:center !important; width:30%;">
		<p><form action="index_sluzba.php" method="post">
		Služby:
		<select name="skupina" class="select_height" onChange="forms[1].submit()">
			<option value="0" <?php echo selected($skupina,'0') ?> > Všetky </option>
			<option value="1" <?php echo selected($skupina,'1') ?> > Zverejnené </option>
		</select>
		</form></p>
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
	if ($_POST["akcia"]=="delete" && $_POST["ID_sluzby"]!="" && $_POST["back"] != "Späť"): // chcem akciu delete z hidden parametru formulara a id nesmie byt prazdne
 	$id=$_POST["ID_sluzby"];
	$nazov=$_POST["Nazov"];
		
	$sql = "DELETE FROM sluzba  WHERE ID_sluzby=$id"; 
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz delete a vysledky nacitame do premennej $vysledok
	if (!$vysledok)
	{  
		echo "Chyba pri vymazávaní záznamu! </br>";
	}
	else 
	{ 
		echo "<p class='oznam'>Služba $nazov bola odstránená z databázy.</p>";
	}

endif;
//-----------------------------------------------------------------------	


if($_SESSION["hlaska"])	
{ 
	echo $_SESSION["hlaska"];
}

// limit ma nastaveny v config.php
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
	
if($pocet_riadkov==0){
     echo "<p class=\"cervene  \">⠀⠀Nenašli sa žiadne záznamy.</p>";
}
// osetrit hranice strankovania
if ($offset<0): // ak $offset je mensi ako nula - nemozes do minusu resetujem to na nulu
	$offset=0;
	$stranka=1;
	$_SESSION["offset"]=$offset;
	$_SESSION["stranka"]=$stranka;
	echo "<p class=\"cervene\">Viac záznamov smerom doľava neexistuje.</p></br>";
endif;

if ($offset > $pocet_riadkov-1){ // ak $offset je vacsi ako pocet zaznamov 
		$offset=$offset-$limit;
		$stranka=$stranka-1;
		$_SESSION["offset"]=$offset;
		$_SESSION["stranka"]=$stranka;
		if(!$hladaj and !$skupina)
		echo "<p class=\"cervene \">Viac záznamov smerom doprava neexistuje.</p>";
	}

	/* hlavny select zoznamu */
	$sql="SELECT * FROM sluzba s WHERE 1 $podmienka ORDER BY ID_sluzby DESC LIMIT $limit OFFSET $offset";
	
?>	

<br>

<div id="tabulka">
<table class="zoznam" border="1" align="center" cellpadding="0" cellspacing="0">
<tr>
	<th width="10%">ID služby</th>
	<th width="60%">Názov sluzby</th>
	<th width="25%">Zobrazovať službu</th>
	<th width="5%" colspan="2"></th>
</tr>
	<?php
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz - select a vysledky nacitame do premennej $vysledok
	
    // Vo vysledkoch moze byt viac riadkov, takze ich musime prezriet v cykle, riadok po riadku a vypisat ich.	
	while ($riadok = mysqli_fetch_assoc($vysledok)) { // mysqli_fetch_assoc - vrati hodnty zo selectu a priradi im mena vybranych stlpcov
	
//echo 'zobr'.$riadok["Zobrazovat_sluzbu"];
	switch ($riadok["Zobrazovat_sluzbu"])
	{
	case 0:
		$Zobrazovat_sluzbu_text = "Nie";
		break;
	case 1:
        $Zobrazovat_sluzbu_text = "Áno";
        break;
	default:	
		$Zobrazovat_sluzbu_text = "Nie";
		break;

	}
		?>
		<tr>
			<td><?php echo $riadok["ID_sluzby"];?></td>
			<td><?php echo $riadok["Nazov"];?></td>
			<td><?php echo "$Zobrazovat_sluzbu_text";?></td>
			<td style = "text-align: center; ">
				<script>
					$(document).ready(function() {
					/*console.log( "Dokument je nahraty a ready!" );*/
				<?php
				echo "$('#zmazat_riadok".$riadok["ID_sluzby"]."').submit(function() {";
				?>
		
				var c = confirm("Ste si istý že chcete zmazať sluzbu ID"+
				" "+<?php echo '"'.$riadok["ID_sluzby"].'"'?>+
				" "+"s názvom:"+" "+<?php echo '"'.$riadok["Nazov"].'"'?>+" ?");
		
				return c; 
		
					});
				});
				</script>

				<div class="container d-flex ms-3">
					<form  class="me-4" action="sluzba.php" method="post">
						<input class="mt-1" type="image" name="zobrazit" Value="Zobraziť" src="images/eye-solid.png" title="Zobraziť" style="width:35px;">
						<input type="hidden" name="ID_sluzby" value="<?php echo $riadok["ID_sluzby"];?>">
					</form>
					<?php if(ZistiPrava("Edit_sluzby",$dblink) == 1):  ?>					
					<form  class="me-4" action="sluzba.php" method="post">
						<input class="mt-1" type="image" name="editovat" Value="Editovať" src="images/pen-to-square-solid.png" title="Editovať" style="width:30px;">
						<input type="hidden" name="ID_sluzby" value="<?php echo $riadok["ID_sluzby"];?>">
					</form>	
					<form class="me-4" id="zmazat_riadok<?php echo $riadok["ID_sluzby"];?>" action="index_sluzba.php" method="post">
						<input class="mt-1" type="image" name="delete" Value="delete" src="images/trash-can-solid.png" title="Zmazať" style="width:25px;">
						<input type="hidden" name="ID_sluzby" value="<?php echo $riadok["ID_sluzby"];?>">
						<input type="hidden" name="Nazov" value="<?php echo $riadok["Nazov"];?>">
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
if(!$skupina AND !$hladaj ):
?>
<div>
	<table id="sipky" border="1" align="center" cellpadding="0" cellspacing="0">
		<tr><td align="left">
		<a href="index_sluzba.php?dolava=1"><img src="images/left-long-solid.png" title="Doľava" style="width:35px; margin-left:4px;"></a>
		</td>
		<td align="center">Stránka: <?php echo $stranka;?></td>
		<td align="right">
		<a href="index_sluzba.php?doprava=1"><img src="images/right-long-solid.png" title="Doprava" style="width:35px; margin-right:4px;"></a>
		</td></tr>
	</table>
</div>

<hr>	
	
<?php endif; ?>

</body>
</html>