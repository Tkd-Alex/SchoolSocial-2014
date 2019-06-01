<?php
	session_start();
	if($_SESSION["utente"]!=""){
	
	$id_utente = $_SESSION["utente"];
	
	include("settings.php");
			if ($con){
				$db=mysql_select_db($dbselected);
				
				$cartella_upload ="upload_avatar/";
				$tipi_consentiti = array("gif","png","jpeg","jpg"); 
		
				if(isset($_FILES["upload"])){
					
					if(trim($_FILES["upload"]["name"]) == ''){
						?>
							<script>
								alert("Non hai selezionato nessun file!");
								top.location="usercontrol.php";
							</script>
						<?php	
					}
					else if(!is_uploaded_file($_FILES["upload"]["tmp_name"]) or $_FILES["upload"]["error"]>0){
						?>
							<script>
								alert("Si sono verificati problemi nella procedura di upload!");
								top.location="usercontrol.php";
							</script>
						<?php							
					}
					else if(!in_array(strtolower(end(explode('.', $_FILES["upload"]["name"]))),$tipi_consentiti)){
						?>
							<script>
								alert("Il file che si desidera uplodare non &#232; fra i tipi consentiti!");
								top.location="usercontrol.php";
							</script>
						<?php						
					}
					else if(!is_dir($cartella_upload)){
						?>
							<script>
								alert("La cartella in cui si desidera salvare il file non esiste!");
								top.location="usercontrol.php";
							</script>
						<?php							
					}
					else if(!is_writable($cartella_upload)){
						?>
							<script>
								alert("La cartella in cui fare l'upload non ha i permessi!");
								top.location="usercontrol.php";
							</script>
						<?php
					}
					else if(!move_uploaded_file($_FILES["upload"]["tmp_name"], $cartella_upload.$_FILES["upload"]["name"])){
						?>
							<script>
								alert("Ops qualcosa è andato storto nella procedura di upload!");
								top.location="usercontrol.php";
							</script>
						<?php
					}
					else{
						$new_avatar = $cartella_upload.$_FILES["upload"]["name"];
						$ris=mysql_query("UPDATE utenti SET avatar = '$new_avatar' WHERE id_utente= '$id_utente'" );
						if($ris>0){
							?>
								<script>
									alert("Upload eseguito correttamente!");
									top.location="usercontrol.php";
								</script>
							<?php						
						}
					}
				}
		}
		else{
			header("location:errore.php"); //Errore con il database
		}
	}
?>