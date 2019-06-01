<?php
	session_start();
	if($_SESSION["utente"]!=""){

		$id_utente = $_SESSION["utente"];

		$data=$_REQUEST["data"];
		$regione=$_REQUEST["regioni"];
		$provincia=$_REQUEST["province"];
		$comune=$_REQUEST["comuni"];
		$mail=$_REQUEST["mail"];
		$scuola=$_REQUEST["scuola"];
		$indirizzo=$_REQUEST["indirizzo"];
		$sezione=$_REQUEST["sezione"];
		$classe=$_REQUEST["classe"];
		$ruolo=$_REQUEST["ruolo"];
		$via=$_REQUEST["via"];

		include("settings.php");
		if ($con){
			$db=mysql_select_db($dbselected);

			//Aggiorno i dati anagrafici
			$query="UPDATE utenti
					SET data_nascita = '$data',
					via = '$via',
					regione ='$regione',
					provincia ='$provincia',
					comune ='$comune',
					mail = '$mail',
					ruolo = '$ruolo'
					WHERE id_utente= '$id_utente' " ; //Query

			$aggiorna_ris = mysql_query($query); //Eseguo
			if($aggiorna_ris>0){ //Se l'aggiornamento è andato a buon fine
				
				if($ruolo==1){//Se l'utente è un alunno effettuo le funzioni di salvataggio scuola
					//Controllo se l'utente ha già registrato una scuola
					$query="SELECT ute_cla.utente FROM ute_cla WHERE utente=".$id_utente.";"; //Query
					$verifica_classe_ris = mysql_query($query); //Eseguo
					if($verifica_classe_ris>0) //Controllo l'esito positivo
						$verifica_classe=mysql_fetch_array($verifica_classe_ris); //Creo l'array
			
					if(!$verifica_classe){ //Se l'array è vuoto significa l'utente non ha ancora registrato una classe
					
						//Registro la prima classe 
						$query="INSERT INTO `ute_cla`(`utente`, `classe`) VALUES ('$id_utente','$classe');"; //Query
						$agg_classe=mysql_query($query); //Eseguo
						if($agg_classe>0){ //Se la classe è stata aggiunta mando l'alert
							?>
								<script>
									alert("Modifiche apportate con successo.");
									top.location="usercontrol.php";
								</script>
							<?php
						}
						else{ 
							header("location:errore.php"); //Errore con l'aggiunta della classe
						}
					}
					else{ //Se è già stata inserita una classe l'aggiorno 
						$query="UPDATE ute_cla
								SET utente = '$id_utente',
								classe = '$classe'
								WHERE utente= '$id_utente' " ; //Query
						$aggiorna_scuola = mysql_query($query); //Eseguo
						if($aggiorna_scuola>0){ //Se l'aggiornamento della classe è stato eseguito con successo 
							?>
							<script>
								alert("Modifiche apportate con successo.");
								top.location="usercontrol.php";
							</script>
						<?php
						}
						else{ 
							header("location:errore.php"); //Errore con l'update
						}
					}
				}
				else{
					?>
						<script>
							alert("Modifiche apportate con successo.");
							top.location="usercontrol.php";
						</script>
					<?php
				}
			}
			else{
				header("location:errore.php"); //Errore con l'aggiornamento
			} 
		}
		else{
			header("location:errore.php"); //Errore con il database
		}
	}

?>