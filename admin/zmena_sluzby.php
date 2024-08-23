<?php
session_start();

include "config.php";  // konfiguracia
include "lib.php";	//	funkcie    

$dblink = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db); // pripojenie do databazy

if (!$dblink) { // kontrola ci je pripojenie na databazu dobre ak nie tak napise chybu
	echo "Chyba pripojenia na DB!</br>";
	exit;
}

if(ZistiPrava("Edit_sluzby",$dblink) == 0 AND $_POST["back"] != "Späť") 
{ 
	include "head.php";
	include "navbar.php";	 
	echo "<p class='oznam'>Nemáte práva na editáciu a pridávanie služieb.</p>";exit;
}

$hlaska="";
$_SESSION["hlaska"] = "";

// ---------------- Idem insertovat zaznam do tabuluky ------------------

if ($_POST["akcia"]=="insert" && $_POST["Nazov"]!="" && $_POST["back"] != "Späť"):  // ak je akcia insert z hidden parametru formulara a vyplnené meno nesmie byt prazdne
	
 	$ID = $_POST["ID_sluzby"];
	$Nazov = Trim($_POST["Nazov"]);
	$Farby = Trim($_POST["Farby"]);
	$Dostupne_formaty = Trim($_POST["Dostupne_formaty"]);
	$Zobrazit_rozmery=0;
	IF($_POST["Zobrazit_rozmery"]=='on') $Zobrazit_rozmery=1;
	$Zobrazit_velkost=0;
	IF($_POST["Zobrazit_velkost"]=='on') $Zobrazit_velkost=1;
	$Zobrazovat_sluzbu=0;
	IF($_POST["Zobrazovat_sluzbu"]=='on') $Zobrazovat_sluzbu=1;
	
	
			
	// INSERT sluzby
	$sql = "INSERT INTO sluzba(ID_sluzby,Nazov,Farby,Dostupne_formaty,Zobrazit_rozmery,Zobrazit_velkost,Zobrazovat_sluzbu) VALUES ('','$Nazov','$Farby','$Dostupne_formaty','$Zobrazit_rozmery','$Zobrazit_velkost','$Zobrazovat_sluzbu')";	
	//echo $sql;exit;								
		$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz select a vysledok načítame do premennej $vysledok
		if (!$vysledok)
		{ 
			$hlaska = "Chyba pri vkladani sluzby! </br>";
		}
		else
		{			
			$hlaska = "<p class='oznam'>Služba $Nazov bola pridaná do databázy</p>";
		}
	
	
		
		
	$_SESSION["hlaska"]=$hlaska; //echo $_SESSION["hlaska"];
	
endif;

//-----------------------------------------------------------------------

// ---------------- Idem Upravit zaznam do tabuluky ------------------
if ($_POST["akcia"]=="update" && $_POST["ID_sluzby"]!="" && $_POST["back"] != "Späť"): // chcem akciu update z hidden parametru formulara a meno nesmie byt prazdne

 	$ID = $_POST["ID_sluzby"];
	$Nazov = Trim($_POST["Nazov"]);
	$Farby = Trim($_POST["Farby"]);
	$Dostupne_formaty = Trim($_POST["Dostupne_formaty"]);
	$Zobrazit_rozmery=0;
	IF($_POST["Zobrazit_rozmery"]=='on') $Zobrazit_rozmery=1;
	$Zobrazit_velkost=0;
	IF($_POST["Zobrazit_velkost"]=='on') $Zobrazit_velkost=1;
	$Zobrazovat_sluzbu=0;
	IF($_POST["Zobrazovat_sluzbu"]=='on') $Zobrazovat_sluzbu=1;
	
	
	
	$sql = "UPDATE sluzba SET Nazov = '$Nazov', Farby = '$Farby',Dostupne_formaty = '$Dostupne_formaty', Zobrazit_rozmery = '$Zobrazit_rozmery',Zobrazit_velkost='$Zobrazit_velkost',Zobrazovat_sluzbu = '$Zobrazovat_sluzbu'
	WHERE ID_sluzby=$ID";
	//echo $sql;exit;
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz update a vysledky nacitame do premennej $vysledok
	if (!$vysledok)
	{  
		$hlaska .= "Chyba updatu! </br>";
	}
	else 
	{ 
		$hlaska .= "<p class='oznam'>Služba $Nazov bola upravená.</p>";
	}
	
	$_SESSION["hlaska"]=$hlaska; echo $_SESSION["hlaska"];
	
endif;
//-----------------------------------------------------------------------	

header('Location: index_sluzba.php');
?>
