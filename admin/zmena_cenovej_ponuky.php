<?php
session_start();
include "config.php";  // konfiguracia
include "lib.php";	//	funkcie    
//phpinfo();
$dblink = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db); // pripojenie do databazy

if (!$dblink) { // kontrola ci je pripojenie na databazu dobre ak nie tak napise chybu
	echo "Chyba pripojenia na DB!</br>";
	exit;
}

if(ZistiPrava("Edit_cenove_ponuky",$dblink) == 0 AND $_POST["back"] != "Späť") 
{ 
	include "head.php";
	include "navbar.php";	 
	echo "<p class='oznam'>Nemáte práva na editáciu a pridávanie cenových ponúk.</p>";exit;
}

$hlaska="";
$_SESSION["hlaska"] = "";

if(!$_POST["ID_cenovej_ponuky"])
	$akcia="insert";
elseif($_POST["back"] == "Späť") 
	$akcia="preview";
else $akcia="update";
	
// ---------------- Idem insertovat zaznam do tabuluky ------------------
//echo $_POST["akcia"];
//echo $akcia;
if ($akcia=="insert" && $_POST["Celkova_cena_s_DPH"]!=""):  // ak je akcia insert z hidden parametru formulara a vyplnené meno nesmie byt prazdne
	
	$Nazov_firmy = Trim($_POST["Obj_Nazov_firmy"]);
	$Meno = Trim($_POST["Obj_Meno"]);
	$Priezvisko = Trim($_POST["Obj_Priezvisko"]);
	
 	$ID = $_POST["ID_cenovej_ponuky"];
	$ID_objednavky = $_POST["ID_objednavky"];
	$Datum_vytvorenia = date("Y/m/d");
	$Datum_upravy = date("Y/m/d");
	$Celkova_cena_s_DPH = Trim($_POST["Celkova_cena_s_DPH"]);
	$Poznamka = Trim($_POST["Cp_Poznamka"]);
	
	
	$Pouz_ID = $_SESSION['Login_ID_pouzivatela'];
	
	// INSERT CENOVEJ PONUKY

	
	$sql = "INSERT INTO cenova_ponuka(ID_cenovej_ponuky,Cp_Datum_vytvorenia,Cp_Datum_upravy,Celkova_cena_s_DPH,Cp_Poznamka,ID_objednavky,ID_pouzivatela)									   
									  VALUES (NULL,'$Datum_vytvorenia','$Datum_upravy','$Celkova_cena_s_DPH','$Poznamka','$ID_objednavky','$Pouz_ID')";
								
		$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz select a vysledok načítame do premennej $vysledok
		if (!$vysledok)
		{ 
			$hlaska = "<p class='cervene'>Chyba pri vkladani cenovej ponuky!</p>";
		}
		else
		{		
			$ID_posledneho_zaznamu = mysqli_insert_id($dblink);
			$hlaska = "<p class='oznam'>Cenová ponuka číslo $ID_posledneho_zaznamu pre zákazníka $Nazov_firmy $Meno $Priezvisko bola pridaná do databázy</p>";
		}
		
	// SLUZBY 
	$ID_novej_cenovej_ponuky = mysqli_insert_id($dblink);
	
	if($_POST['ID_sluzby_na_objednavke'])
	{	
		for ($i = 0; $i < count($_POST['ID_sluzby_na_objednavke']); $i++){
			$ID_sluzby_na_objednavke=$_POST['ID_sluzby_na_objednavke'][$i];
			$Cena_za_jednotku=$_POST['Cena_za_jednotku'][$i];
			$Cena_za_sluzbu=$_POST['Cena_za_sluzbu'][$i];
			
			$sql="INSERT INTO sluzby_na_cenovej_ponuke (Cena_za_jednotku, Cena_za_sluzbu, ID_cenovej_ponuky, ID_sluzby_na_objednavke) 
					VALUES	('$Cena_za_jednotku', '$Cena_za_sluzbu', '$ID_novej_cenovej_ponuky','$ID_sluzby_na_objednavke'); ";
			$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz select a vysledok načítame do premennej $vysledok
			if (!$vysledok)
				{ 
				$hlaska .= "<p class='cervene'>Chyba pri vkladani služby na cenovej ponuke!</p>";
				}
	
		}
	}
			
	$_SESSION["hlaska"]=$hlaska; 
	
endif;

//-----------------------------------------------------------------------

// ---------------- Idem Upravit zaznam do tabuluky ------------------
if ($akcia=="update" && $_POST["ID_cenovej_ponuky"]!="") : // chcem akciu update z hidden parametru formulara a meno nesmie byt prazdne

	$Nazov_firmy = Trim($_POST["Obj_Nazov_firmy"]);
	$Meno = Trim($_POST["Obj_Meno"]);
	$Priezvisko = Trim($_POST["Obj_Priezvisko"]);

 	$ID = $_POST["ID_cenovej_ponuky"];
	$ID_objednavky = $_POST["ID_objednavky"];
	$Datum_upravy = date("Y/m/d");
	
	$Celkova_cena_s_DPH = Trim($_POST["Celkova_cena_s_DPH"]);
	$Poznamka = Trim($_POST["Cp_Poznamka"]);
	
	$Pouz_ID = $_SESSION['Login_ID_pouzivatela'];
	
	$sql = "UPDATE cenova_ponuka SET Cp_Datum_upravy = '$Datum_upravy', Celkova_cena_s_DPH = '$Celkova_cena_s_DPH', Cp_Poznamka = '$Poznamka', ID_objednavky = '$ID_objednavky',ID_pouzivatela = '$Pouz_ID' WHERE ID_cenovej_ponuky=$ID";
	//echo $sql;exit;
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz update a vysledky nacitame do premennej $vysledok
	if (!$vysledok)
	{  
		$hlaska .= "<p class='cervene'>Chyba updatu cenovej ponuky!</p>";
	}
	else 
	{ 
		$hlaska .= "<p class='oznam'>Cenová ponuka číslo $ID pre zákazníka $Nazov_firmy $Meno $Priezvisko bola upravená.</p>";
	}
	if($_POST['ID_sluzby_na_objednavke']) 
	{
	for ($i = 0; $i < count($_POST['ID_sluzby_na_objednavke']);$i++){
    	$ID_sluzby_na_objednavke=$_POST['ID_sluzby_na_objednavke'][$i];
		$ID_sluzby_na_cenovej_ponuke=$_POST['ID_sluzby_na_cenovej_ponuke'][$i];
		$Cena_za_jednotku=$_POST['Cena_za_jednotku'][$i];
		$Cena_za_sluzbu=$_POST['Cena_za_sluzbu'][$i];
		if($ID_sluzby_na_cenovej_ponuke[$i])
			$sql="UPDATE sluzby_na_cenovej_ponuke SET Cena_za_jednotku='$Cena_za_jednotku', Cena_za_sluzbu='$Cena_za_sluzbu', ID_cenovej_ponuky='$ID', ID_sluzby_na_objednavke='$ID_sluzby_na_objednavke)' WHERE ID_sluzby_na_cenovej_ponuke='$ID_sluzby_na_cenovej_ponuke'"; 
		else 
			$sql="INSERT INTO sluzby_na_cenovej_ponuke (Cena_za_jednotku, Cena_za_sluzbu, ID_cenovej_ponuky, ID_sluzby_na_objednavke) 
				VALUES	('$Cena_za_jednotku', '$Cena_za_sluzbu', '$ID','$ID_sluzby_na_objednavke'); ";
		//echo 	$sql.'<br>';exit;
		$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz select a vysledok načítame do premennej $vysledok
		if (!$vysledok)
			{ 
			$hlaska .= "<p class='cervene'>Chyba pri update služby na objednavke!</p>";
			}
		}
	}
	
	
	
	
	$_SESSION["hlaska"]=$hlaska; echo $_SESSION["hlaska"];
	
endif;
//-----------------------------------------------------------------------	

header('Location: index_cenova_ponuka.php');
?>
