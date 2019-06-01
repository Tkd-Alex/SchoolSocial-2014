<?php
	session_start();
	if($_SESSION["utente"]!=""){
		$mittente=$_REQUEST["idM"];
		$destinatario=$_REQUEST["idD"];
		
		include("settings.php");
			if ($con){
				$db=mysql_select_db($dbselected);
				
				$query="UPDATE amici
						SET stato = 1
						WHERE amico_mittente= '$mittente' and amico_destinatario='$destinatario'" ;

				$ris=mysql_query($query);
				if($ris>0){
					?>
						<script>
							alert("Richiesta d'amicizia accettata.");
							top.location="richieste.php";
						</script>
					<?php
				}
				else{
					header("location:errore.php"); //Errore query
				}
			}
			else{
				header("location:errore.php"); //Errore con il database
			}
	}
?>

