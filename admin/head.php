
<title> Reklamné štúdio - Administrácia </title>
<meta charset="UTF-8">

<script src="mdbootstrap/js/jquery-3.6.0.min.js"></script>
<script src="mdbootstrap/js/jquery-ui.js"></script>

<script>
function OnChangeLogin(str,oldname) {
	
	if (str == "") {
		document.getElementById("txtHint").innerHTML = "<br>";
		return true;
	}
	else if (str == oldname) {
		document.getElementById("txtHint").innerHTML = "<br>";
		
		return true;
	}	
	else  {
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("txtHint").innerHTML = this.responseText;
				//alert(document.getElementById('Result').value)
				if(document.getElementById('Result') && document.getElementById('Result').value=="ERR"){ 
					document.getElementById('txtHint').innerHTML='<span class="red">Vyskytla sa chyba kontroly prihlasovacieho mena, skúste sa prosím prihlásiť neskôr.</span>';
					return false;
				}
				else if(document.getElementById('Result') && document.getElementById('Result').value==0){
					document.getElementById('txtHint').innerHTML='<span class="red">Toto prihlasovacie meno sa už v systéme nachádza, skúste ho zmeniť.</span>';
					return false;
				}
				else
				{
					document.getElementById("txtHint").innerHTML = "<br>";
					
					return true;	
				}	
			}	
			
		}; // end http request function
		xmlhttp.open("GET","search_login.php?q="+str+"&oldname="+oldname,true);
		xmlhttp.send();
	}
	
}
</script>

 <script> 
function ShowPassword() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <!-- Font Awesome -->
	<link rel="stylesheet" href="mdbootstrap/css/all.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
    <!-- MDB -->
    <link rel="stylesheet" href="mdbootstrap/css/mdb.min.css" />

	<style type="text/css">
	<?php include "mystyle.css"; ?>
	
	</style>
	<script>
	/* po kliknuti na ikonku mobilneho menu - zobrazi alebo zmaze mobilne menu*/
	function menuPreMobily(){
		
		
		var d = document.getElementById("navbarExample01");
		//alert(d);
		if(d.className=="navbar-collapse collapse")
			d.className += " show";
		else if(d.className=="navbar-collapse collapse show")
			d.classList.remove('show');
		
	}
	</script>
	