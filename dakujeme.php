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
			<div class="row">
				<div style="font-size:20px;" class="col-6">
					<br>
					<p style="font-size:30px;">Ďakujeme za Vašu objednávku.</p>
					<p class="link">
					<br>
					<a href="index.php">Späť na hlavnú stránku</a>
					<br>
					</p>
					<p style="margin-top:30rem;"></p>
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