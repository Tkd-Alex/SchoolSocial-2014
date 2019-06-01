<?php
	session_start();
	if($_SESSION["utente"]!=""){

		$id_utente = $_SESSION["utente"];
		$classe=$_REQUEST["classe"];

		if($classe="")
			header("location:errore.php"); //Campi vuoti
		else{
			include("settings.php");
			if ($con){
				$db=mysql_select_db($dbselected);

				$query="INSERT INTO `ute_cla`(`utente`, `classe`) VALUES ('$id_utente','$classe');";	

				$ris=mysql_query($query);
				
				if($ris>0){
					?>
						<script>
							alert("Scuola aggiunta con successo.");
							javascript:self.close();
						</script>
					<?php
				}
				else{
					header("location:errore.php"); //Errore con il database
				}

			}
			else{
				header("location:errore.php"); //Errore con il database
			}
		}		
	}

?>