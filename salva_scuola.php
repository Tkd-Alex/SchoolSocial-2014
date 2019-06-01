<?php
	session_start();

	$nome=$_REQUEST["nome"];
	$regione=$_REQUEST["regioni"];
	$provincia=$_REQUEST["province"];
	$comune=$_REQUEST["comuni"];
	$via=$_REQUEST["via"];
	$telefono=$_REQUEST["telefono"];
	$fax=$_REQUEST["fax"];
	$mail=$_REQUEST["mail"];
	$sitoweb=$_REQUEST["sitoweb"];

	
	if($nome==""){
		header("location:errore.php"); //Nome vuoto
	}
	else{
		include("settings.php");
		if ($con){
			$db=mysql_select_db($dbselected);
			

			$query="INSERT INTO `scuole`(`nome`, `telefono`, `fax`, `mail`, `sitoweb`, `via`, `regione`, `provincia`, `comune`) VALUES ('$nome','$telefono','$fax','$mail','$sitoweb','$via','$regione','$provincia','$comune');";		

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
				header("location:errore.php"); //Errore inserimento scuola
			}
		}
		else{
			header("location:errore.php"); //Errore con il database
		}
	}
?>

