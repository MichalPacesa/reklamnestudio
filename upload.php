<?php


@ini_set('display_errors',1);
@ini_set('display_startup_errors;',1);

$typsuboru=$_FILES['userfile'.$j]['type']; echo $typsuboru; 

if ($_FILES['userfile'.$j]['error'] > 0)
  {
    echo 'Problém: ';
    switch ($_FILES['userfile'.$j]['error'])
    {
      case 1:  echo 'Súbor presiahol veľkosť upload_max_filesize';  break;
      case 2:  echo 'Súbor presiahol veľkosť max_file_size';  break;
      case 3:  echo 'Súbor bol nahraný len čiastočne';  break;
      case 4:  echo 'Súbor sa nenahral';  break;
    }

    //exit;
  }

// dame soubor tam, kde ho chceme mat
$typsuboru=$_FILES['userfile'.$j]['type'];
$nazovsuboru=$_FILES['userfile'.$j]['name']; 

$nazovsuboru=zrus_diakritiku($nazovsuboru);//odstranime diakritiku medzery a velke pismena


$r=rand(1000, 9999);
$nazovsuboru=$r.'-'.$nazovsuboru;  // doplnime nahodne cislo do nazvu ... 
$subor="admin/prilohy/".$nazovsuboru;
   

if (!is_uploaded_file($_FILES['userfile'.$j]['tmp_name'])){
	echo 'Problém: Súbor sa nepodarilo odoslať...';
    exit;
	}
	
	
if (!move_uploaded_file($_FILES['userfile'.$j]['tmp_name'], $subor)){
    echo 'Problém: Súbor sa nepodarilo zapísať...';
    exit;
    }
	
if (!kontrolasuboru($typsuboru,$_FILES['userfile'.$j]['tmp_name'])){
	$hlaska .="<p class='oznam' style='color:red!important'>Súbor môže mať len tento formát: jpg, gif, png,bmp,pdf,xls,doc</p>";
	header('Location: index.php');
}	
else{
	$hlaska.='<p class="oznam"> Súbor '.$nazovsuboru.' bol úspešne odoslaný.</p>';
	if($ID)
		$ID_objednavky=$ID;
	else 
		$ID_objednavky=$ID_posledneho_zaznamu;	
	
	$sql = "INSERT INTO prilohy (Nazov_suboru,ID_objednavky) VALUES ('$nazovsuboru','$ID_objednavky')";
	$vysledok = mysqli_query($dblink, $sql); 
	if (!$vysledok)
		{ 
		$hlaska .= '<p class="oznam">Chyba pri vkladani prilohy!</p>';
		}
	}

?>

