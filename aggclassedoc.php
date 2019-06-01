<?php
	session_start();
	if($_SESSION["utente"]!=""){

		$id_utente = $_SESSION["utente"];
		$classe=$_REQUEST["classe"];
		$old_classe=$_REQUEST["cl"];

		include("settings.php");
			if ($con){
				$db=mysql_select_db($dbselected);

				$query="UPDATE ute_cla
						SET utente = '$id_utente',
							classe = '$classe'
						WHERE utente= '$id_utente' and classe = '$old_classe' " ;

				$ris=mysql_query($query);
				if($ris>0){
					?>
						<script>
							alert("Classe aggiornata.");
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

?>