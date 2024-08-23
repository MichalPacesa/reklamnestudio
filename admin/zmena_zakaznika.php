<?php
session_start();

include "config.php";  // konfiguracia
include "lib.php";	//	funkcie    

$dblink = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db); // pripojenie do databazy

if (!$dblink) { // kontrola ci je pripojenie na databazu dobre ak nie tak napise chybu
	echo "Chyba pripojenia na DB!</br>";
	exit;
}

if(ZistiPrava("Edit_zakaznikov",$dblink) == 0 AND $_POST["back"] != "Späť") 
{ 
	include "head.php";
	include "navbar.php";	 
	echo "<p class='oznam'>Nemáte práva na editáciu a pridávanie zákazníka.</p>";exit;
}

$hlaska="";
$_SESSION["hlaska"] = "";

// ---------------- Idem insertovat zaznam do tabuluky ------------------

if ($_POST["akcia"]=="insert" && $_POST["Zak_Meno"]!="" && $_POST["back"] != "Späť"):  // ak je akcia insert z hidden parametru formulara a vyplnené meno nesmie byt prazdne
	
 	$ID = $_POST["ID_zakaznika"];
	$Nazov_firmy = Trim($_POST["Zak_Nazov_firmy"]);
	$Meno = Trim($_POST["Zak_Meno"]);
	$Priezvisko = Trim($_POST["Zak_Priezvisko"]);
	$Email = Trim($_POST["Zak_Email"]);
	$Telefon = Trim($_POST["Zak_Telefon"]);
	$Ulica_cislo_fakturacna = Trim($_POST["Zak_Ulica_cislo_fakturacna"]);
	$Mesto_fakturacna = Trim($_POST["Zak_Mesto_fakturacna"]);
	$PSC_fakturacna = Trim($_POST["Zak_PSC_fakturacna"]);
	$Ulica_cislo_dodacia = Trim($_POST["Zak_Ulica_cislo_dodacia"]);
	$Mesto_dodacia = Trim($_POST["Zak_Mesto_dodacia"]);
	$PSC_dodacia = Trim($_POST["Zak_PSC_dodacia"]);
	$ICO = Trim($_POST["Zak_ICO"]);
	$DIC = Trim($_POST["Zak_DIC"]);
	$IC_DPH = Trim($_POST["Zak_IC_DPH"]);
	$Nazov_banky = Trim($_POST["Zak_Nazov_banky"]);
	$Cislo_uctu = Trim($_POST["Zak_Cislo_uctu"]);
	$Poznamka = Trim($_POST["Zak_Poznamka"]);
	
	$PSC_fakturacna = str_replace(' ', '', $PSC_fakturacna);	
	$PSC_dodacia = str_replace(' ', '', $PSC_dodacia);
	
	$Prihlasovacie_meno = Trim($_POST["Prihlasovacie_meno"]);
	$Prihlasovacie_heslo = Trim($_POST["Prihlasovacie_heslo"]);
	$Prihlasovacie_heslo_crypt = sha1($Prihlasovacie_heslo);
	
	// INSERT REGISTROVANEHO POUZIVATELA
	if($Prihlasovacie_meno AND $Prihlasovacie_heslo)
	{
		/*kontrola loginu*/
		$sql = "SELECT * FROM registrovany_pouzivatel WHERE Prihlasovacie_meno='$Prihlasovacie_meno'";
		//echo $sql;exit;
		$vysledok = mysqli_query($dblink, $sql);
		$num_row = mysqli_num_rows($vysledok);
		if($num_row > 0)  /// Ak nasiel aspon 1 zaznam
			{
			header('Location: zakaznik.php?vysledok=chyba');exit;
			}
		
			
		$sql = "INSERT INTO registrovany_pouzivatel(ID_pouzivatela,Pouz_Meno,Pouz_Priezvisko,Prihlasovacie_Meno,Prihlasovacie_Heslo,ID_Prava)									   
													VALUES (NULL,'$Meno','$Priezvisko','$Prihlasovacie_meno','$Prihlasovacie_heslo_crypt',3)";									
	
		$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz select a vysledok načítame do premennej $vysledok
		if (!$vysledok)
			{ 
			$hlaska = "<p class='cervene'>Chyba pri vkladani pouzivatela!</p>";
			}
		else
			{
			$ID_posledneho_zaznamu = mysqli_insert_id($dblink);		
			$hlaska = "<p class='oznam'>Registrovany pouzivatel číslo $ID_posledneho_zaznamu - $Meno $Priezvisko bol pridany do databazy</p>";
			}
		$ID_pouzivatela = mysqli_insert_id($dblink);
	}
		
	if(!$ID_pouzivatela)
	{
		$sql = "INSERT INTO zakaznik(ID_zakaznika,Zak_Nazov_firmy, Zak_Meno,Zak_Priezvisko,Zak_Email,Zak_Telefon,Zak_Ulica_cislo_fakturacna,Zak_Mesto_fakturacna,Zak_PSC_fakturacna,
								   Zak_Ulica_cislo_dodacia,Zak_Mesto_dodacia,Zak_PSC_dodacia,Zak_ICO,Zak_DIC,Zak_IC_DPH,Zak_Nazov_banky,Zak_Cislo_uctu,Zak_Poznamka) 
								   VALUES (NULL,'$Nazov_firmy','$Meno','$Priezvisko','$Email','$Telefon',
								   '$Ulica_cislo_fakturacna','$Mesto_fakturacna','$PSC_fakturacna',
								   '$Ulica_cislo_dodacia','$Mesto_dodacia','$PSC_dodacia','$ICO','$DIC','$IC_DPH','$Nazov_banky','$Cislo_uctu','$Poznamka')";
	}
	else
	{
		$sql = "INSERT INTO zakaznik(ID_zakaznika,Zak_Nazov_firmy, Zak_Meno,Zak_Priezvisko,Zak_Email,Zak_Telefon,Zak_Ulica_cislo_fakturacna,Zak_Mesto_fakturacna,Zak_PSC_fakturacna,
								   Zak_Ulica_cislo_dodacia,Zak_Mesto_dodacia,Zak_PSC_dodacia,Zak_ICO,Zak_DIC,Zak_IC_DPH,Zak_Nazov_banky,Zak_Cislo_uctu,Zak_Poznamka,ID_pouzivatela) 
								   VALUES (NULL,'$Nazov_firmy','$Meno','$Priezvisko','$Email','$Telefon',
								   '$Ulica_cislo_fakturacna','$Mesto_fakturacna','$PSC_fakturacna',
								   '$Ulica_cislo_dodacia','$Mesto_dodacia','$PSC_dodacia','$ICO','$DIC','$IC_DPH','$Nazov_banky','$Cislo_uctu','$Poznamka','$ID_pouzivatela')";
	}
							   						   														   
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz select a vysledok načítame do premennej $vysledok
	
	if (!$vysledok)
	{ 
		//echo "Chyba pri vkladani objednavky! </br>";
		$hlaska .= "<p class='cervene'>Chyba pri vkladani zakaznika!</p>";
		
	}
	else
	{ 
		$ID_posledneho_zaznamu = mysqli_insert_id($dblink);
		$hlaska .= "<p class='oznam'>Zákazník číslo $ID_posledneho_zaznamu - $Nazov_firmy $Meno $Priezvisko bol pridaný do databázy.</p>";
	}
	
	$_SESSION["hlaska"]=$hlaska; //echo $_SESSION["hlaska"];
	
endif;

//-----------------------------------------------------------------------

// ---------------- Idem Upravit zaznam do tabuluky ------------------
if ($_POST["akcia"]=="update" && $_POST["ID_zakaznika"]!="" && $_POST["back"] != "Späť"): // chcem akciu update z hidden parametru formulara a meno nesmie byt prazdne

 	$ID = $_POST["ID_zakaznika"];
	$ID_pouzivatela = $_POST["ID_pouzivatela"];
	$Nazov_firmy = Trim($_POST["Zak_Nazov_firmy"]);
	$Meno = Trim($_POST["Zak_Meno"]);
	$Priezvisko = Trim($_POST["Zak_Priezvisko"]);
	$Email = Trim($_POST["Zak_Email"]);
	$Telefon = Trim($_POST["Zak_Telefon"]);
	$Ulica_cislo_fakturacna = Trim($_POST["Zak_Ulica_cislo_fakturacna"]);
	$Mesto_fakturacna = Trim($_POST["Zak_Mesto_fakturacna"]);
	$PSC_fakturacna = Trim($_POST["Zak_PSC_fakturacna"]);
	$Ulica_cislo_dodacia = Trim($_POST["Zak_Ulica_cislo_dodacia"]);
	$Mesto_dodacia = Trim($_POST["Zak_Mesto_dodacia"]);
	$PSC_dodacia = Trim($_POST["Zak_PSC_dodacia"]);
	$ICO = Trim($_POST["Zak_ICO"]);
	$DIC = Trim($_POST["Zak_DIC"]);
	$IC_DPH = Trim($_POST["Zak_IC_DPH"]);
	$Nazov_banky = Trim($_POST["Zak_Nazov_banky"]);
	$Cislo_uctu = Trim($_POST["Zak_Cislo_uctu"]);
	$Poznamka = Trim($_POST["Zak_Poznamka"]);
	
	$Prihlasovacie_meno = Trim($_POST["Prihlasovacie_meno"]);
	$Prihlasovacie_meno_old = Trim($_POST["Prihlasovacie_meno_old"]);
	$Prihlasovacie_heslo = Trim($_POST["Prihlasovacie_heslo"]);
	$Prihlasovacie_heslo_crypt = sha1($Prihlasovacie_heslo);
	
	$PSC_fakturacna = str_replace(' ', '', $PSC_fakturacna);	
	$PSC_dodacia = str_replace(' ', '', $PSC_dodacia);
		
	/*kontrola loginu*/
	if($Prihlasovacie_meno AND $Prihlasovacie_meno!=$Prihlasovacie_meno_old){	
		$sql = "SELECT * FROM registrovany_pouzivatel WHERE Prihlasovacie_meno='$Prihlasovacie_meno'";
		$vysledok = mysqli_query($dblink, $sql);
		$num_row = mysqli_num_rows($vysledok);
		if($num_row > 0)  /// Ak nasiel aspon 1 zaznam
			{
			header('Location: zakaznik.php?vysledok=chyba');exit;
		}
	}
	
	if(!$ID_pouzivatela){
		if($Prihlasovacie_meno AND $Prihlasovacie_heslo){	
			$sql_pouzivatel = "INSERT INTO registrovany_pouzivatel(ID_pouzivatela,Pouz_Meno,Pouz_Priezvisko,Prihlasovacie_Meno,Prihlasovacie_Heslo,ID_Prava)									   
							   VALUES (NULL,'$Meno','$Priezvisko','$Prihlasovacie_meno','$Prihlasovacie_heslo_crypt',3)";
				
			$vysledok = mysqli_query($dblink, $sql_pouzivatel);
			if (!$vysledok)
			{  
				$hlaska .= "<p class='cervene'>Chyba pri vkladani pouzivatela!</p>";
			}
			else 
			{ 
				$ID_pouzivatela = mysqli_insert_id($dblink);
				$hlaska .= "<p class='oznam'>Registrovaný používateľ číslo $ID_posledneho_zaznamu - $Meno $Priezvisko pridaný do databázy.</p>";
			}
		}
		else
		{
		
		$ID_pouzivatela = "";
		
		}
		
	}
	else
	{	
		if($Prihlasovacie_meno AND $Prihlasovacie_heslo)
		{
			// UPDATE registrovany_pouzivatel
			$sql_pouzivatel = "UPDATE registrovany_pouzivatel SET Pouz_Meno='$Meno', Pouz_Priezvisko='$Priezvisko', 
						       Prihlasovacie_meno='$Prihlasovacie_meno', Prihlasovacie_heslo='$Prihlasovacie_heslo_crypt', ID_prava=3 WHERE ID_pouzivatela=$ID_pouzivatela";
	
			$vysledok = mysqli_query($dblink, $sql_pouzivatel); // vykonam sql prikaz update a vysledky nacitame do premennej $vysledok
			if (!$vysledok)
			{  
				$hlaska .= "Chyba updatu registrovaného použivateľa!";
			}
			else 
			{ 
				$hlaska .= "<p class='oznam'>Registrovaný používateľ číslo $ID_pouzivatela - $Meno $Priezvisko bol upravený.</p>";
			}
			// /UPDATE registrovany_pouzivatel
		}
		elseif($Prihlasovacie_meno AND !$Prihlasovacie_heslo){ // nechcu zmenit heslo
			// UPDATE registrovany_pouzivatel
			$sql_pouzivatel = "UPDATE registrovany_pouzivatel SET Pouz_Meno='$Meno', Pouz_Priezvisko='$Priezvisko', 
						       Prihlasovacie_meno='$Prihlasovacie_meno', ID_prava=3 WHERE ID_pouzivatela=$ID_pouzivatela";
	
			$vysledok = mysqli_query($dblink, $sql_pouzivatel); // vykonam sql prikaz update a vysledky nacitame do premennej $vysledok
			if (!$vysledok)
			{  
				$hlaska .= "Chyba updatu registrovaného použivateľa!";
			}
			else 
			{ 
				$hlaska .= "<p class='oznam'>Registrovaný používateľ číslo $ID_pouzivatela - $Meno $Priezvisko bol upravený.</p>";
			}
			// /UPDATE registrovany_pouzivatel
		}	
		else // vymazali prihlasovacie meno
		{
			$sql_del = "DELETE FROM registrovany_pouzivatel WHERE ID_pouzivatela=$ID_pouzivatela"; // insert dat
			$vysledok = mysqli_query($dblink, $sql_del); // vykonam sql prikaz delete a vysledky nacitame do premennej $vysledok
			if (!$vysledok)
			{  
			$hlaska .= "<p class='cervene'>Chyba pri vymazávaní záznamu!</p>";
			}
			else 
			{ 
			$hlaska .= "<p class='oznam'>Používateľovi číslo $ID_pouzivatela - $Meno $Priezvisko boli odstránené prihlasovacie údaje.</p>";
			}
			
			$ID_pouzivatela = "";
			
		}	
	
	}
	
	// UPDATE zakaznik
		if($ID_pouzivatela == "")
		{	
		$sql = "UPDATE zakaznik SET Zak_Nazov_firmy = '$Nazov_firmy', Zak_Meno='$Meno', Zak_Priezvisko='$Priezvisko', Zak_Email='$Email', Zak_Telefon='$Telefon', 
		Zak_Ulica_cislo_fakturacna='$Ulica_cislo_fakturacna', Zak_Mesto_fakturacna='$Mesto_fakturacna', Zak_PSC_fakturacna='$PSC_fakturacna',
		Zak_Ulica_cislo_dodacia='$Ulica_cislo_dodacia', Zak_Mesto_dodacia='$Mesto_dodacia', Zak_PSC_dodacia='$PSC_dodacia',
		Zak_ICO='$ICO', Zak_DIC='$DIC', Zak_IC_DPH='$IC_DPH', Zak_Nazov_banky='$Nazov_banky', Zak_Cislo_uctu='$Cislo_uctu', Zak_Poznamka='$Poznamka' WHERE ID_zakaznika=$ID";
		}
		else
		{
		$sql = "UPDATE zakaznik SET Zak_Nazov_firmy = '$Nazov_firmy', Zak_Meno='$Meno', Zak_Priezvisko='$Priezvisko', Zak_Email='$Email', Zak_Telefon='$Telefon', 
		Zak_Ulica_cislo_fakturacna='$Ulica_cislo_fakturacna', Zak_Mesto_fakturacna='$Mesto_fakturacna', Zak_PSC_fakturacna='$PSC_fakturacna',
		Zak_Ulica_cislo_dodacia='$Ulica_cislo_dodacia', Zak_Mesto_dodacia='$Mesto_dodacia', Zak_PSC_dodacia='$PSC_dodacia',
		Zak_ICO='$ICO', Zak_DIC='$DIC', Zak_IC_DPH='$IC_DPH', Zak_Nazov_banky='$Nazov_banky', Zak_Cislo_uctu='$Cislo_uctu', Zak_Poznamka='$Poznamka', ID_pouzivatela='$ID_pouzivatela' WHERE ID_zakaznika=$ID";
		}
		$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz update a vysledky nacitame do premennej $vysledok
		if (!$vysledok)
		{  
			$hlaska .= "<p class='cervene'>Chyba updatu zakaznika!</p>";
		}
		else 
		{ 
			$hlaska .= "<p class='oznam'>Zákazník číslo $ID - $Meno $Priezvisko bol upravený.</p>";
		}
	// /UPDATE zakaznik
	
	 
	
	$_SESSION["hlaska"]=$hlaska; echo $_SESSION["hlaska"];
	
endif;
//-----------------------------------------------------------------------	

header('Location: index_zakaznik.php');
?>
