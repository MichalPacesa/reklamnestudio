<!DOCTYPE html>
<?php session_start(); ?>
<html>
<head>
<?php include "head.php";

//define( '_VALID_MOS', 1 );
include 'login.php';

if (!isset($_SESSION['Login_Prihlasovacie_meno']))  // nie je prihlaseny
{
exit;
}

?>
<script>

function vyber_sluzbu(id_sluzby, poradie){
	 //id_sluzby (text): ID vybratej sluzby
	 // poradie (int) riadok sluzby na objednavke 
	
	var id_sirka='Sluzba_'+poradie+'_sirka';
	var id_vyska='Sluzba_'+poradie+'_vyska';
	var id_velkost='Sluzba_'+poradie+'_velkost';
	var id_pomlcka1='Pomlcka1_'+poradie; // ked sa neda vybrat vlastny rozmer 
	var id_pomlcka2='Pomlcka2_'+poradie; // ked sa neda vybrat velkost oblecenia
	var id_format='Sluzba_'+poradie+'_format';
	var id_farba='Sluzba_'+poradie+'_farba'; 
	var id_div_farba='Farba_'+poradie; 
	var id_pocet_kusov='Sluzba_'+poradie+'_pocet_kusov'; 
	if(!id_sluzby || id_sluzby=="0" || id_sluzby=="") // vybrali prazdne
		{
		document.getElementById(id_sirka).style.display='none';
		document.getElementById(id_vyska).style.display='none';
		document.getElementById(id_velkost).style.display='none';
		document.getElementById(id_pomlcka1).style.display='block';
		document.getElementById(id_pomlcka2).style.display='block';
		document.getElementById(id_pocet_kusov).value='';
		document.getElementById(id_farba).value='';
		if(document.getElementById(id_farba).selected) 
			document.getElementById(id_farba).selected='';
		document.getElementById(id_div_farba).value='';
		
		document.getElementById(id_format).selected='';
		document.getElementById(id_format).value='';
		
		}
	else{
		// zistime udaje o sluzbe
		var str=id_sluzby;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
        document.getElementById("txtHint").innerHTML = this.responseText;// vlozi sa obsah search.php
		
		var Zobr_rozmery=Zobr_velkost=Dost_formaty=Farby='';
		if(document.getElementById('Zobrazit_rozmery')){
			Zobr_rozmery=document.getElementById('Zobrazit_rozmery').value;
		}
		
		if(document.getElementById('Zobrazit_velkost')){
			Zobr_velkost=document.getElementById('Zobrazit_velkost').value;
		}	
				
		// FORMATY		
		var Select_formatov='<select class="select_objednavka" name="Format['+poradie+']" id="Sluzba_'+poradie+'_format" ><option value=""  selected></option>';	
		if(document.getElementById('Dostupne_formaty')){
			Dost_formaty=document.getElementById('Dostupne_formaty').value;//formaty oddelene ciarkami
		}	
		
		if(Dost_formaty){
			var myArray = Dost_formaty.split(",");
			for (let i = 0; i < myArray.length; i++) {
				if(myArray[i])
					Select_formatov+='<option value="'+myArray[i]+'">'+myArray[i]+'</option>';
			}
		}	
		else {	// nie su dostupne predvolene formaty
			Select_formatov+='<option value="A5">A5</option>';
			Select_formatov+='<option value="A4">A4</option>';
			Select_formatov+='<option value="A3">A3</option>';
			Select_formatov+='<option value="A2">A2</option>';
			Select_formatov+='<option value="A1">A1</option>';
		}	
		Select_formatov+='</select>';
		document.getElementById(id_format).innerHTML=Select_formatov; // zapiseme do html iny combobox 
		 // END FORMATY	 
		// FARBY		
		if(document.getElementById('Farby')){
			Farby=document.getElementById('Farby').value;
		}	
		if(Farby){
			var Select_farieb='<select class="select_objednavka" name="Farba['+poradie+']" id="Sluzba_'+poradie+'_farby" ><option value="Vyberte"  selected></option>';	
			var myArray = Farby.split(",");
			for (let i = 0; i < myArray.length; i++) {
				if(myArray[i])
					Select_farieb+='<option value="'+myArray[i]+'">'+myArray[i]+'</option>';
			}
			Select_farieb+='</select>';
		}	
		else {	// nie su dostupne predvolene farby
			Select_farieb='<input type="text" id="Sluzba_'+poradie+'_farba" name="Farba['+poradie+']" value="" >';
			
		}	

		document.getElementById(id_div_farba).innerHTML=Select_farieb; // zapiseme do html iny combobox alebo input
		// END FARBY	
		
				
		// ci zobrazit vlastne rozmery pre sluzbu
		if(!Zobr_rozmery || Zobr_rozmery=="0")  
			{
			document.getElementById(id_sirka).style.display='none';
			document.getElementById(id_vyska).style.display='none';
			document.getElementById(id_pomlcka1).style.display='block';
			}
		else{ 
			document.getElementById(id_sirka).style.display='block';
			document.getElementById(id_vyska).style.display='block';
			document.getElementById(id_pomlcka1).style.display='none';
			}
		// ci zobrazit velkost oblecenia pre sluzbu
		if(!Zobr_velkost  || Zobr_velkost=="0")   
			{
			document.getElementById(id_velkost).style.display='none';
			document.getElementById(id_pomlcka2).style.display='block';
			}
		else 
			{
			document.getElementById(id_velkost).style.display='block';
			document.getElementById(id_pomlcka2).style.display='none';
			}
						
		}
		};//end function onreadystatechange
    
		xmlhttp.open("GET","search.php?q="+str,true); // zavolame search.php
		xmlhttp.send();
				
		}
		
	return;
} // end function vyber_sluzbu
</script>
<script>

function zmazat_prilohu(poradie,nazovsuboru){
		var c = confirm("Ste si istý, že chcete zmazať prílohu "+nazovsuboru+" ?");
		if(c){ 		
			var div_suboru='Subor_'+poradie;
			var input_suboru='Zmazat_prilohu_'+poradie; 
			document.getElementById(div_suboru).style.display="none";
			document.getElementById(input_suboru).value="zmazat";
			document.getElementById('Zmazat_prilohy').value="zmazat";
			}
		return;
	}
</script>	
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

$ID_objednavky=$_POST["ID_objednavky"];
$ID_objednavky=intval($ID_objednavky);

if($ID_objednavky)
{
	$zobrazit=$_POST["zobrazit_x"];
	$sql="SELECT * FROM objednavka o LEFT JOIN zamestnanec z ON o.ID_zamestnanca = z.ID_zamestnanca LEFT JOIN registrovany_pouzivatel r ON o.ID_pouzivatela=r.ID_pouzivatela WHERE o.ID_objednavky = $ID_objednavky";
	if($zobrazit) 
	{
		$akcia="preview";
		$nadpis = "Objednávka č. ".$ID_objednavky;
	}	
	else
	{	
		if(ZistiPrava("Edit_objednavky",$dblink) == 0) 
		{ 
			include "navbar.php";
			echo "<p class='oznam'>Nemáte práva na editáciu a pridávanie objednávky</p>";exit;
		}
		
		$akcia = "update"; 
		$nadpis = "Editácia objednávky č. ".$ID_objednavky;
	}	
	$vysledok = mysqli_query($dblink,$sql); 
	$riadok = mysqli_fetch_assoc($vysledok);
	$datum_vytvorenia = $riadok["Obj_Datum_vytvorenia"];
	$datum_upravy = $riadok["Obj_Datum_upravy"];
	$Nazov_firmy = $riadok["Obj_Nazov_firmy"];
	$Meno = $riadok["Obj_Meno"];
	$Priezvisko = $riadok["Obj_Priezvisko"];
	$Email = $riadok["Obj_Email"];
	$Telefon = $riadok["Obj_Telefon"];
	$Ulica_cislo_fakturacna = $riadok["Obj_Ulica_cislo_fakturacna"];
	$Mesto_fakturacna = $riadok["Obj_Mesto_fakturacna"];
	$PSC_fakturacna = $riadok["Obj_PSC_fakturacna"];
	$Ulica_cislo_dodacia = $riadok["Obj_Ulica_cislo_dodacia"];
	$Mesto_dodacia = $riadok["Obj_Mesto_dodacia"];
	$PSC_dodacia = $riadok["Obj_PSC_dodacia"];
	$ICO = $riadok["Obj_ICO"];
	$DIC = $riadok["Obj_DIC"];
	$IC_DPH = $riadok["Obj_IC_DPH"];
	$Stav_objednavky = $riadok["Stav_objednavky"];
	$Prideleny_zamestnanec = $riadok["ID_zamestnanca"];
	$Posledny_pouzivatel = $riadok["Pouz_Meno"]." ".$riadok["Pouz_Priezvisko"];
	$Poznamka = $riadok["Obj_Poznamka"];
	
	$Upraveny_Datum_upravy = date("d.m.Y", strtotime($riadok["Obj_Datum_upravy"]));	
}
else
{
	if(ZistiPrava("Edit_objednavky",$dblink) == 0) 
	{ 
		include "navbar.php";
		echo "<p class='oznam'>Nemáte práva na editáciu a pridávanie objednávky</p>";exit;
	}
	$akcia = "insert";
	$nadpis = "Nová objednávka";
	$ID_objednavky = "";
	$Upraveny_Datum_upravy = "";
	$Nazov_firmy = "";
	$Meno = "";
	$Priezvisko = "";
	$Email = "";
	$Telefon = "";
	$Ulica_cislo_fakturacna = "";
	$Mesto_fakturacna = "";
	$PSC_fakturacna = "";
	$Ulica_cislo_dodacia = "";
	$Mesto_dodacia = "";
	$PSC_dodacia = "";
	$ICO = "";
	$DIC = "";
	$IC_DPH = "";
	$Poznamka = "";
	$Prideleny_zamestnanec ="";
	$Posledny_pouzivatel = "";
}
?>

<h1><?php echo $nadpis; ?></h1>
<p>

	<?php if($Upraveny_Datum_upravy): ?>
	<div class="container d-flex">
		<p class="oznam text-grey text-start" style="margin-top: 5px !important;margin-left:-10px!important;">Položky označené <span class="red bold">*</span> sú povinné</p>
		<p class="oznam text-grey text-end " style="margin-top: 5px !important;margin-right:-10px!important;">Dátum poslednej úpravy: <?php echo $Upraveny_Datum_upravy;?> Upravil: <?php echo $Posledny_pouzivatel;?></p>
	</div>	
	<?php else: ?>
	<p class="oznam text-grey text-start">Položky označené <span class="red bold">*</span> sú povinné</p>
	<?php endif; ?>
	
	<?php if($akcia!="preview"): ?>
	<form action="vyber_zakaznika.php" method="POST">
	<input style="margin-left: 17.8rem" type="submit" value="Výber zákazníka">
	<input type="hidden" name="ID_objednavky" value="<?php echo $ID_objednavky;?>">
	<input type="hidden" name="akcia" value="<?php echo $akcia;?>">
	</form>
	<?php endif; ?>
	
	<?php if($zobrazit): ?>
	<br>
	<?php 
	else:
	echo '<div style="margin-top:-1.5rem;"></div>';
	endif;
		
	$ID_zakaznika=$_POST["ID_vybrateho_zakaznika"];
	$ID_zakaznika=intval($ID_zakaznika);
	if($ID_zakaznika) 
	{
		$akcia="vyber";
		$sql="SELECT * FROM zakaznik z LEFT JOIN registrovany_pouzivatel r ON z.ID_pouzivatela = r.ID_pouzivatela WHERE z.ID_zakaznika = $ID_zakaznika";
		$vysledok = mysqli_query($dblink,$sql); 
		$riadok = mysqli_fetch_assoc($vysledok);
		$Nazov_firmy = $riadok["Zak_Nazov_firmy"];
		$Meno = $riadok["Zak_Meno"];
		$Priezvisko = $riadok["Zak_Priezvisko"];
		$Email = $riadok["Zak_Email"];
		$Telefon = $riadok["Zak_Telefon"];
		$Ulica_cislo_fakturacna = $riadok["Zak_Ulica_cislo_fakturacna"];
		$Mesto_fakturacna = $riadok["Zak_Mesto_fakturacna"];
		$PSC_fakturacna = $riadok["Zak_PSC_fakturacna"];
		$Ulica_cislo_dodacia = $riadok["Zak_Ulica_cislo_dodacia"];
		$Mesto_dodacia = $riadok["Zak_Mesto_dodacia"];
		$PSC_dodacia = $riadok["Zak_PSC_dodacia"];
		$ICO = $riadok["Zak_ICO"];
		$DIC = $riadok["Zak_DIC"];
		$IC_DPH = $riadok["Zak_IC_DPH"];
		$Nazov_banky = $riadok["Zak_Nazov_banky"];
	}
	?>
<form id="form1" enctype="multipart/form-data" action="zmena_objednavky.php" method="POST" id="form">
<table class="zoznam" border="1" align="center" cellpadding="0" cellspacing="0">
	<!-- STAV OBJEDNAVKY -->
	<tr><td style = "vertical-align:middle !important;">Stav objednávky</td>
	<td style = "vertical-align:middle !important;">
	<select class="vyber" name="Stav_objednavky" <?php echo disabled_vyber($akcia);?>>
	<?php 
	if($ID_zakaznika)
	{
		$ID_objednavky=$_POST["ID_objednavky"];
	 	$akcia=$_POST["akcia"];
		unset($_SESSION["akcia"]);
	}
	if($akcia == "insert")
	{ 
	$Stav_objednavky = 1;
	}
	?>
	<option value="1" <?php echo selected($Stav_objednavky,'1') ?> > Prijatá </option>
	<option value="2" <?php echo selected($Stav_objednavky,'2') ?> > Rozpracovaná </option>
	<option value="3" <?php echo selected($Stav_objednavky,'3') ?> > Dokončená - neuhradená </option>
	<option value="4" <?php echo selected($Stav_objednavky,'4') ?> > Dokončená - uhradená </option>
	<option value="5" <?php echo selected($Stav_objednavky,'5') ?> > Odoslaná </option>
	<option value="6" <?php echo selected($Stav_objednavky,'6') ?> > Vybavená </option>
	<option value="7" <?php echo selected($Stav_objednavky,'7') ?> > Stornovaná </option>
	</select>
	</td></tr>
	<!-- END STAV OBJEDNAVKY -->
	<!-- PRIDELENY ZAMESTNANEC -->
	
	<tr><td>Pridelený zamestnanec</td>
	<td style = "vertical-align:middle !important;">
	<?php  $sql = "Select * FROM zamestnanec"; 
			$vysledok=mysqli_query($dblink,$sql);
			if (!$vysledok):
				echo "Doslo k chybe pri vytvarani SQL dotazu !";
			else:	?>
			<select class = "vyber" name="Prideleny_zamestnanec" <?php echo disabled_vyber($akcia);?> <?php if($_SESSION["Login_ID_prava"] != 1) { echo "disabled"; }?>>
				<option value="0"> </option> 
			<?php while($riadok=mysqli_fetch_assoc($vysledok)): ?>
				<option value="<?php echo $riadok["ID_zamestnanca"];?>" <?php echo selected($Prideleny_zamestnanec,$riadok["ID_zamestnanca"]); ?>><?php echo $riadok["Zam_Meno"]." ".$riadok["Zam_Priezvisko"]; ?></option>
			<?php endwhile; ?>
			</select>
			
	<?php  endif; 	/* end zamestnanci filter */ ?>
	</td></tr>
	<!-- END PRIDELENY ZAMESTNANEC -->
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Kontaktné údaje:</b></td></tr>
	<tr><td>Názov firmy:</td><td style="width:50%;"> <input type="text" name="Obj_Nazov_firmy"  value="<?php echo $Nazov_firmy;?>" <?php echo disabled_vyber($akcia);?>></br></td></tr>
	<tr><td>Meno:<span class="hviezdicka">*</span></td><td> <input required type="text" id="Obj_Meno" name="Obj_Meno" value="<?php echo $Meno;?>" <?php echo disabled_vyber($akcia);?>></br></td></tr>
	<tr><td>Priezvisko:<span class="hviezdicka">*</span></td><td> <input required  type="text" name="Obj_Priezvisko" value="<?php echo $Priezvisko;?>" <?php echo disabled_vyber($akcia);?>></br></td></tr>
	<tr><td>Email:<span class="hviezdicka">*</span></td><td> <input required  type="text" name="Obj_Email" value="<?php echo $Email;?>" <?php echo disabled_vyber($akcia);?>></br></td></tr>
	<tr><td>Telefon:<span class="hviezdicka">*</span></td><td> <input required  type="text" name="Obj_Telefon" placeholder="Zadajte v tvare +421..." value="<?php echo $Telefon;?>" <?php echo disabled_vyber($akcia);?>></br></td></tr>

	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Fakturačná adresa:</b></td></tr>
	<tr><td>Ulica a číslo domu (fakturačná adresa):<span class="hviezdicka">*</span></td><td> <input required  type="text" name="Obj_Ulica_cislo_fakturacna" value="<?php echo $Ulica_cislo_fakturacna;?>" <?php echo disabled_vyber($akcia);?>></br></td></tr>
	<tr><td>Mesto (fakturačná adresa):<span class="hviezdicka">*</span></td><td> <input required  type="text" name="Obj_Mesto_fakturacna" value="<?php echo $Mesto_fakturacna;?>" <?php echo disabled_vyber($akcia);?>></br></td></tr>
	<tr><td>PSČ (fakturačná adresa):<span class="hviezdicka">*</span></td><td> <input required  type="text" name="Obj_PSC_fakturacna" value="<?php echo $PSC_fakturacna;?>" <?php echo disabled_vyber($akcia);?>></br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Dodacia adresa:</b> <span class="grey"> (Vyplňte len v prípade, že sa líši od fakturačnej.)</span>  </td></tr>
	<tr><td>Ulica a číslo domu (dodacia adresa):</td><td> <input   type="text" name="Obj_Ulica_cislo_dodacia"  value="<?php echo $Ulica_cislo_dodacia;?>" <?php echo disabled_vyber($akcia);?>></br></td></tr>
	<tr><td>Mesto (dodacia adresa):</td><td> <input   type="text" name="Obj_Mesto_dodacia" value="<?php echo $Mesto_dodacia;?>" <?php echo disabled_vyber($akcia);?>></br></td></tr>
	<tr><td>PSČ (dodacia adresa):</td><td> <input   type="text" name="Obj_PSC_dodacia" value="<?php echo $PSC_dodacia;?>" <?php echo disabled_vyber($akcia);?>></br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Firemné údaje:</b></td></tr>
	<tr><td>IČO:</td><td> <input   type="text" name="Obj_ICO" value="<?php echo $ICO;?>" <?php echo disabled_vyber($akcia);?>></br></td></tr>
	<tr><td>DIČ:</td><td> <input   type="text" name="Obj_DIC" value="<?php echo $DIC;?>" <?php echo disabled_vyber($akcia);?>></br></td></tr>
	<tr><td>IČ DPH:</td><td> <input   type="text" name="Obj_IC_DPH" value="<?php echo $IC_DPH;?>" <?php echo disabled_vyber($akcia);?>></br></td></tr>
	<tr><td colspan="2"><br></td></tr>
	<tr><td colspan="2"><b>Objednávam si nasledujúce služby:</b><br><br>
	<!-- SLUŽBY NA OBJEDNÁVKE -->	
		<div id="sluzby_na_objednavke"  width="100%">
		<?php	
				// edit objednavky
				if($ID_objednavky){
					$sql_sluzby_na_objednavke = "Select * FROM sluzby_na_objednavke so LEFT JOIN sluzba s ON so.ID_sluzby=s.ID_sluzby WHERE so.ID_objednavky=$ID_objednavky"; 
					// sluzby pre objednavku
 					
					$vysledok2=mysqli_query($dblink,$sql_sluzby_na_objednavke);
					if (!$vysledok2){
						echo "Doslo k chybe pri vyhľadaní údajov o službách na objednávke !";
						exit;
					}	
					$i=0;
					// v cykle nacitame postupne riadky tabulky sluzby na objednavke
					while ($riadok2 = mysqli_fetch_assoc($vysledok2)) {
						
							$ID_sluzby_na_objednavke[$i]=$riadok2['ID_sluzby_na_objednavke']; //id  sluzby na objednavke 
							$ID_sluzby[$i]=$riadok2['ID_sluzby'];// id sluzby zapisanej z comboboxu
							if($riadok2['Pocet_kusov']) $Pocet_kusov[$i]=$riadok2['Pocet_kusov']; else $Pocet_kusov[$i]="";
							$Farba[$i]=$riadok2['Farba'] ;
							$Format[$i]=$riadok2['Format'];
							if($riadok2['Rozmer_sirka']) $Rozmer_sirka[$i]=$riadok2['Rozmer_sirka']; else $Rozmer_sirka[$i]="";
							if($riadok2['Rozmer_vyska']) $Rozmer_vyska[$i]=$riadok2['Rozmer_vyska'];  else $Rozmer_vyska[$i]="";;
							$Velkost[$i]=$riadok2['Velkost'];
							$Zobrazit_rozmery[$i]=$riadok2['Zobrazit_rozmery']; 
							$Zobrazit_velkost[$i]=$riadok2['Zobrazit_velkost']; 
							$Farby[$i]=$riadok2['Farby']; 
							$Dostupne_formaty[$i]=$riadok2['Dostupne_formaty']; 
							$ID_sluzby_na_objednavke[$i]=$riadok2['ID_sluzby_na_objednavke'];
							
							$disabled=disabled_vyber($akcia);
							if($Farby[$i] and $Farby[$i]!="") {
								$Select_farieb[$i]='<select '.$disabled.' class="select_objednavka" name="Farba['.$i.']" id="Sluzba_'.$i.'_farba" ><option value="" '.selected('',$Farba[$i]).'></option>';	
								$MyArray = explode(",", $Farby[$i]); // farby oddelene ciarkami
								for ($j = 0; $j < count($MyArray); $j++) {
									if($MyArray[$j])
										$Select_farieb[$i].='<option value="'.$MyArray[$j].'"'.selected($MyArray[$j],$Farba[$i]).' >'.$MyArray[$j].'</option>';
								}
								$Select_farieb[$i].='</select>';
							}
							
							
							
							if($Dostupne_formaty[$i] and $Dostupne_formaty[$i]!=""){
								$Select_formatov[$i]='<select '.$disabled.' class="select_objednavka" id="Sluzba_'.$i.'_format" name="Format['.$i.']"  ><option value=""'.selected('',$Format[$i]).' ></option>';	
								$MyArray = explode(",", $Dostupne_formaty[$i]); // farby oddelene ciarkami
								
								for ($j = 0; $j < count($MyArray); $j++) {
									if($MyArray[$j])
										$Select_formatov[$i].='<option value="'.$MyArray[$j].'" '.selected($MyArray[$j],$Format[$i]).'>'.$MyArray[$j].'</option>';
								}
								$Select_formatov[$i].='</select>';
							}
										
																					
							$i++;
							
					}
				}
				
						
				?>	
				
								
				<div class="d-flex align-items-start mb-2 w-100">
					<div class="col-md-4 text-center" >Názov služby</div>
					<div class="col-md-1 text-center" >Počet ks</div>
					<div class="col-md-2 text-center" >Farba</div>
					<div class="col-md-2 text-center" >Formát</div>
					<div class="col-md-2 text-center" >Vlastné<br>rozmery</div>
					<div class="col-md-1 text-center" >Veľkosť<br>oblečenia</div>
					
				</div>
				<?php for ($i=0; $i < $pocet_sluzieb_na_objednavke; $i++):?>
						
				<div class="d-flex align-items-start w-100 border-bottom mb-4 pb-4" id="Sluzba_<?php echo $i;?>">
					<div class="col-md-4 text-center"  >
					  <?php 
						
						if($i==0) $required="required"; else $required=""; 
						echo zoznam_sluzieb($dblink,"ID_sluzby[".$i."]",$ID_sluzby[$i],$i,$required,$akcia);?>
					</div>
					<div class="col-md-1 text-center" style="">
						<input type="hidden" name="ID_sluzby_na_objednavke[<?php echo $i;?>]" value="<?php echo $ID_sluzby_na_objednavke[$i];?>">
						<input type="number" id="Sluzba_<?php echo $i;?>_pocet_kusov" name="Pocet_kusov[<?php echo $i;?>]" value="<?php echo $Pocet_kusov[$i];?>" <?php echo disabled_vyber($akcia);?>>
					</div>
					<div class="col-md-2 text-center" id="Farba_<?php echo $i;?>">
						<?php if($Farby[$i] and $Farby[$i]!="" ):
								echo $Select_farieb[$i];
								else: ?>
								<input type="text" id="Sluzba_<?php echo $i;?>_farba" name="Farba[<?php echo $i;?>]" value="<?php echo $Farba[$i];?>" <?php echo disabled_vyber($akcia);?>>
						<?php endif?>		
						
					</div>
					<div class="col-md-2 text-center" id="Format_<?php echo $i;?>">
						<?php if($Dostupne_formaty[$i] and $Dostupne_formaty[$i]!="")
									echo $Select_formatov[$i];
								else 
									echo zoznam_formatov("Format[".$i."]",$Format[$i],$i,$akcia); // formaty A5,A4...
						?>		
					</div>
					
					
					<div id='Sluzba_<?php echo $i;?>_sirka' class="col-md-1 text-center" style="<?php echo display_style($Zobrazit_rozmery[$i]);?>">
						<input type="text" placeholder="Šírka" name="Rozmer_sirka[<?php echo $i;?>]" value="<?php echo $Rozmer_sirka[$i];?>"<?php echo disabled_vyber($akcia);?>>mm 
					</div>
					<div id='Sluzba_<?php echo $i;?>_vyska' class="col-md-1 text-center" style="<?php echo display_style($Zobrazit_rozmery[$i]);?>">
						<input type="text" placeholder="Výška" name="Rozmer_vyska[<?php echo $i;?>]" value="<?php echo $Rozmer_vyska[$i];?>"<?php echo disabled_vyber($akcia);?>>mm
					</div>
					<div id='Pomlcka1_<?php echo $i;?>' class="col-md-2 text-center" style="<?php echo display_style(!$Zobrazit_rozmery[$i]);?>">
						<div style="font-size:1.5rem;font-weight:bold;margin-top:-5px">-</div>
					</div>
					
					<div id='Sluzba_<?php echo $i;?>_velkost' class="col-md-1 text-center" style="<?php echo display_style($Zobrazit_velkost[$i]);?>">
						
						<?php echo zoznam_velkosti_oblecenia("Velkost[".$i."]",$Velkost[$i],$akcia); ?>
						
					</div>
					<div id='Pomlcka2_<?php echo $i;?>' class="col-md-1 text-center" style="<?php echo display_style(!$Zobrazit_velkost[$i]);?>">
						<div style="font-size:1.5rem;font-weight:bold;margin-top:-5px">-</div>
					</div>
					
				</div>	
				<?php endfor; ?> 	
					
					
			</div>
			
		<div id="txtHint"></div>	
		<!-- END SLUŽBY NA OBJEDNÁVKE -->		
	</td>
	<tr><td colspan="2"><br></td></tr>
	<tr><td style = "vertical-align:top !important;">Poznámka:</td><td><textarea type="text" name="Obj_Poznamka" cols="25" rows="5" <?php echo disabled_vyber($akcia); ?>><?php echo $Poznamka;?></textarea></td></tr>
	<?php   if($ID_objednavky):?>
	<tr><td colspan="2"><b>Vložené prílohy:</b>
		<!-- PRILOHY ZOZNAM-->		
		<?php	$sql = "Select * FROM prilohy where ID_objednavky=$ID_objednavky"; 
				$vysledok=mysqli_query($dblink,$sql);
				$num_rows = zisti_pocet_riadkov($dblink,$sql);
				if (!$vysledok):
					echo "Doslo k chybe pri vyhladani priloh !";
					
				elseif($num_rows!=0):	?>
					<div class="mt-2 mb-2 w-100">
						<?php 
							$s=0; // poradie prilohy
							while($riadok=mysqli_fetch_assoc($vysledok)): 
								$nazov_suboru=$riadok["Nazov_suboru"];
							?>
								<div class="col-md-4" id="Subor_<?php echo $s;?>" style="display:block; float:left;">
									<?php echo '<a href="prilohy/'.$nazov_suboru.'" title="Otvoriť" target="_blank">'.'<div style="float:left">'.$nazov_suboru.'</div></a>';
									
									 if($akcia!="preview") 
										 echo ' <image type="button" value="" src="images/trash-can-solid.png" title="Zmazať prílohu" style="width:16px;float:left;margin-left:0.8rem;margin-top:0.3rem;" onclick="zmazat_prilohu('.$s.',\''.$nazov_suboru.'\')" >';
									 ?>
								</div>
								<input type="text" value="" id="Zmazat_prilohu_<?php echo $s;?>" name="Zmazat_prilohu_<?php echo $s;?>" style="display:none"/>
								
							<?php $s++; endwhile; ?>
						<input type="text" value="" id="Zmazat_prilohy" name="Zmazat_prilohy" style="display:none"/>
						
					</div>
					
			<?php endif; /* end hladania */ ?>
			<!-- END PRILOHY ZOZNAM-->	
		</td></tr>
		<tr><td colspan="2"><br></td></tr>
	<?php endif; /* end if ID objednavky */ ?>
	
	<?php if($akcia !='preview'):?>
	<tr><td colspan="2"><label for="files" class="mb-2"><b>Prílohy:</b></label><br>Vyberte jeden alebo viac súborov:<br><br>
		<input class="mb-2" type="file" id="userfile" name="userfile1" ><br>
		<input class="mb-2" type="file" id="userfile" name="userfile2" ><br>
		<input class="mb-2" type="file" id="userfile" name="userfile3" ><br>
		<input class="mb-2" type="file" id="userfile" name="userfile4" ><br>
		<input class="mb-2" type="file" id="userfile" name="userfile5" >
		<br><br></td></tr><br><br>
		<tr><td colspan="2"><br></td></tr>
	<?php endif ?>	
	
	<tr><td colspan="2">
	
	<input type="hidden" name="akcia" value="<?php echo $akcia;?>">
	<input type="hidden" name="ID_objednavky" value="<?php echo $ID_objednavky;?>">

	<?php if($akcia !='preview'):?>
	<input style="float:left;" type="submit" value="Uložiť" form="form1">
	<?php endif;	?>
	<input style="float:left;" type="submit" name="back" class="button2" form="back" value="Späť">
	<!-- musime dat tento button do form kvoli required kontrolam -->
	</td></tr>
</table>


</form>

<form id="back" action="zmena_objednavky.php" method="POST"></form>
</p>
<?php
mysqli_close($dblink); // odpojit sa z DB
//phpinfo();
?>
</body>
</html>