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
			<h2 class="mb-2">Tlač</h2>
			<div class="row">
				<div class="col-6">
					Veľkoplošná tlač na kvalitnej 8 farebnej tlačiarni môže byť v rozmeroch do 1,6 m. Vytlačíme vaše dokumenty, fotografie či obrázky z vašich súborov. <br>
					Ponúkame vám:
					<ul>
						<li>Tlač vizitiek</li>
						<li>Tlač letákov</li>
						<li>Tlač plagátov</li>
						<li>Tlač PVC samolepiek<br>
							&nbsp;&nbsp;&nbsp;&nbsp;Samolepky je možné orezať do akéhokoľvek tvaru. Určené aj do vonkajšieho 
							&nbsp;&nbsp;&nbsp;&nbsp;prostredia PVC samolepky vyrábame klasické aj zrkadlové, lepené na vnútornú
							&nbsp;&nbsp;&nbsp;&nbsp;stranu skla. Je mmožné ich použiť aj na reklamné polepy áut.
						</li>	
						<li>	
							Tlač dokumentov, brožúr vrátane väzby. <br>
							&nbsp;&nbsp;&nbsp;&nbsp;Poskytujeme hrebeňovú, krúžkovú a tepelnú väzbu, laminovanie.
						</li>
						<li>Tlač vašich fotografií</li>
						<li>
							Výroba fotoobrazov z vašich fotiek<br>
						&nbsp;&nbsp;&nbsp;&nbsp;Kvalitná tlač na maliarske plátno alebo na fotopapier s možnosťou zaramovania.		
						</li>
						<li>Výroba reklamných a informačných tabúľ</li>   
						<li>Kopírovacie služby</li>   
					</ul>
				</div>
				<div class="col-6">
					<img src="obr/ploter-tlaciaren-roland.jpg" alt="Tlačiareň ploter Roland" style="width:40rem;"class="mb-3 ms-5"><br>
				</div>
				
			</div>
			<hr>
			<div class="row">
				<h3 class="mb-2">Ukážky našich prác</h3>
				<br>
				<br>
				<br>
			</div>	
			<div class="row">
				<div class="col-4 text-center mr-3">
					<img  src="obr/tlac-banska-stiavnica.jpg" alt="Tlač banneru pre mesto Banská Štiavnica" class="height-250 mb-3 "><br>
					Tlač banneru 
				</div>	
				<div class="col-4 text-center mr-3">
					<img  src="obr/tlac-textu-roland.jpg" alt="Tlač nálepiek - svadobný motív" class="height-250 mb-3 "><br>
					Tlač banneru
				</div>
				<div class="col-4 text-center">
					<img src="obr/samolepka-na-auto.jpg" alt="Polep auta"class="height-250 mb-3"><br>
					Tlač samolepky na auto
				</div>	
				
			</div>	<br>
			<div class="row">
				<div class="col-5 text-center">
					<img src="obr/tlac-samolepiek.jpg" alt="Tlač samolepiek pre mesto Banská Štiavnica" class="height-250 mb-3"><br>
					Tlač samolepiek pre mestský úrad
				</div>	
				<div class="col-5 text-center">
					<img src="obr/otvaracie-hodiny-na-dvere.jpg" alt="Tlač samolepiek otváracie hodiny" class="height-250 mb-3"><br>
					Tlač samolepky ma dvere - otváracie hodiny 
				</div>	
			</div>	
		</div>	
	
	</main>
	
	<div style="height:3rem"> </div>
	<footer>
		<?php include "footer.php" ?>
	</footer>
	
</body>
</html>