<?php
	session_start();

	$nome=$_REQUEST["nome"];
	$cognome=$_REQUEST["cognome"];
	$password=$_REQUEST["password"];
	$v_password=$_REQUEST["v_password"];
	$mail=$_REQUEST["mail"];
	
	if($nome==""||$cognome==""||$mail==""||$password==""||$v_password==""){
		?>
			<script>
				alert("Assicurati di aver inserito tutti i campi!");
				top.location="index.php";
			</script>
		<?php		
	}
	else{
		if($password==$v_password){
			include("settings.php");
			if ($con){
				$db=mysql_select_db($dbselected);
								
				$query= "select * from utenti where mail='".$mail."';" ;
				$check_gia_registrato_ris=mysql_query($query);
				if($check_gia_registrato_ris>0)
					$check_gia_registrato = mysql_fetch_array($check_gia_registrato_ris);
				
				if($check_gia_registrato){
					?>
						<script>
							alert("Questa e-mail &egrave; gi&agrave; stata utilizzata per registra un'altro account.");
							top.location="index.php";
						</script>
					<?php
				}
				else{

					$query="INSERT INTO utenti (nome,cognome,mail,password) values ('$nome','$cognome','$mail','$password');"; 
					$insert_ris=mysql_query($query);
					if($insert_ris>0){
						
						$query= "select * from utenti where mail='".$mail."' and password='$password';" ;
						$info_utente_ris=mysql_query($query);
						$info_utente=mysql_fetch_array($info_utente_ris);
						$_SESSION["utente"]= $info_utente["id_utente"];
						mysql_close($con);
						header("location:usercontrol.php"); //Accesso effettuato
					}

				}
			}
			else{
				header("location:errore.php"); //Errore con il database
			}
		}
		else{
			header("location:errore.php"); //Le password inserite non concidono
		}

	}
?>

