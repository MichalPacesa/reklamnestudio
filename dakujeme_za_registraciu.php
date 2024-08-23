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
					<p style="font-size:30px;">Ďakujeme za Vašu registráciu.</p><br>
					<p class="link">
					<a href="login.php">Prihlásiť sa</a>
					</p>
					<p class="link">
					<a href="index.php">Späť na hlavnú stránku</a>
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