<?php
	session_start();
	if($_SESSION["utente"]!=""){

		$id_utente = $_SESSION["utente"];
		$password=$_REQUEST["password"];

		include("settings.php");
			if ($con){
				$db=mysql_select_db($dbselected);

				$query="UPDATE utenti SET password = '$password' WHERE id_utente= '$id_utente'  " ;

				$ris=mysql_query($query);
				if($ris>0){
					$_SESSION["utente"]="";
					?>
						<script>
							alert("Password modifica. Rieffettua l'accesso");
							top.location="index.php";
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

?>