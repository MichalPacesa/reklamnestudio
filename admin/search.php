<?php

include "config.php";
include "lib.php";
$dblink = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db);

$hladaj=$_GET["q"];

$sql = "SELECT * from sluzba WHERE id_sluzby='$hladaj'";
//echo $sql;

$vysledok_hladaj = mysqli_query($dblink, $sql); 
if(!$vysledok_hladaj) echo 'Nepodaril sa vyber zo sluzieb.';
$riadok_hladaj=mysqli_fetch_assoc($vysledok_hladaj);

?>
<html>

<form  method="get">
	<input type="hidden" id="Farby"            name="Farby"            value="<?php echo $riadok_hladaj['Farby'];?>" />
	<input type="hidden" id="Dostupne_formaty" name="Dostupne_formaty" value="<?php echo $riadok_hladaj['Dostupne_formaty'];?>" />
	<input type="hidden" id="Zobrazit_rozmery" name="Zobrazit_rozmery" value="<?php echo $riadok_hladaj['Zobrazit_rozmery'];?>" />
	<input type="hidden" id="Zobrazit_velkost" name="Zobrazit_velkost" value="<?php echo $riadok_hladaj['Zobrazit_velkost'];?>" />
</form>

		
<?php
		mysqli_close($dblink); //odpojim sa od databazy
?>

</html>