<?php
session_start();

include "config.php";  // konfiguracia
include "lib.php";	//	funkcie    

$dblink = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db); // pripojenie do databazy

if (!$dblink) { // kontrola ci je pripojenie na databazu dobre ak nie tak napise chybu
	echo "Chyba pripojenia na DB!</br>";
	exit;
}

//echo "akcia je:".$_POST["akcia"];exit;

if(ZistiPrava("Edit_objednavky",$dblink) == 0 AND $_POST["back"] != "Späť") 
{ 
	include "head.php";
	include "navbar.php";	 
	echo "<p class='oznam'>Nemáte práva na editáciu a pridávanie objednávok.</p>";exit;
}

$hlaska="";
$_SESSION["hlaska"] = "";

if(!$_POST["ID_objednavky"])
	$akcia="insert";
elseif($_POST["back"] == "Späť") 
	$akcia="preview";
else $akcia="update";

// ---------------- Idem insertovat zaznam do tabuluky ------------------

if ($_POST["akcia"]=="insert" && $_POST["Obj_Meno"]!="" && $_POST["back"] != "Späť"){  // ak je akcia insert z hidden parametru formulara a vyplnené meno nesmie byt prazdne
	
 	$ID = $_POST["ID_objednavky"];
	$Datum_vytvorenia = date("Y/m/d");
	$Datum_upravy = date("Y/m/d");
	$Nazov_firmy = Trim($_POST["Obj_Nazov_firmy"]);
	$Meno = Trim($_POST["Obj_Meno"]);
	$Priezvisko = Trim($_POST["Obj_Priezvisko"]);
	$Email = Trim($_POST["Obj_Email"]);
	$Telefon = Trim($_POST["Obj_Telefon"]);
	$Ulica_cislo_fakturacna = Trim($_POST["Obj_Ulica_cislo_fakturacna"]);
	$Mesto_fakturacna = Trim($_POST["Obj_Mesto_fakturacna"]);
	$PSC_fakturacna = Trim($_POST["Obj_PSC_fakturacna"]);
	$Ulica_cislo_dodacia = Trim($_POST["Obj_Ulica_cislo_dodacia"]);
	$Mesto_dodacia = Trim($_POST["Obj_Mesto_dodacia"]);
	$PSC_dodacia = Trim($_POST["Obj_PSC_dodacia"]);
	$ICO = Trim($_POST["Obj_ICO"]);
	$DIC = Trim($_POST["Obj_DIC"]);
	$IC_DPH = Trim($_POST["Obj_IC_DPH"]);
	$Stav_objednavky = Trim($_POST["Stav_objednavky"]);
	$Prideleny_zamestnanec=Trim($_POST["Prideleny_zamestnanec"]);
	$Poznamka = Trim($_POST["Obj_Poznamka"]);
	
	$Pouz_ID = $_SESSION['Login_ID_pouzivatela'];
		
	// Kontrola či zakaznik existuje v systeme
	
	if($ICO and $DIC)
	{
		$sql = "SELECT * FROM zakaznik WHERE Zak_ICO = '$ICO' AND Zak_DIC = '$DIC' LIMIT 1";
	}
	else
	{	
		$sql = "SELECT * FROM zakaznik WHERE Zak_Meno = '$Meno' AND Zak_Priezvisko = '$Priezvisko' AND Zak_Telefon = '$Telefon'
				AND Zak_Ulica_cislo_fakturacna = '$Ulica_cislo_fakturacna' AND Zak_Mesto_fakturacna = '$Mesto_fakturacna' LIMIT 1";
	}
	
	$vysledok = mysqli_query($dblink, $sql);
	if($vysledok AND (mysqli_num_rows($vysledok) != 0))
	{
		$riadok = mysqli_fetch_assoc($vysledok);
		$ID_zakaznika = $riadok["ID_zakaznika"];

	}
	else 
	{
		// INSERT ZAKAZNIKA
		$sql = "INSERT INTO zakaznik(ID_zakaznika,Zak_Nazov_firmy,Zak_Meno,Zak_Priezvisko,Zak_Email,Zak_Telefon,
								    Zak_Ulica_cislo_fakturacna,Zak_Mesto_fakturacna,Zak_PSC_fakturacna,
								    Zak_Ulica_cislo_dodacia,Zak_Mesto_dodacia,Zak_PSC_dodacia,
								    Zak_ICO,Zak_DIC,Zak_IC_DPH,Zak_Poznamka)									   
								    VALUES (NULL,'$Nazov_firmy','$Meno','$Priezvisko','$Email','$Telefon',
								    '$Ulica_cislo_fakturacna','$Mesto_fakturacna','$PSC_fakturacna',
								    '$Ulica_cislo_dodacia','$Mesto_dodacia','$PSC_dodacia',
								    '$ICO','$DIC','$IC_DPH','$Poznamka')";	
									
		$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz select a vysledok načítame do premennej $vysledok
		if (!$vysledok)
		{ 
			$hlaska = "Chyba pri vkladani zakaznika! </br>";
		}
		else
		{		
			$ID_posledneho_zaznamu = mysqli_insert_id($dblink);
			$hlaska .= "<p class='oznam'>Zákaznik číslo $ID_posledneho_zaznamu - $Nazov_firmy $Meno $Priezvisko bol pridaný do databázy</p>";
		}
	
		$ID_zakaznika = mysqli_insert_id($dblink);
	}
	IF($Prideleny_zamestnanec) 
	// INSERT OBJEDNAVKY
	$sql = "INSERT INTO objednavka(ID_objednavky,Obj_Datum_vytvorenia,Obj_Datum_upravy,Obj_Nazov_firmy,
								   Obj_Meno,Obj_Priezvisko,Obj_Email,Obj_Telefon,Obj_Ulica_cislo_fakturacna,Obj_Mesto_fakturacna,Obj_PSC_fakturacna,
								   Obj_Ulica_cislo_dodacia,Obj_Mesto_dodacia,Obj_PSC_dodacia,Obj_ICO,Obj_DIC,Obj_IC_DPH,Stav_objednavky,Obj_Poznamka,ID_pouzivatela,ID_zakaznika,ID_zamestnanca) 
								   VALUES (NULL,'$Datum_vytvorenia','$Datum_upravy','$Nazov_firmy','$Meno','$Priezvisko','$Email','$Telefon',
								   '$Ulica_cislo_fakturacna','$Mesto_fakturacna','$PSC_fakturacna',
								   '$Ulica_cislo_dodacia','$Mesto_dodacia','$PSC_dodacia','$ICO','$DIC','$IC_DPH','$Stav_objednavky','$Poznamka','$Pouz_ID','$ID_zakaznika','$Prideleny_zamestnanec')";
	else	$sql = "INSERT INTO objednavka(ID_objednavky,Obj_Datum_vytvorenia,Obj_Datum_upravy,Obj_Nazov_firmy,
								   Obj_Meno,Obj_Priezvisko,Obj_Email,Obj_Telefon,Obj_Ulica_cislo_fakturacna,Obj_Mesto_fakturacna,Obj_PSC_fakturacna,
								   Obj_Ulica_cislo_dodacia,Obj_Mesto_dodacia,Obj_PSC_dodacia,Obj_ICO,Obj_DIC,Obj_IC_DPH,Stav_objednavky,Obj_Poznamka,ID_pouzivatela,ID_zakaznika,ID_zamestnanca) 
								   VALUES (NULL,'$Datum_vytvorenia','$Datum_upravy','$Nazov_firmy','$Meno','$Priezvisko','$Email','$Telefon',
								   '$Ulica_cislo_fakturacna','$Mesto_fakturacna','$PSC_fakturacna',
								   '$Ulica_cislo_dodacia','$Mesto_dodacia','$PSC_dodacia','$ICO','$DIC','$IC_DPH','$Stav_objednavky','$Poznamka','$Pouz_ID','$ID_zakaznika',NULL)";
							   			   
															   
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz select a vysledok načítame do premennej $vysledok
	
	if (!$vysledok)
	{ 
		$hlaska .= "Chyba pri vkladani objednavky! </br>";
		
	}
	else
	{ 
		$ID_posledneho_zaznamu = mysqli_insert_id($dblink);
		$hlaska .= "<p class='oznam'>Objednávka číslo $ID_posledneho_zaznamu pre zákazníka $Nazov_firmy $Meno $Priezvisko bola pridaná do databázy.</p>";
	}
	
	// INSERT SLUZIEB
	$ID_novej_objednavky = mysqli_insert_id($dblink);
	
	//phpinfo();
	//pocet_sluzieb_na_objednavke je nastaveny v config
	for ($i=0; $i < $pocet_sluzieb_na_objednavke; $i++){
		$ID_sluzby=$_POST['ID_sluzby'][$i]; // pole so sluzbami
		$Pocet_kusov=$_POST['Pocet_kusov'][$i]; // pole so sluzbami
		IF(!$Pocet_kusov) $Pocet_kusov=1;
		$Sirka=$_POST['Rozmer_sirka'][$i]; // pole so sluzbami
		$Vyska=$_POST['Rozmer_vyska'][$i]; // pole so sluzbami
		$Farba=$_POST['Farba'][$i]; // pole so sluzbami
		$Velkost=$_POST['Velkost'][$i]; // pole so sluzbami
		$Format=$_POST['Format'][$i]; 
			
		
		
		IF($ID_sluzby AND $ID_sluzby!=""){
			$sql="INSERT INTO sluzby_na_objednavke (ID_sluzby_na_objednavke, Pocet_kusov, Rozmer_sirka, Rozmer_vyska, Farba, Format, Velkost, ID_objednavky, ID_sluzby) 
				VALUES	(NULL,  '$Pocet_kusov', '$Sirka', '$Vyska', '$Farba','$Format', '$Velkost', $ID_novej_objednavky,'$ID_sluzby' ); ";
			//echo 	$sql.'<br>';exit;
			$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz select a vysledok načítame do premennej $vysledok
			if (!$vysledok)
				{ 
				$hlaska .= "Chyba pri vkladani služby na objednavke! </br>";//exit;
				}
		}
	}
	/* PRILOHY */	
	$j=1;
	$pocet_priloh = 0;
	if ($_FILES["userfile1"]['name'])  //uzivatel vybral novy subor
	       {include 'upload.php';}
	$j=2;	   
	if ($_FILES["userfile2"]['name'])  //uzivatel vybral novy subor
	       {include 'upload.php';}
	$j=3;
	if ($_FILES["userfile3"]['name'])  //uzivatel vybral novy subor
	       {include 'upload.php';}
	$j=4;	   
	if ($_FILES["userfile4"]['name'])  //uzivatel vybral novy subor
	       {include 'upload.php';}
	$j=5;
	if ($_FILES["userfile5"]['name'])  //uzivatel vybral novy subor
	       {include 'upload.php';}		   
	
	if($pocet_priloh == 1)
	$hlaska.='<p class="oznam"> '.$pocet_priloh.' príloha bola úspešne odoslaná.</p>';

	if($pocet_priloh >= 2 AND $pocet_priloh <= 4)
	$hlaska.='<p class="oznam"> '.$pocet_priloh.' prílohy boli úspešne odoslané.</p>';

	if($pocet_priloh >= 5)
	$hlaska.='<p class="oznam"> '.$pocet_priloh.' príloh bolo úspešne odoslaných.</p>';
	
	if($_POST['Zmazat_prilohy'] and $_POST['Zmazat_prilohy']=="zmazat"){	   
		$sql="SELECT count(1) as pocet_riadkov from prilohy where ID_objednavky=$ID";
		$num_rows = zisti_pocet_riadkov($dblink,$sql);
		$sql = "Select * FROM prilohy where ID_objednavky=$ID"; 
		$vysledok_prilohy=mysqli_query($dblink,$sql);
		if (!$vysledok_prilohy)
			{$hlaska .="Doslo k chybe pri vyhladani príloh !";}
		elseif($num_rows!=0){ 
			for ($s=0; $s < $num_rows; $s++){// poradie priloh
				$row=mysqli_fetch_assoc($vysledok_prilohy);
				if($_POST['Zmazat_prilohu_'.$s] and ($_POST['Zmazat_prilohu_'.$s]=="zmazat")){		
					$cesta='prilohy'.$row['Nazov_suboru'];
					$ID_prilohy=$row['ID_prilohy'];
					if (file_exists($cesta)){     //existuje taky subor na disku
						@unlink($cesta);     // vymazeme ho
					}
					$sql = "Delete FROM prilohy where ID_prilohy=$ID_prilohy"; // vymazeme aj v db
					$vysledok=mysqli_query($dblink,$sql);
					if(!$vysledok)
						$hlaska .= "Chyba pri vymazani prilohy. ";
					else $hlaska .= "Príloha ".$cesta." bola vymazaná. ";
				}		
			}
		}
		else{ 	$hlaska .="Chyba pri vymazani priloh !";}
	
	/* END PRILOHY */	
	}
	
	$_SESSION["hlaska"]=$hlaska; //echo $_SESSION["hlaska"];
	
}

//-----------------------------------------------------------------------

// ---------------- Idem Upravit zaznam do tabuluky ------------------
if ($_POST["akcia"]=="update" && $_POST["ID_objednavky"]!="" && $_POST["back"] != "Späť"){ // chcem akciu update z hidden parametru formulara a meno nesmie byt prazdne

 	$ID = $_POST["ID_objednavky"];
	$Datum_upravy = date("Y/m/d");
	$Nazov_firmy = Trim($_POST["Obj_Nazov_firmy"]);
	$Meno = Trim($_POST["Obj_Meno"]);
	$Priezvisko = Trim($_POST["Obj_Priezvisko"]);
	$Email = Trim($_POST["Obj_Email"]);
	$Telefon = Trim($_POST["Obj_Telefon"]);
	$Ulica_cislo_fakturacna = Trim($_POST["Obj_Ulica_cislo_fakturacna"]);
	$Mesto_fakturacna = Trim($_POST["Obj_Mesto_fakturacna"]);
	$PSC_fakturacna = Trim($_POST["Obj_PSC_fakturacna"]);
	$Ulica_cislo_dodacia = Trim($_POST["Obj_Ulica_cislo_dodacia"]);
	$Mesto_dodacia = Trim($_POST["Obj_Mesto_dodacia"]);
	$PSC_dodacia = Trim($_POST["Obj_PSC_dodacia"]);
	$ICO = Trim($_POST["Obj_ICO"]);
	$DIC = Trim($_POST["Obj_DIC"]);
	$IC_DPH = Trim($_POST["Obj_IC_DPH"]);
	$Stav_objednavky = Trim($_POST["Stav_objednavky"]);
	$Prideleny_zamestnanec = Trim($_POST["Prideleny_zamestnanec"]);
	$Poznamka = Trim($_POST["Obj_Poznamka"]);
	
	$Pouz_ID = $_SESSION['Login_ID_pouzivatela'];
	
	IF($Prideleny_zamestnanec) 
	$sql = "UPDATE objednavka SET Obj_Nazov_firmy = '$Nazov_firmy', Obj_Meno='$Meno', Obj_Datum_upravy='$Datum_upravy', Obj_Priezvisko='$Priezvisko', Obj_Email='$Email', Obj_Telefon='$Telefon', 
	Obj_Ulica_cislo_fakturacna='$Ulica_cislo_fakturacna', Obj_Mesto_fakturacna='$Mesto_fakturacna', Obj_PSC_fakturacna='$PSC_fakturacna',
	Obj_Ulica_cislo_dodacia='$Ulica_cislo_dodacia', Obj_Mesto_dodacia='$Mesto_dodacia', Obj_PSC_dodacia='$PSC_dodacia',
	Obj_ICO='$ICO', Obj_DIC='$DIC', Obj_IC_DPH='$IC_DPH', Stav_objednavky = '$Stav_objednavky', ID_pouzivatela = '$Pouz_ID', ID_zamestnanca = '$Prideleny_zamestnanec', Obj_Poznamka='$Poznamka'
	WHERE ID_objednavky=$ID";
	ELSE $sql = "UPDATE objednavka SET Obj_Nazov_firmy = '$Nazov_firmy', Obj_Meno='$Meno', Obj_Datum_upravy='$Datum_upravy', Obj_Priezvisko='$Priezvisko', Obj_Email='$Email', Obj_Telefon='$Telefon', 
	Obj_Ulica_cislo_fakturacna='$Ulica_cislo_fakturacna', Obj_Mesto_fakturacna='$Mesto_fakturacna', Obj_PSC_fakturacna='$PSC_fakturacna',
	Obj_Ulica_cislo_dodacia='$Ulica_cislo_dodacia', Obj_Mesto_dodacia='$Mesto_dodacia', Obj_PSC_dodacia='$PSC_dodacia',
	Obj_ICO='$ICO', Obj_DIC='$DIC', Obj_IC_DPH='$IC_DPH', Stav_objednavky = '$Stav_objednavky', ID_pouzivatela = '$Pouz_ID', ID_zamestnanca = NULL, Obj_Poznamka='$Poznamka'
	WHERE ID_objednavky=$ID";
	
	$vysledok = mysqli_query($dblink, $sql); // vykonam sql prikaz update a vysledky nacitame do premennej $vysledok
	if (!$vysledok)
	{  
		$hlaska .= "Chyba updatu! </br>";
	}
	else 
	{ 
		$hlaska .= "<p class='oznam'>Objednávka číslo $ID od zákazníka $Meno $Priezvisko bola upravená.</p>";
	}
  	// UPDATE SLUZIEB
	
	for ($i=0; $i < $pocet_sluzieb_na_objednavke; $i++){
		$ID_sluzby_na_objednavke=$_POST["ID_sluzby_na_objednavke"][$i]; 
		$ID_sluzby=$_POST["ID_sluzby"][$i]; 
		$Pocet_kusov=$_POST["Pocet_kusov"][$i]; 
		if(!$Pocet_kusov) $Pocet_kusov=1;
		$Format=$_POST['Format'][$i]; 
		$Rozmer_sirka=$_POST['Rozmer_sirka'][$i]; 
		$Rozmer_vyska=$_POST['Rozmer_vyska'][$i]; 
		$Farba=$_POST["Farba"][$i]; 
		$Velkost=$_POST["Velkost"][$i]; 
		
			
		
		
		
		if($ID_sluzby and $ID_sluzby!='' and !$ID_sluzby_na_objednavke){
			$sql="INSERT INTO sluzby_na_objednavke (ID_sluzby_na_objednavke, Pocet_kusov, Rozmer_sirka, Rozmer_vyska, Farba, Format, Velkost, ID_objednavky, ID_sluzby) 
				VALUES	(NULL,  '$Pocet_kusov', '$Rozmer_sirka', '$Rozmer_vyska', '$Farba','$Format', '$Velkost', '$ID','$ID_sluzby' ); ";
			//echo 	$sql.'<br>';exit;
			$vysledok = mysqli_query($dblink, $sql); 
			if (!$vysledok)	{ 
				$hlaska .= "Chyba pri vkladaní služby na objednavke! </br>";//exit;
			}
		}
		elseif($ID_sluzby and $ID_sluzby!='' and $ID_sluzby_na_objednavke){
			$sql="UPDATE sluzby_na_objednavke SET Pocet_kusov='$Pocet_kusov', Rozmer_sirka='$Rozmer_sirka', Rozmer_vyska='$Rozmer_vyska', Farba='$Farba', Format='$Format', Velkost='$Velkost', ID_sluzby='$ID_sluzby' WHERE ID_sluzby_na_objednavke=$ID_sluzby_na_objednavke"; 
			//echo 	$sql.'<br>';exit;
			$vysledok = mysqli_query($dblink, $sql); 
			if (!$vysledok)	{ 
				$hlaska .= "Chyba pri update služby na objednavke! </br>";//exit;
			}
		}	
		elseif((!$ID_sluzby or $ID_sluzby=='') and $ID_sluzby_na_objednavke){	
			$sql="DELETE FROM sluzby_na_objednavke WHERE ID_sluzby_na_objednavke=$ID_sluzby_na_objednavke"; 
			
			$vysledok = mysqli_query($dblink, $sql); 
			if (!$vysledok)	{ 
				$hlaska .= "Chyba pri update služby na objednavke! </br>";//exit;
			}
		}
		
	}// endfor
	
	$j=1;
	$pocet_priloh = 0;
	if ($_FILES["userfile1"]['name'])  //pouzivatel vybral novy subor
	       {include 'upload.php';}
	$j=2;	   
	if ($_FILES["userfile2"]['name'])  //pouzivatel vybral novy subor
	       {include 'upload.php';}
	$j=3;
	if ($_FILES["userfile3"]['name'])  //pouzivatel vybral novy subor
	       {include 'upload.php';}
	$j=4;	   
	if ($_FILES["userfile4"]['name'])  //pouzivatel vybral novy subor
	       {include 'upload.php';}
	$j=5;	   
	if ($_FILES["userfile5"]['name'])  //pouzivatel vybral novy subor
	       {include 'upload.php';}		   
	
	if($pocet_priloh == 1)
	$hlaska.='<p class="oznam"> '.$pocet_priloh.' príloha bola úspešne pridaná.</p>';

	if($pocet_priloh >= 2 AND $pocet_priloh <= 4)
	$hlaska.='<p class="oznam"> '.$pocet_priloh.' prílohy boli úspešne pridané.</p>';

	if($pocet_priloh >= 5)
	$hlaska.='<p class="oznam"> '.$pocet_priloh.' príloh bolo úspešne pridaných.</p>';
	
	if($_POST['Zmazat_prilohy'] and $_POST['Zmazat_prilohy']=="zmazat"){	   
		$sql="SELECT count(1) as pocet_riadkov from prilohy where ID_objednavky=$ID";
		$num_rows = zisti_pocet_riadkov($dblink,$sql);
		$sql = "Select * FROM prilohy where ID_objednavky=$ID"; 
		$vysledok_prilohy=mysqli_query($dblink,$sql);
		if (!$vysledok_prilohy)
			{$hlaska .="Doslo k chybe pri vyhladani príloh !";}
		elseif($num_rows!=0){ 
			for ($s=0; $s < $num_rows; $s++){// poradie priloh
				$row=mysqli_fetch_assoc($vysledok_prilohy);
				if($_POST['Zmazat_prilohu_'.$s] and ($_POST['Zmazat_prilohu_'.$s]=="zmazat")){		
					$cesta='prilohy'.$row['Nazov_suboru'];
					$ID_prilohy=$row['ID_prilohy'];
					if (file_exists($cesta)){     //existuje taky subor na disku
						@unlink($cesta);     // vymazeme ho
					}
					$sql = "Delete FROM prilohy where ID_prilohy=$ID_prilohy"; // vymazeme aj v db
					$pocet_priloh_del = $pocet_priloh_del +1;
					$vysledok=mysqli_query($dblink,$sql);
					if(!$vysledok)
						$hlaska .= "Chyba pri vymazani prilohy. ";
				}		
			}
			if($pocet_priloh_del == 1)
			$hlaska .= '<p class="oznam">'.$pocet_priloh_del.' príloha bola úspešne vymazaná.</span>';

			if($pocet_priloh_del >= 2 AND $pocet_priloh_del <= 4)
			$hlaska .= '<p class="oznam">'.$pocet_priloh_del.' prílohy boli úspešne vymazané.</span>';

			if($pocet_priloh_del >= 5)
			$hlaska .= '<p class="oznam">'.$pocet_priloh_del.' príloh bolo úspešne vymazaných.</span>';
			
			
		}
		else{ 	$hlaska .="Chyba pri vymazani priloh !";}
		
		
	}
	$_SESSION["hlaska"]=$hlaska; 
} // end update	
	

//-----------------------------------------------------------------------	

header('Location: index.php');
?>
