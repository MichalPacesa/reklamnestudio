<?php
session_start();

include "config.php";  // konfiguracia
include "lib.php";	//	funkcie    

$dblink = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db); // pripojenie do databazy

if (!$dblink) { // kontrola ci je pripojenie na databazu dobre ak nie tak napise chybu
	echo "Chyba pripojenia na DB!</br>";
	exit;
}

if(ZistiPrava("Edit_zamestnancov",$dblink) == 0 AND $_POST["back"] != "Späť") 
{ 
	include "head.php";
	include "navbar.php";	 
	echo "<p class='oznam'>Nemáte práva na editáciu a pridávanie zamestnancov.</p>";exit;
}

$hlaska="";
$_SESSION["hlaska"] = "";

// ---------------- Idem insertovat zaznam do tabuluky ------------------
if($_POST["akcia"]=="insert" && $_POST["Zam_Meno"]!="" && $_POST["back"] != "Späť"):  // ak je akcia insert z hidden parametru formulara a vyplnené meno nesmie byt prazdne
	
 	$ID = $_POST["ID_zamestnanca"];
	$Meno = Trim($_POST["Zam_Meno"]);
	$Priezvisko = Trim($_POST["Zam_Priezvisko"]);
	$Datum_narodenia = Trim($_POST["Zam_Datum_narodenia"]);
	$Email = Trim($_POST["Zam_Email"]);
	$Telefon = Trim($_POST["Zam_Telefon"]);
	$Ulica_cislo = Trim($_POST["Zam_Ulica_cislo"]);
	$Mesto = Trim($_POST["Zam_Mesto"]);
	$PSC = Trim($_POST["Zam_PSC"]);
	$Druh_zamestnania = Trim($_POST["Druh_zamestnania"]);
	$Uvazok = Trim($_POST["Uvazok"]);
	$Pracovna_pozicia = Trim($_POST["Pracovna_pozicia"]);
	$Datum_nastupu = Trim($_POST["Datum_nastupu"]);
	$Nazov_firmy = Trim($_POST["Zam_Nazov_firmy"]);
	$ICO = Trim($_POST["Zam_ICO"]);
	$DIC = Trim($_POST["Zam_DIC"]);
	$IC_DPH = Trim($_POST["Zam_IC_DPH"]);
	$Nazov_banky = Trim($_POST["Zam_Nazov_banky"]);
	$Cislo_uctu = Trim($_POST["Zam_Cislo_uctu"]);
	$Poznamka = Trim($_POST["Zam_Poznamka"]);
	
	$Prihlasovacie_meno = Trim($_POST["Prihlasovacie_meno"]);
	$Prihlasovacie_heslo = Trim($_POST["Prihlasovacie_heslo"]);
	$Prihlasovacie_heslo_crypt = sha1($Prihlasovacie_heslo);	
	$ID_prava = Trim($_POST["ID_prava"]);
	
	$PSC = str_replace(' ', '', $PSC);	
	
	$Upraveny_Datum_narodenia = date("y-m-d", strtotime($Datum_narodenia));
	$Upraveny_Datum_nastupu	= date("y-m-d", strtotime($Datum_nastupu));
	
	
	/*kontrola loginu*/
	$sql = "SELECT * FROM registrovany_pouzivatel WHERE Prihlasovacie_meno='$Prihlasovacie_meno'";
	$vysledok = mysqli_query($dblink, $sql);
	$num_row = mysqli_num_rows($vysledok);
	if($num_row > 0)  /// Ak nasiel aspon 1 zaznam
		{
		header('Location: zamestnanec.php?vysledok=chyba');exit;
		}
	// INSERT REGISTROVANEHO POUZIVATELA
	$sql = "INSERT INTO registrovany_pouzivatel(ID_pouzivatela,Pouz_Meno,Pouz_Priezvisko,Prihlasovacie_Meno,Prihlasovacie_Heslo,ID_Prava)									   
												VALUES (NULL,'$Meno','$Priezvisko','$Prihlasovacie_meno','$Prihlasovacie_heslo_crypt','$ID_prava')";								
	
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz select a vysledok načítame do premennej $vysledok
	if (!$vysledok)
	{ 
		$hlaska = "Chyba pri vkladani pouzivatela! </br>";
	}
	else
	{
		$ID_posledneho_zaznamu = mysqli_insert_id($dblink);		
		$hlaska = "<p class='oznam'>Registrovany pouzivatel číslo $ID_posledneho_zaznamu - $Meno $Priezvisko bol pridany do databazy</p>";
	}
	$ID_pouzivatela = mysqli_insert_id($dblink);

		$sql = "INSERT INTO zamestnanec(ID_zamestnanca,Zam_Meno,Zam_Priezvisko,Zam_Datum_narodenia,
										Zam_Email,Zam_Telefon,Zam_Ulica_cislo,Zam_Mesto,Zam_PSC,Druh_zamestnania,Uvazok,Pracovna_pozicia,Datum_nastupu,
										Zam_Cislo_uctu,Zam_Nazov_banky,Zam_Nazov_firmy,Zam_ICO,Zam_DIC,Zam_IC_DPH,Zam_Poznamka,ID_pouzivatela)
										VALUES (NULL,'$Meno','$Priezvisko','$Upraveny_Datum_narodenia','$Email','$Telefon',
										'$Ulica_cislo','$Mesto','$PSC','$Druh_zamestnania','$Uvazok','$Pracovna_pozicia','$Upraveny_Datum_nastupu',
									    '$Cislo_uctu','$Nazov_banky','$Nazov_firmy','$ICO','$DIC','$IC_DPH','$Poznamka','$ID_pouzivatela')";
		

		
								   						   														   
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz select a vysledok načítame do premennej $vysledok
	
	if (!$vysledok)
	{ 
		//echo "Chyba pri vkladani objednavky! </br>";
		$hlaska .= "Chyba pri vkladani zamestnanca! </br>";
		
	}
	else
	{ 
		$ID_posledneho_zaznamu = mysqli_insert_id($dblink);
		$hlaska .= "<p class='oznam'>Zamestnanec číslo $ID_posledneho_zaznamu - $Nazov_firmy $Meno $Priezvisko bol pridaný do databázy.</p>";
	}
	$_SESSION["hlaska"]=$hlaska; 
	
endif;
//-----------------------------------------------------------------------

// ---------------- Idem Upravit zaznam do tabuluky ------------------
if ($_POST["akcia"]=="update" && $_POST["ID_zamestnanca"]!="" && $_POST["back"] != "Späť"): // chcem akciu update z hidden parametru formulara a meno nesmie byt prazdne

 	$ID = $_POST["ID_zamestnanca"];
	$ID_pouzivatela = $_POST["ID_pouzivatela"];
	$Meno = Trim($_POST["Zam_Meno"]);
	$Priezvisko = Trim($_POST["Zam_Priezvisko"]);
	$Datum_narodenia = Trim($_POST["Zam_Datum_narodenia"]);
	$Email = Trim($_POST["Zam_Email"]);
	$Telefon = Trim($_POST["Zam_Telefon"]);
	$Ulica_cislo = Trim($_POST["Zam_Ulica_cislo"]);
	$Mesto = Trim($_POST["Zam_Mesto"]);
	$PSC = Trim($_POST["Zam_PSC"]);
	$Druh_zamestnania = Trim($_POST["Druh_zamestnania"]);
	$Uvazok = Trim($_POST["Uvazok"]);
	$Pracovna_pozicia = Trim($_POST["Pracovna_pozicia"]);
	$Datum_nastupu = Trim($_POST["Datum_nastupu"]);
	$Nazov_firmy = Trim($_POST["Zam_Nazov_firmy"]);
	$ICO = Trim($_POST["Zam_ICO"]);
	$DIC = Trim($_POST["Zam_DIC"]);
	$IC_DPH = Trim($_POST["Zam_IC_DPH"]);
	$Nazov_banky = Trim($_POST["Zam_Nazov_banky"]);
	$Cislo_uctu = Trim($_POST["Zam_Cislo_uctu"]);
	$Poznamka = Trim($_POST["Zam_Poznamka"]);
	
	$PSC = str_replace(' ', '', $PSC);	
	
	$Prihlasovacie_meno = Trim($_POST["Prihlasovacie_meno"]);
	$Prihlasovacie_meno_old = Trim($_POST["Prihlasovacie_meno_old"]);
	$Prihlasovacie_heslo = Trim($_POST["Prihlasovacie_heslo"]);
	$Prihlasovacie_heslo_crypt = sha1($Prihlasovacie_heslo);
	$ID_prava = Trim($_POST["ID_prava"]);//phpinfo();
	
	/*kontrola loginu*/
	if($Prihlasovacie_meno AND $Prihlasovacie_meno!=$Prihlasovacie_meno_old){	
		$sql = "SELECT * FROM registrovany_pouzivatel WHERE Prihlasovacie_meno='$Prihlasovacie_meno'";
		$vysledok = mysqli_query($dblink, $sql);
		$num_row = mysqli_num_rows($vysledok);
		if($num_row > 0)  /// Ak nasiel aspon 1 zaznam
			{
			header('Location: zamestnanec.php?vysledok=chyba');exit;
		}
	}
	
	if($Datum_narodenia == "") 
	{
	$Datum_narodenia = "0000-00-00";
	}
	else $Upraveny_Datum_narodenia = date("y-m-d", strtotime($Datum_narodenia));
	
	if($Datum_nastupu == "") 
	{
		$Datum_nastupu = "0000-00-00";
	}
	else $Upraveny_Datum_nastupu = date("y-m-d", strtotime($Datum_nastupu));
	
	$sql = "UPDATE zamestnanec SET Zam_Meno='$Meno', Zam_Priezvisko='$Priezvisko', Zam_Datum_narodenia='$Upraveny_Datum_narodenia', Zam_Email='$Email', Zam_Telefon='$Telefon', 
	Zam_Ulica_cislo='$Ulica_cislo', Zam_Mesto='$Mesto', Zam_PSC='$PSC', Druh_zamestnania='$Druh_zamestnania', Uvazok='$Uvazok', Pracovna_pozicia='$Pracovna_pozicia', Datum_nastupu='$Upraveny_Datum_nastupu',
	Zam_Nazov_firmy='$Nazov_firmy', Zam_ICO='$ICO', Zam_DIC='$DIC', Zam_IC_DPH='$IC_DPH', Zam_Nazov_banky='$Nazov_banky', Zam_Cislo_uctu='$Cislo_uctu', Zam_Poznamka='$Poznamka' WHERE ID_zamestnanca=$ID";
	
	
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz update a vysledky nacitame do premennej $vysledok
	if (!$vysledok)
	{  
		$hlaska .= "Chyba updatu zamestnanca! </br>";
	}
	else 
	{ 
		$hlaska .= "<p class='oznam'>Zamestnanec číslo $ID - $Meno $Priezvisko bol upravený.</p>";
	}
	
	if($Prihlasovacie_heslo!=""){ // zmenili heslo
		$sql_pouz = "UPDATE registrovany_pouzivatel SET Pouz_Meno='$Meno', Pouz_Priezvisko='$Priezvisko', 
		         Prihlasovacie_meno='$Prihlasovacie_meno', Prihlasovacie_heslo='$Prihlasovacie_heslo_crypt', ID_prava='$ID_prava' WHERE ID_pouzivatela=$ID_pouzivatela";
	}			 
	else {// bez zmeny hesla 
		$sql_pouz = "UPDATE registrovany_pouzivatel SET Pouz_Meno='$Meno', Pouz_Priezvisko='$Priezvisko', 
		      Prihlasovacie_meno='$Prihlasovacie_meno', ID_prava='$ID_prava' WHERE ID_pouzivatela=$ID_pouzivatela";
	}		  
	//echo $sql_pouz;exit;
	
	$vysledok = mysqli_query($dblink, $sql_pouz); // vykonam sql prikaz update a vysledky nacitame do premennej $vysledok
	if (!$vysledok)
	{  
		$hlaska .= "Chyba updatu registrovaného použivateľa! </br>";
	}
	else 
	{ 
		$hlaska .= "<p class='oznam'>Registrovaný používateľ číslo $ID_pouzivatela - $Meno $Priezvisko bol upravený.</p>";
	}
	
	$_SESSION["hlaska"]=$hlaska; echo $_SESSION["hlaska"];
	
endif;
//-----------------------------------------------------------------------	

header('Location: index_zamestnanec.php');
?>
