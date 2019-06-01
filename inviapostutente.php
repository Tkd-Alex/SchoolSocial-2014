<?php
	session_start();

	$post=$_REQUEST["post"];
	$per_id=$_REQUEST["id"];
	$cur_id=$_SESSION["utente"];

	
	if($post==""){
		header("location:errore.php"); //Testo vuoto
	}
	else{
		include("settings.php");
		if ($con){
			$db=mysql_select_db($dbselected);
							
			$query="insert into post (data_ora, contenuto,scritto_per_utente,scritto_da_utente) values (NOW(),'".$post."','$per_id','$cur_id');"; 
			$ris=mysql_query($query);
			
			if($ris>0){
				?>
					<script>
						alert("Post inviato con successo.");
						top.location="user.php?id=<?php echo $per_id ;?>";
					</script>
				<?php	
			}
			else{
				?>
					<script>
						alert("Errore! Forse hai un po' esagerato con la formattazione del testo!");
						top.location="user.php?id=<?php echo $per_id ;?>";
					</script>
				<?php	
			}
		}
		else{
			header("location:errore.php"); //Errore con il database
		}
	}
?>

