<?php session_start(); ?>
<!DOCTYPE html>

<html>
<head>
	<?php
	include "head.php";
	?>
	
	
</head>
<body>

<?php include "config.php";

if($_SESSION['Login_Prihlasovacie_meno_web'])
{
	include "navbar_log.php"; 
}
else include "navbar.php";

?>
	
   <!-- Navigation-->
	<header>
		<!-- Navbar -->
		<?php 
		
		 
		?>
		<!-- Navbar -->

	</header>
  <!-- end Navigation-->

  <!-- Main  -->
	<main class="mt-3">
		<div class="container">
		<!--Section: Content-->
		<section>
			<div class="row">
				<div class="col-md-6 gx-5 mb-4">
					<div class="bg-image hover-overlay ripple shadow-2-strong rounded-5" data-mdb-ripple-color="light">
						<img src="obr/ploter-tlaciaren-roland-2.jpg" class="img-fluid" />
						<a href="tlac.php">
							<div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
						</a>
					</div>
				</div>
	
				<div class="col-md-6 gx-5 mb-4">

					<h4><strong>Vitajte na stránkach reklamného štúdia</strong></h4>
					<p class="text-muted">
						Sme reklamné štúdio.
						Postaráme sa o Vašu reklamu a  firemnú identitu od grafického návrhu firemného loga, tlač hlavičkového papiera, vizitiek, tlač samolepiek, plagátov a reklamných pútačov či informačných tabúľ až po výrobu pečiatok. Robíme tiež potlač textilu a iných materiálov. 
						Vytlačíme vaše dokumenty, poskytujeme laminovanie, hrebeňovú, krúžkovú a tepelnú väzbu a kopírovacie služby. Vytlačíme vaše fotografie a fotoobrazy.
					</p>
					<br>
					<a class="btn form-control btn-lg" style="width:10rem;" href="objednavka.php" role="button" rel="nofollow">Objednávka</a>
				</div>
			</div>
      </section>
      <!--Section: Content-->

      <hr class="my-5" />

      <!--Section: Content-->
	  
      <section class="text-center">
		<a name="reklama"></a>
        <h4 class="mb-5"><strong>Reklamné služby</strong></h4>

        <div class="row">
          <div class="col-lg-4 col-md-6 mb-4">
            <div class="card">
              <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                <img src="obr/tlac-banska-stiavnica.jpg" class="img-fluid" style="height:26rem" />  <!--TLAČ-->
                <a href="tlac.php">
                  <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                </a>
              </div>
              <div class="card-body">
                <h5 class="card-title">Tlač</h5>
                <p class="card-text">
                  V našej firme ponúkame tlač hlavičkového papiera, vizitiek, tlač samolepiek, plagátov a reklamných pútačov či informačných tabúľ
				  <br>
				  <br>
                </p>
                <a href="tlac.php" class="btn btn-primary viac">Viac</a>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 mb-4">
            <div class="card">
              <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
				<img src="obr/potlac-bavlnenych-triciek.jpg" class="img-fluid" style="height:26rem"/> <!--POTLAČ-->
				
                <a href="potlac.php">
                  <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                </a>
              </div>
              <div class="card-body">
                <h5 class="card-title">Potlač</h5>
                <p class="card-text">
                  Nové tričká u nás na predajni pánske, dámske rôzne veľkosti..
				  <br>
				  <br>
				  <br>
                </p>
                <a href="potlac.php" class="btn btn-primary viac">Viac</a>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 mb-4">
            <div class="card">
              <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                <img src="obr/peciatky-banska-stiavnica.png" class="img-fluid" style="height:26rem"/> <!--PEČIATKY-->
                <a href="peciatky.php">
                  <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                </a>
              </div>
              <div class="card-body">
                <h5 class="card-title">Pečiatky</h5>
                <p class="card-text">
                  V našej firme vyrábame rôzne druhy pečiatok pre vašu firmu.
				  <br>
				  <br>
				  <br>
                </p>
                <a href="peciatky.php" class="btn btn-primary viac">Viac</a>
              </div>
            </div>
          </div>
        </div>
      </section>
	  <!--Section: Content-->

    </div>
	</main>
	<!-- end  Main -->
	<br>
	<footer>	
		<?php include "footer.php" ?>
	</footer>	
</body>
</html>

