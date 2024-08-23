<?php session_start(); ?>
<!DOCTYPE html>
<html lang="sk">
<head>
<?php include "head.php" ?>
</head>

<body>
   <!--Main Navigation-->
	<header>
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
	</header> 

	<main>
	<div class="container text-black">
		<h2 class="mb-2">Darčekové predmety</h2>
		<div class="row">
			<div class="col-15">
				Rozmýšlate čím obdarovať svojich blízkych a priateľov? Chcete predávať zaujímavé suveníry alebo rozdávať reklamné predmety s vašou značkou? Naša firma sa zameriava na výrobu týchto darčekových predmetov:<br>
				<ul>
					<li>Výroba a potlač odznakov</li>
					<li>Výroba a potlač magnetiek</li>
					<li>Potlač medailí</li>
					<li>Potlač darčekových hrnčekov</li>
					<li>Výroba a potlač sviečok</li>
					<li>Tlač samolepiek, napr. na darčekové fľaše</li>
					<li>Tlač fotoobrazov na papier alebo na plátno s možnosťou zaramovania</li>
					<li>Gravírovanie do dreva a iných materiálov</li>
				</ul>	
			</div>

				
		</div>
		<hr>
		<div class="row">
			<h3 class="mb-2">Ukážky našich prác</h2><br><br><br>
		</div>	
		<div class="row">
			<div class="col-4 text-center">
				<img  src="obr/potlac-hrncekov-banska-stiavnica.jpg" alt="Potlač hrnčekov Banská Štiavnica" class="height-250 mb-3 me-3"><br>
				Potlač keramických hrnčekov
			</div>	
			<div class="col-4 text-center">
				<img  src="obr/potlac-plechovych-hrncekov.jpg" alt="Potlač plechových hrnčekov Banská Štiavnica" class="height-250 mb-3 "><br>
				Potlač plechových hrnčekov
			</div>	
			<div class="col-4 text-center">
				<img  src="obr/potlac-sviecok.jpg" alt="Potlač sviečok" class="height-250 mb-3 me-3"><br>
				Potlač sviečok
			</div>	
				
		</div>	<br>		
		<br>	
		<div class="row">
			<div class="col-3 text-center mb-3 ">
				<img src="obr/gravirovanie-do-dreva-1.jpg" alt="Gravírovanie do dreva" class="height-250 mb-3 me-3"><br>
				Gravírovanie do dreva
			</div>	
			<div class="col-3 text-center mb-3">
				<img src="obr/gravirovanie-do-dreva-2.jpg" alt="Gravírovanie do dreva" class="height-250 mb-3 "><br>
				Gravírovanie do dreva
			</div>
			<div class="col-3 text-center mb-3 "></div><div class="col-3 text-center mb-3 "></div>			
		</div>	
	</div>	
	</main>
	
	
	<div style="height:3rem"> </div>
	<footer>
		<?php include "footer.php" ?>
	</footer>
</body>
</html>