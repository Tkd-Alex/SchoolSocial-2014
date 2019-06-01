<?php
	session_start();

	$classe=$_REQUEST["classe"];
	$sezione=$_REQUEST["sezione"];
	
	if($classe=="" && $sezione=="" ){
		header("location:errore.php"); //Campi vuoti
	}
	else{
		include("settings.php");
		if ($con){
			$db=mysql_select_db($dbselected);

			$query="INSERT INTO `classi`(`classe`, `sezione`) VALUES ('$classe','$sezione');";		

			$ris=mysql_query($query);
			
			if($ris>0){
				?>
					<script>
						alert("Classe inserita.");
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

