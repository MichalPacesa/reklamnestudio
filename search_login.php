<?php
// program na kontrolu existencie loginu. Vysledok OK/0/ERR vlozi do inputu s nazvom Result
include "config.php";
include "lib.php";
$dblink = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db);
$hladaj=$_GET["q"];
$sql = "SELECT * FROM registrovany_pouzivatel WHERE Prihlasovacie_meno='$hladaj'";
//echo $sql;
$Result="ERR";
$vysledok_hladaj = mysqli_query($dblink, $sql); 
if(!$vysledok_hladaj) echo 'Nepodarila sa kontrola užívateľského mena.';
else
	$num_row = mysqli_num_rows($vysledok_hladaj);
	if($num_row > 0)  /// nasiel aspon 1 zaznam
		$Result=0;
	else $Result="OK";		
?>
<html>
<form  method="get">
	<input type="hidden" id="Result" name="Result" value="<?php echo $Result;?>" />
</form>
<?php
		mysqli_close($dblink); //odpojim sa od databazy


?>
</html>