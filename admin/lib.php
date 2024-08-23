<?php

function zisti_pocet_riadkov($db_link,$sql)
{
	$vysledok = mysqli_query($db_link, $sql);
	$riadok = mysqli_fetch_row($vysledok);
	return $riadok[0];
}

function selected($nazovprem,$hodnota)
{
 // funkcia pre select vo formulari
 if ($nazovprem == $hodnota)
 return 'selected';
}
function disabled($nazovprem)
{
 // funkcia pre preview vo formulari
 // vracia slovo disabled ak ide o preview
 if ($nazovprem == 'preview' OR $nazovprem == 'vyber')
	return 'disabled';
}

function disabled_vyber($nazovprem) 
{
 if ($nazovprem == 'preview')
	return 'disabled';

 if ($nazovprem == 'vyber')
	return '';
}

function checked($premenna,$hodnota)
{
// funkcia pre checkbox vo formulari
if($premenna==$hodnota)
 return 'checked';
else 
 return ' ';
 
}

function zoznam_sluzieb ($dblink,$name_selectu,$ID_sluzby,$poradie,$req,$akcia){
	// $dblink - povinne, conect databazy
	//  name_selectu - nepovinne,  nazov html selectu (text)
	//  ID_sluzby - nepovinne, ID sluzby ktora sa ma nastavit selected  (int)
	// poradie - povinne - poradie riadku v zozname sluzieb na objednavke (int)
	// req - nepovinne - ci je select povinny (text alebo 1/0)
	//echo 'id sluzby:'.$ID_sluzby; 
	$disabled=disabled_vyber($akcia);
	$sql_sluzba = "Select * FROM sluzba where zobrazovat_sluzbu=1 order by Nazov ASC;";  // vsetky sluzby s ich  nazvami
	
	$vysledok_sluzba=mysqli_query($dblink,$sql_sluzba);
	if (!$vysledok_sluzba){
			echo "Doslo k chybe pri vyhľadaní údajov o službách !";
			exit;
		}
	if(!$name_selectu) $name_selectu='ID_sluzby';
	if(!$ID_sluzby) $ID_sluzby="0";
	if($req) {
		$hviezdicka='<span class="hviezdicka">*</span>';
	}
	else {
		$hviezdicka='';
	}
	if(!$req)			
		echo '<select '.$disabled.' onChange="vyber_sluzbu(this.value,'.$poradie.');" name="'.$name_selectu.'" id="'.$name_selectu.'"  style="width:25rem;height:30px" >';
	else  
		echo '<select '.$disabled.' required onChange="vyber_sluzbu(this.value,'.$poradie.');" name="'.$name_selectu.'" id="'.$name_selectu.'"  style="width:25rem;height:30px" >';
	
	echo '<option value="" '.selected('0',$ID_sluzby).' ></option>';    // prazdny 1. option len ked nie je required 
	
	while($riadok_sluzba=mysqli_fetch_assoc($vysledok_sluzba)):
		echo '<option  value="'.$riadok_sluzba["ID_sluzby"].'" '.selected($riadok_sluzba["ID_sluzby"],$ID_sluzby).'>'.$riadok_sluzba['Nazov'].'</option>';
	endwhile;   
	echo '</select>'.$hviezdicka;
}
function zoznam_formatov ($name_selectu,$Format_selected, $poradie,$akcia){
	//  name_selectu - nepovinne,  nazov html selectu (text)
	//  Format_selected - nepovinne, Format ktory sa ma nastavit selected (text)
	// poradie - poradie riadku v zozname sluzieb na objednavke (int)
	$disabled=disabled_vyber($akcia);
	if(!$name_selectu) $name_selectu='Format['.$poradie.']';
	if(!$Format_selected) $Format_selected='';
		
	echo '<select '.$disabled.' class="select_objednavka" name="'.$name_selectu.'" id="Sluzba_'.$poradie.'_format">';
	echo '<option value="" '.selected("",$Format_selected).'></option>'; 
	echo '<option  value="A5" '. selected("A5",$Format_selected).' >A5</option>'; 
	echo '<option  value="A4" '. selected("A4",$Format_selected).' >A4</option>'; 
	echo '<option  value="A3" '. selected("A3",$Format_selected).' >A3</option>'; 
	echo '<option  value="A2" '. selected("A2",$Format_selected).' >A2</option>'; 
	echo '<option  value="A1" '. selected("A1",$Format_selected).' >A1</option>'; 
	echo '</select>';
}

function zoznam_velkosti_oblecenia ($name_selectu,$velkost_selected,$akcia){
	//  name_selectu - nepovinne,  nazov html selectu (text)
	//  ID_velkosti_selected - nepovinne, velkost ktora sa ma nastavit selected (text)
	$disabled=disabled_vyber($akcia);
	if(!$name_selectu) $name_selectu='Velkost';
	if(!$velkost_selected) $velkost_selected='';

	echo '<select '.$disabled.' name="'.$name_selectu.'" class="select_height">';
	echo '<option value="" '.selected('',$velkost_selected).'> </option>'; 
	echo '<option value="XS" '.selected('XS',$velkost_selected).'>XS</option>'; 
	echo '<option value="S" '.selected('S',$velkost_selected).'>S</option>'; 
	echo '<option value="M" '.selected('M',$velkost_selected).'>M</option>'; 
	echo '<option value="L" '.selected('L',$velkost_selected).'>L</option>'; 
	echo '<option value="XL" '.selected('XL',$velkost_selected).'>XL</option>'; 
	echo '<option value="XXL" '.selected('XXL',$velkost_selected).'>XXL</option>';
	echo '</select>';
}

function display_style($Zobrazit){
	$return='display:block';
	if(!$Zobrazit) 
		$return='display:none';
	return $return;
	
	}
	
function zrus_diakritiku($text)
    {
		//echo 'zrus diakritiku text: '.$text;
		$return = Str_Replace(
		Array("á","č","ď","é","ě","í","ľ","ň","ó","ř","š","ť","ú","ů","ý","ž","ô","ú","ä","Á","Č","Ď","É","Ě","Í","Ľ","Ň","Ó","Ř","Š","Ť","Ú","Ů","Ý","Ž") ,
		Array("a","c","d","e","e","i","l","n","o","r","s","t","u","u","y","z","o","u","a","A","C","D","E","E","I","L","N","O","R","S","T","U","U","Y","Z") ,
		$text);
		$return = Str_Replace(Array(" ", "_"), "-", $return); //nahradí mezery a podtržítka pomlčkami
		$return = Str_Replace(Array("(",")","!",",","\"","'"), "", $return); //odstraní ()!,"'
		$return = StrToLower($return); //velké písmena nahradí malými.
		return $return;

	}



function kontrolasuboru($typsuboru,$nazovsuboru)	 
{
    if($typsuboru=='application/msword' or $typsuboru=='application/vnd.ms-excel' or $typsuboru=='application/pdf' or $typsuboru=='text/html' or $typsuboru=="image/jpeg"
		or $typsuboru=='image/jpeg' or $typsuboru=='image/gif' or $typsuboru=='image/png' or $typsuboru=='image/bmp' )
	   {
	   return 1; 
       }
   	else 
             
	   return 0;
  
}

function ZistiPrava($Nazov_prava,$dblink)
{
	$ID_prava = $_SESSION['Login_ID_prava'];
	if(!$ID_prava)
	{
	return 0;
	} 
	
	$sql = "SELECT $Nazov_prava FROM prava WHERE ID_prava = $ID_prava";
	$vysledok = mysqli_query($dblink, $sql);
	$riadok = mysqli_fetch_assoc($vysledok);
	return $riadok[$Nazov_prava];	
}  
// END LIB
?>
