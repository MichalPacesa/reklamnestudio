
<nav class="navbar navbar-expand-lg navbar-light   d-lg-block" style="background:white">
	<!-- aby boli logo a menu vedla seba-->
	
	<div class="container d-flex align-items-left justify-content-start text-left ">
		<!--  logo -->
		<span class="logo" style="line-height:110%">
				<a href="index.php" style="white-space: nowrap;"> Reklamné štúdio </a><br>
				<span class="logo_a">
					<a href="index.php"> Administrácia </a>
				</span>
		</span>
		
     <!-- ikona menu pre mobily -->
    <button id="ikona-menu" class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarExample01" 
          aria-controls="navbarExample01" aria-expanded="false" aria-label="Toggle navigation" onclick="menuPreMobily();">
          <i class="fas fa-bars"></i>
	</button>
	<!-- hlavne menu  -->
    <div class="navbar-collapse collapse" id="navbarExample01">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0" style = "margin-left:4rem; font-size:1.25rem;white-space: nowrap;z-index:2000">
            <?php if(ZistiPrava("Zobraz_objednavky",$dblink) == 1):  ?>
			<li class="nav-item active">
              <a class="nav-link" aria-current="page" href="index.php">Objednávky</a>
            </li>
			<?php endif;  ?>
			
            <?php if(ZistiPrava("Zobraz_zakaznikov",$dblink) == 1):  ?>
			<li class="nav-item">
              <a class="nav-link" href="index_zakaznik.php">Zákazníci</a>
			</li>
			<?php endif;  ?>
			
			<?php if(ZistiPrava("Zobraz_zamestnancov",$dblink) == 1):  ?>
			<li class="nav-item">
              <a class="nav-link" href="index_zamestnanec.php">Zamestnanci</a>
			</li>  
			<?php endif;  ?>
			
			<?php if(ZistiPrava("Zobraz_cenove_ponuky",$dblink) == 1):  ?>
			<li class="nav-item">
              <a class="nav-link" href="index_cenova_ponuka.php">Cenové ponuky</a>
			</li>
			<?php endif;  ?>
			
			<?php if(ZistiPrava("Zobraz_sluzby",$dblink) == 1):  ?>
			<li class="nav-item">
              <a class="nav-link" href="index_sluzba.php">Služby</a>
			</li>
			<?php endif;  ?>
			
		  </ul>

          <ul id="vedlajsie-menu" class="navbar-nav d-flex flex-row" style="white-space: nowrap;z-index:1000">
            <!-- Icons -->
			<?php if($_SESSION['Login_Meno_Priezvisko']): ?>
			<li class="nav-item me-3 me-lg-0 mb-10">
              <a class="nav-link" rel="nofollow">
                Ste prihlásení ako: <b> <?php echo $_SESSION['Login_Meno_Priezvisko']; ?>
              </a>
            </li>
			<li class="nav-item me-3 me-lg-0 mb-10">
              <a class="nav-link" href="logout.php" rel="nofollow"> 
                Odhlásiť sa
              </a>
            </li>
          <?php endif; ?>
          </ul>
    </div>
		
</div>
</nav>
