<?php
	session_start();

	$post=$_REQUEST["post"];
	$per_cl=$_REQUEST["cl"];
	$cur_id=$_SESSION["utente"];

	
	if($post==""){
		header("location:errore.php"); //Testo vuoto
	}
	else{
		include("settings.php");
		if ($con){
			$db=mysql_select_db($dbselected);
							
			$query="insert into post (data_ora, contenuto,scritto_per_classe,scritto_da_utente) values (NOW(),'".$post."','$per_cl','$cur_id');"; 
			$ris=mysql_query($query);
			
			if($ris>0){
				?>
					<script>
						alert("Post inviato con successo.");
						top.location="gruppoclasse.php?cl=<?php echo $per_cl ;?>";
					</script>
				<?php	
			}
			else{
				?>
					<script>
						alert("Errore! Forse hai un po' esagerato con la formattazione del testo!");
						top.location="gruppoclasse.php?cl=<?php echo $per_cl ;?>";
					</script>
				<?php	
			}
		}
		else{
			header("location:errore.php"); //Errore con il database
		}
	}
?>

