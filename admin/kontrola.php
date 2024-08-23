<html>
<head>
<?php include "head.php" ?>
</head>
<?php 
include "config.php";
include "lib.php";
$dblink = mysqli_connect($mysql_server, $mysql_user, $mysql_password, $mysql_db); // pripojenie do databazy

$ID_zakaznika = $_POST["ID_zakaznika"];

$sql = "SELECT * FROM zakaznik z
	
	WHERE (z.id_auta = a.id_auta) AND ((z.meno like '%$hladaj%') OR (z.priezvisko like '%$hladaj%') OR (z.email like '%$hladaj%') OR (z.telefon like '%$hladaj%'))";


$vysledok = mysqli_query($dblink, $sql); 


	while ($riadok = mysqli_fetch_assoc($vysledok)) { // mysqli_fetch_assoc - vrati hodnty zo selectu a priradi im mena vybranych stlpcov

		?>
		
		<tr>
			<td><?php echo $riadok["id_zamestnanec"];?></td>
			<td><?php echo $riadok["meno"];?></td>
			<td><?php echo $riadok["priezvisko"];?></td>
			<td><?php echo $riadok["email"];?></td>
			<td><?php echo $riadok["telefon"];?></td>
			<td class="siroky"><?php echo $riadok["znacka"]." ".$riadok["model"]." ".$riadok["rok_vyroby"];?></td>
			<td style = "text-align: center">
<script>
$(document).ready(function() { // ${document}.ready - ked sa donahrava cela stranka
console.log( "Dokument je nahraty a ready!" );
<?php
echo "$('#zmazatf".$riadok["id_zamestnanec"]."').submit(function() {";
?>
		
        var c = confirm("Ste si istý že chcete zmazať zamestnanca:"+
		" "+<?php echo '"'.$riadok["meno"].'"'?>+
		" "+<?php echo '"'.$riadok["priezvisko"].'"'?>+" ?");
		
        return c; // vrati vyber True/False Ano/Nie
		
    });
});
</script>

<?php
$pokus = 12345;
?>
				<form id="zmazatf<?php echo $riadok["id_zamestnanec"];?>" action="index.php" method="post">
					<input type="hidden" name="id" value="<?php echo $riadok["id_zamestnanec"];?>">
					<input type="hidden" name="meno" value="<?php echo $riadok["meno"];?>">
					<input type="hidden" name="priezvisko" value="<?php echo $riadok["priezvisko"];?>">
					<input type="hidden" name="akcia" value="delete">
					<input type="submit" name="Zmazat" Value="Zmazat" class="button" onclick='if(!confirm("Ste si istý že chcete zmazať zamestnanca:"+
																								" "+<?php echo '"'.$riadok["meno"].'"'?>+
												" "+<?php echo '"'.$riadok["priezvisko"].'"'?>+" ?")) return false;'>
																		
				</form>
			</td>
			
			<td style = "text-align: baseline"> 
			<form  action="edituj.php?id=<?php echo $riadok["id_zamestnanec"];?>" method="post">
					<input type="hidden" name="id" value="<?php echo $riadok["id_zamestnanec"];?>">
					<input type="submit" name="editovat" Value="Editovať" class="button">
					
				</form>
				
				<form action="index.php?id=<?php echo $riadok["id_zamestnanec"];?>" method="post">
					<input type="submit" name="skuska" Value="skuska" class="button">
					<input type="hidden" name="id" value="<?php echo $riadok["id_zamestnanec"];?>">
					<input type="hidden" name="meno" value="<?php echo $riadok["meno"];?>">
					<input type="hidden" name="priezvisko" value="<?php echo $riadok["priezvisko"];?>">
					<input type="hidden" name="pokus" value="<?php echo $pokus;?>">
				</form>
			
			</td>
		</tr>
		
		<?php
	}
	mysqli_close($dblink); //odpojim sa od databazy


?>
</table>
</html>