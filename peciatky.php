<?php session_start(); ?>
<!DOCTYPE html>
<html lang="sk">
<head>
<?php include 'head.php';?>
</head>

<body>
		
    <!-- Navbar -->
<?php 
include "config.php";
if($_SESSION['Login_Prihlasovacie_meno_web'])
{
	include "navbar_log.php"; 
}
else include "navbar.php";
?>
	<!-- Navbar -->
	
	
		<div class="container text-black">
			<h2 class="mb-3">Pečiatky</h2>
			<div class="container">		
			<br>
			Vyrábame pečiatky <b>COLOP Printer</b>, ktoré je možné vybrať v rôznych variantoch, v závislosti od veľkosti odtlačku - počtu riadkov (3 až 6 riadkov), ďalej farba držadla a obalu (napr. biela s červeným obalom, čierna so zeleným obalom) a dá sa vybrať aj farba odtlačku (čierna, modrá, červená...)
			Ponúkame tri základné modely a to vo verzii štandard, Microban a vo verzii Greeen Line. <b>Pečiatka COLOP Printer štandard</b> je samonamáčacia pečiatka pre spoľahlivé, pravidelné používanie. Pečiatka <b>COLOP Printer Microban</b> má antibakteriálnu ochranu na držadle pečiatky, ktorá chráni pred šírením baktérií na pečiatke. <b>Pečiatky COLOP Printer Green Line</b> sú oproti štandardným pečiatkam vyrobené z prevažne  obnoviteľných  a recyklovateľných materiálov. 
			<br>
			<br>
			<br>
			</div>
			<div class="row">
				<div class="col-3 text-center">
					<img src="obr/peciatka-cervena-COLOP-Printer-10.jpg" alt="COLOP-Printer-10"  class="width-200">
					<br>	
					pečiatka COLOP Printer 10<br>	
					3 riadky 27x10 mm<br>	
				</div>
				<div class="col-3 text-center">
					<img  src="obr/peciatka-cervena-COLOP-Printer-20.jpg" alt="COLOP-Printer-20" class="width-200">
					<br>	
					pečiatka COLOP Printer 20<br>	
					4 riadky 38x14 mm<br>	
				</div>	
				<div class="col-3 text-center">
					<img  src="obr/peciatka-cervena-COLOP-Printer-30.jpg" alt="COLOP-Printer-30" class="width-200">
					<br>	
					pečiatka COLOP Printer 30<br>	
					5 riadkov 47x18 mm<br>	
				</div>	
				<div class="col-3 text-center">
					<img  src="obr/peciatka-cervena-COLOP-Printer-40.jpg" alt="COLOP-Printer-40" class="width-200">
					<br>	
					pečiatka COLOP Printer 40<br>	
					6 riadkov 59x18 mm<br>	
				</div>
			</div>	
			<br>
			<br>
			<div class="row">
				<div class="col-3 text-center"></div>
				<div class="col-3 text-center">
					<img  src="obr/peciatka-zelena-COLOP-Printer-20.jpg" alt="COLOP-Printer-20" class="width-200">
					<br>	
					pečiatka COLOP Printer 20<br>	
					Green Line<br>	
					4 riadky 38x14 mm<br>	
				</div>	
				<div class="col-3 text-center">
					<img  src="obr/peciatka-zelena-COLOP-Printer-30.jpg" alt="COLOP-Printer-30" class="width-200">
					<br>	
					pečiatka COLOP Printer 30<br>	
					Green Line<br>	
					5 riadkov 47x18 mm<br>	
				</div>	
				<div class="col-3 text-center">
					<img  src="obr/peciatka-zelena-COLOP-Printer-40.jpg" alt="COLOP-Printer-40" class="width-200">
					<br>	
					pečiatka COLOP Printer 40<br>	
					Green Line<br>	
					6 riadkov 59x18 mm<br>	
				</div>
			</div>
		</div>	
		<div style="height:3rem"> </div>
		<footer>
		<?php include "footer.php" ?>
		</footer>
				
