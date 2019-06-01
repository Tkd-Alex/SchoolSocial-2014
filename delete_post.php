<?php
	session_start();
	if($_SESSION["utente"]!=""){

		$id_post = $_REQUEST["post"];

		include("settings.php");
			if ($con){
				$db=mysql_select_db($dbselected);

				$query="DELETE FROM post WHERE id_post = '$id_post'; " ;

				$ris=mysql_query($query);
				if($ris>0){
					?>
						<script>
							alert("Post eliminato con successo.");
							top.location="home.php";
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