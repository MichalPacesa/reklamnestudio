<?php
session_start();

include "config.php"; 
include "lib.php";

$dblink = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db);
if (!$dblink) // kontrola ci je pripojenie na db dobre ak nie tak napise chybu
{ 
	echo "Chyba pripojenia na DB!</br>";
	exit; 
}

if(isset($_POST["Login_Prihlasovacie_meno"]) AND isset($_POST["Login_Prihlasovacie_heslo"]))
{
	$Login_Prihlasovacie_meno = $_POST['Login_Prihlasovacie_meno'];
	$Login_Prihlasovacie_heslo = $_POST['Login_Prihlasovacie_heslo'];
	
	$Login_Prihlasovacie_heslo_crypt = sha1($Login_Prihlasovacie_heslo);
	$sql = "SELECT * FROM registrovany_pouzivatel WHERE Prihlasovacie_meno='$Login_Prihlasovacie_meno' AND Prihlasovacie_heslo='$Login_Prihlasovacie_heslo_crypt' AND ID_prava = 3";
     
	$vysledok = mysqli_query($dblink,$sql);
	$row = mysqli_num_rows($vysledok);
	if($row > 0) 
    {
	$riadok = mysqli_fetch_assoc($vysledok);
	
	$_SESSION['Login_ID_pouzivatela_web'] = $riadok["ID_pouzivatela"];
	$_SESSION['Login_Prihlasovacie_meno_web'] = $Login_Prihlasovacie_meno;
	$_SESSION['Login_Meno_Priezvisko_web'] = $riadok["Pouz_Meno"]." ".$riadok["Pouz_Priezvisko"];
	$Login_ID_pouzivatela=$riadok["ID_pouzivatela"];
	header ("Location: index.php");
	}
	else
	{
		header ("Location: login.php?udaje=nespravne");
		
	}	
}


?>




