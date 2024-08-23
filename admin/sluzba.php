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

$ID_sluzby=intval($_POST["ID_sluzby"]);


if($ID_sluzby) // preview alebo update sluzby
{
	$zobrazit=$_POST["zobrazit_x"];
	$sql="SELECT * FROM sluzba s  WHERE s.ID_sluzby = $ID_sluzby";
			
	if($zobrazit) 
	{
		$akcia="preview";
		$nadpis = "Služba č.".$ID_sluzby;
	}	
	else
	{
		if(ZistiPrava("Edit_sluzby",$dblink) == 0) 
		{ 
			include "navbar.php";
			echo "<p class='oznam'>Nemáte práva na editáciu a pridávanie služieb.</p>";exit;
		}
		$akcia = "update"; 
		$nadpis = "Editácia služby č.".$ID_sluzby;
	}
	
	$vysledok = mysqli_query($dblink,$sql); 
	$riadok = mysqli_fetch_assoc($vysledok);
	$Nazov = $riadok["Nazov"];
	$Farby = $riadok["Farby"];
	$Dostupne_formaty = $riadok["Dostupne_formaty"];
	$Zobrazit_rozmery=$Zobrazit_velkost=$Zobrazovat_sluzbu='off';
	if($riadok["Zobrazit_rozmery"]) $Zobrazit_rozmery='on' ; 
	if($riadok["Zobrazit_velkost"]) $Zobrazit_velkost='on' ;
	if($riadok["Zobrazovat_sluzbu"]) $Zobrazovat_sluzbu='on' ;
	
}
else // nova sluzba
{
	if(ZistiPrava("Edit_sluzby",$dblink) == 0) 
	{ 
		include "navbar.php";
		echo "<p class='oznam'>Nemáte práva na editáciu a pridávanie služieb.</p>";exit;
	}
	$akcia = "insert";
	$nadpis = "Nová služba";
	$ID_sluzby = "";
	$Nazov = "";
	$Farby = "";
	$Dostupne_formaty = "";
	$Zobrazit_rozmery = "off";
	$Zobrazit_velkost = "off";
	$Zobrazovat_sluzbu = "on";
}
?>

<h1><?php echo $nadpis; ?></h1>

<p>
<form action="zmena_sluzby.php" method="POST">

<p class="oznam text-grey text-start">Položky označené <span class="red bold">*</span> sú povinné</p>

<table  class="zoznam" border="1" align="center" cellpadding="0" cellspacing="0">
	
	<tr><td width="30%">Názov:<span class="hviezdicka">*</span> </td><td style="width:50%;"> <input type="text" size="57" name="Nazov" value="<?php echo $Nazov;?>" <?php echo disabled($akcia);?> ></br></td></tr>
	<tr><td>Dostupné farby:</td><td> <textarea name="Farby" placeholder="Zadajte farby oddelené čiarkami, napr. biela,čierna,zeleno-biela" cols="60" rows="5" <?php echo disabled($akcia);?> ><?php echo $Farby;?></textarea></br></td></tr>
	<tr><td>Dostupné formáty:</td><td> <textarea  name="Dostupne_formaty" placeholder="Zadajte formáty oddelené čiarkami napr. A4,A3,A2 alebo 200 × 300 mm,250 × 400 mm" cols="60" rows="5" <?php echo disabled($akcia);?>><?php echo $Dostupne_formaty;?></textarea> </br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	
	<tr><td style="vertical-align:middle;"><br>Zobrazovať vlastné rozmery (šírka, výška):<br><br></td><td><input type="checkbox" class="Checkbox" name="Zobrazit_rozmery"  <?php echo checked($Zobrazit_rozmery,'on');  ?> <?php echo disabled($akcia);?> /></td></tr>
	<tr><td><br>Zobrazovať veľkosť oblečenia (XS, S, M, L, XL, XXL):<br><br></td><td><input type="checkbox" class="Checkbox" name="Zobrazit_velkost" <?php echo checked($Zobrazit_velkost,'on'); ?> <?php echo disabled($akcia);?> /></td></tr>
	<tr><td><br>Zobrazovať túto službu:<br><br></td><td><input type="checkbox" class="Checkbox" name="Zobrazovat_sluzbu" size="1"  <?php echo checked($Zobrazovat_sluzbu,'on') ?> <?php echo disabled($akcia);?> /></td></tr>
	<tr><td colspan="2"><br></td></tr>
	
	
	<tr><td colspan="2">
	<?php if($akcia !='preview'):?>
		<input type="submit" value="Uložiť">
	<?php endif;	?>
	<input type="submit" name="back" class = "button2" type="button" value="Späť" onclick="/*window.history.go(-1);*/"></form>
	</td></tr>
</table>

<input type="hidden" name="akcia" value="<?php echo $akcia;?>">
<input type="hidden" name="ID_sluzby" value="<?php echo $ID_sluzby;?>">

</form>
</p>
<?php
mysqli_close($dblink); // odpojit sa z DB
?>
</body>
</html>