<?php
	session_start();
	
	$mail=$_REQUEST["mail"];
	$password=$_REQUEST["password"];

	if($mail==""||$password==""){
		header("location:errore.php"); //Assicurati di aver inserito tutti i campi
	}
	else{
		include("settings.php");
		if ($con){

			$db=mysql_select_db($dbselected);

			$query= "select * from utenti 
			where mail='".$mail."' and password='$password';" ;

			$ris=mysql_query($query);

			$riga=mysql_fetch_array($ris);

			if($riga){
					$_SESSION["utente"]= $riga["id_utente"];
					header("location:home.php"); //Accesso effettuato
				}
			else{
					header("location:errore.php"); //Mail e/o username errati
				}
			mysql_close($con);
		}
	}

?>