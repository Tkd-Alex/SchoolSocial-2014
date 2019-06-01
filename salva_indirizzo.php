<?php
	session_start();

	$indirizzo=$_REQUEST["indirizzo"];
	$scuola=$_REQUEST["scuola"];
	
	if($indirizzo=="" && $scuola=="" ){
		header("location:errore.php"); //Campi vuoti
	}
	else{
		include("settings.php");
		if ($con){
			$db=mysql_select_db($dbselected);
			
			$query="INSERT INTO `indirizzi`(`indirizzo`, `scuola`) VALUES ('$indirizzo','$scuola');";		

			$ris=mysql_query($query);
			
			if($ris>0){
				?>
					<script>
						alert("Indirizzo inserito.");
						top.location="win_addscuola.php";
					</script>
				<?php
			}
			else{
				header("location:errore.php"); //Errore inserimento scuola
			}
		}
		else{
			header("location:errore.php"); //Errore con il database
		}
	}
?>

