<?php
$mysql_server="127.0.0.1"; // localhost, 127.0.0.1
$mysql_user="root"; // login
$mysql_password=""; // heslo
$mysql_port="3306"; // port
$mysql_db="reklamne_studio_michal_pacesa"; // meno databazy
$dir_name="reklamnestudio"; // meno adresara v ktorom je system (hlavny web)

$limit=5; 
$pocet_sluzieb_na_objednavke=5;// kolko riadkov sluzieb bude v objednavke

$stav = 0; // ci vypisovat php errory
@ini_set('display_errors', $stav);
@ini_set('display_startup_errors;',$stav);
@ini_set('display_notice;',$stav);
@ini_set('display_warning;',$stav);

?>