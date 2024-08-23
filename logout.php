<?php
session_start();

unset($_SESSION['Login_ID_pouzivatela_web']);
unset($_SESSION['Login_Prihlasovacie_meno_web']); 
unset($_SESSION['Login_Meno_Priezvisko_web']);
header('Location: index.php');
?>

