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
			<h2 class="mb-2">Potlač</h2>
			<div class="row">
				<div class="col-6">
					Potlač tričiek a iných textilných materiálov vykonávame na DTF tlačiarni. Nová technológia tlače DTF (Digital transfer film) umožňuje tlačiť vzory na špeciálny transférový film a potom 
					naniesť na bavlnu, polyester a iné textílie.Vzniknutá potlač je veľmi kvalitná a aj napriek naťahovaniu potlačenej plochy ostáva nepoškodená. Taktiež je odolná voči praniu.<br>
					Ponúkame vám:
					<ul>
						<li>Potlač bio bavlnených tričiek</li>
						<li>Potlač tričiek zo zmesi bavlna-polyester</li>
						<li>Potlač športových tričiek z polyesteru</li>
						<li>Potlač textílií z bavlny (bavlnené tašky, návliečky, textilné obrúsky...)</li>
					</ul>
					Potlač robíme podľa vášho pripraveného vzoru, ale pomôžeme vám aj s grafickým návrhom vašej potlače. Tričko či inú textíliu si môžete priniesť vlastnú, ale ponúkame tiež na objednávku 
					tričká z kvalitného materiálu rôznych veľkostí.
				</div>
				<div class="col-6">
						<img  src="obr/potlac-bavlnenych-triciek.jpg" alt="Potlač tričiek DTF" class="width-500 mb-3 "><br>
				</div>
			</div>
			<hr>
			<div class="row">
				<h3 class="mb-2">Ukážky našich prác</h2><br><br><br>
			</div>	
			<div class="row">
				<div class="col-4 text-center   ">
					<img src="obr/potlac-nasiviek.jpg" alt="Potlač nášiviek" class="height-250 mb-3 me-3"><br>
					Potlač nášiviek
				</div>	
				<div class="col-4 text-center ">
					<img src="obr/potlac-triciek-2.jpg" alt="Potlač tričiek" class="height-250 mb-3 me-3"><br>
					Potlač tričiek
				</div>	
				<div class="col-4 text-center ">
					<img src="obr/potlac-tabul.jpg" alt="Potlač tričiek" class="height-250 mb-3 "><br>
					Potlač tabúl
				</div>	
				
			</div><br><br>	
			<div class="row">	
			<div class="col-4 text-center ">
				<img src="obr/potlac-odznakov.jpg" alt="Potlač odznakov"class="height-250 mb-3 me-3"><br>
				Potlač odznakov
			</div>	
			<div class="col-4 text-center">
				<img src="obr/potlac-priveskov.jpg" alt="Potlač plieškov"class="height-250 mb-3 "><br>
				Potlač príveskov
			</div>	
			
		</div><br>
			
		</div>	
	</main>
	
	<div style="height:3rem"> </div>
	<footer>
		<?php include "footer.php" ?>
	</footer>
	
</body>
</html>