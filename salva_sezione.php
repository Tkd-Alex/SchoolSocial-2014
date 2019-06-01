<?php
	session_start();

	$indirizzo=$_REQUEST["indirizzo"];
	$sezione=$_REQUEST["sezione"];
	
	if($indirizzo=="" && $sezione=="" ){
		header("location:errore.php"); //Campi vuoti
	}
	else{
		include("settings.php");
		if ($con){
			$db=mysql_select_db($dbselected);
			

$query="INSERT INTO `sezioni`(`indirizzo`, `sezione`) VALUES ('$indirizzo','$sezione');";		

			$ris=mysql_query($query);
			
			if($ris>0){
				?>
					<script>
						alert("Sezione inserita.");
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

