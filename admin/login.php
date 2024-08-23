<?php


if(isset($_POST['Login_Prihlasovacie_meno']) AND isset($_POST['Login_Prihlasovacie_heslo']))
{
	$Login_Prihlasovacie_meno = $_POST['Login_Prihlasovacie_meno'];
	$Login_Prihlasovacie_heslo = $_POST['Login_Prihlasovacie_heslo'];
	$dblink = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db);

	if (!$dblink) // kontrola ci je pripojenie na db dobre ak nie tak napise chybu
	{ 
		echo "Chyba pripojenia na DB!</br>";
		die(); // ukonci vykonanie php
	}
	mysqli_set_charset($dblink, "utf8mb4");
	$Login_Prihlasovacie_heslo_crypt = sha1($Login_Prihlasovacie_heslo);
	$sql = "SELECT * FROM registrovany_pouzivatel WHERE Prihlasovacie_meno='$Login_Prihlasovacie_meno' AND Prihlasovacie_heslo='$Login_Prihlasovacie_heslo_crypt' AND ID_prava != 3";
    
	$vysledok = mysqli_query($dblink,$sql);
	$row = mysqli_num_rows($vysledok);
	if($row > 0) 
    {
	$riadok = mysqli_fetch_assoc($vysledok);
	
	$_SESSION['Login_ID_pouzivatela'] = $riadok["ID_pouzivatela"];
	$_SESSION['Login_Prihlasovacie_meno'] = $Login_Prihlasovacie_meno;
	$_SESSION['Login_Meno_Priezvisko'] = $riadok["Pouz_Meno"]." ".$riadok["Pouz_Priezvisko"];
	$_SESSION['Login_ID_prava'] = $riadok["ID_prava"];
	$Login_ID_pouzivatela=$riadok["ID_pouzivatela"];
	}       
	mysqli_close($dblink);

}


if (isset($_SESSION['Login_ID_pouzivatela']))
{ 

}
else
{	
	echo "<h2>Prihlásenie</h2>";
	
	if (isset($Login_Prihlasovacie_meno))
	{
		// Pokusil se prihlásit, ale nepodarilo sa mu to 
		echo '<br><div class="hlaska"><div class="text-left">Nesprávne prihlasovacie meno alebo heslo.</div></div><br>';
	}
	else 
	{
		echo "<br>";
	}
	echo '</div>';	

	echo '<form action="index.php" method="POST">';

	echo '<table class="login" border="1" align="center" cellpadding="0" cellspacing="0">';
		echo '<tr><td>Prihlasovacie meno:</td><td style="width: 65%"><input required type="text" name="Login_Prihlasovacie_meno" value="" /></br></td></tr>';
		echo '<tr><td>Heslo:</td><td><input required type="password" name="Login_Prihlasovacie_heslo" value="" id="password"/>';   
		echo '<input type="checkbox" class="ZobrazitHeslo" onclick="ShowPassword()"><span style="vertical-align:middle;"/>Zobraziť heslo</span></td></tr>';
		echo '<tr><td colspan="2">';
		echo '<input style="float:right;margin-right:1rem;" type="submit" value="Prihlásiť">';
		echo '</td></tr>';
	echo '</table>';
	echo '</form>';
	

}
?>