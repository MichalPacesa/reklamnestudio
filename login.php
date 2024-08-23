<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<?php
include "head.php";
include "config.php";
include "navbar.php";
?>
</head>
<body>

<div class="sirka_login text-black" style="margin-left:19.4rem;">
<h2 style="margin-top:1rem;">Prihlásenie</h2><br>

<?php
$udaje = $_GET["udaje"];
if($udaje == "nespravne"){
$hlaska = "Nesprávne prihlasovacie meno alebo heslo";
echo "<p class='oznam_login' style='text-align:left;'>".$hlaska."</p>";
}
?>
<form id="login_form" action="zmena_konta.php" method="POST">

<table class="login" border="1" align="left" cellpadding="0" cellspacing="0">
<tr><td>Prihlasovacie meno:</td><td style="width: 65%"><input required type="text" name="Login_Prihlasovacie_meno" value=""/></br></td></tr>
<tr><td>Heslo:</td><td><input required type="password" name="Login_Prihlasovacie_heslo" value="" id="password"/> 
<input type="checkbox" class="ZobrazitHeslo" onclick="ShowPassword()"><span style="vertical-align:middle;"/>Zobraziť heslo</span></td></tr>
<tr><td colspan="2">
<input type="submit" form="login_form" value="Prihlásiť" style="float:right;margin-right:1rem;">
</td></tr>
</table>
</form>
</div>
</body>
</html>