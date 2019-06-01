<?php
	session_start();

	$id=$_GET['id'];
	$sessione=$_SESSION['utente'];
	
	include("settings.php");
		if ($con){
		
			$db=mysql_select_db($dbselected);
			$query="INSERT INTO amici (amico_mittente,amico_destinatario,stato) values ('$sessione','$id',false);"; 
			$ris=mysql_query($query);
			if($ris){
				?>
					<script>
						alert("Richiesta d'amicizia inviata con successo.");
						top.location="user.php?id=<?php echo $id ;?>";
					</script>
				<?php	
			}
			else{
				header("location:errore.php"); //Errore inserimento
			}
		}
		else{
			header("location:errore.php"); //Errore connessione
		}
?>

