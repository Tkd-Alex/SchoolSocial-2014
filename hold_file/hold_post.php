<?php
	session_start(); //Apro la sessione
	if($_SESSION["utente"]!=""){ //Controllo se è stato effettuato il login
	
		include("settings.php"); //Richiamo i dati per il db
		
		if ($con){ //Se la connessione è stata effettuata con successo....
				
				$db=mysql_select_db($dbselected); //Seleziono il database

				//Ricavo le informazioni riguardante l'utente loggato
				$query = "select * from utenti where id_utente='".$_SESSION["utente"]."' " ; //Query
				$info_utente_ris = mysql_query($query); //Eseguo
				$info_utente = mysql_fetch_array($info_utente_ris); //Creo l'array
		}
		
		//Controllo se l'utente si è già registratore in una classe
		$query="SELECT * FROM ute_cla WHERE utente=".$_SESSION["utente"].";"; //Query
		$check_classe_utente_ris = mysql_query($query); //Eseguo
		$check_classe_utente = mysql_fetch_array($check_classe_utente_ris); //Creo l'array
		
		if($check_classe_utente){ //Se l'array è pieno
		
			//Controllo se l'utente ha ricevuto richieste d'amicizia
			$query ="SELECT  `amico_destinatario`, `stato` FROM  `amici` WHERE amico_destinatario = ".$_SESSION["utente"]." AND stato =0"; //Query
			$n_richieste_ris =mysql_query($query); //Eseguo
			$n_richieste = mysql_num_rows($n_richieste_ris); //Conto le righe	
			
			//Ricavo le informazioni riguardati l'indirizzo dell'utente che ha effettuato l'accesso
			$query = "SELECT regioni.nome_regione, comuni.comune, province.nome_provincia 
			FROM regioni
			INNER JOIN province ON (province.id_reg = regioni.id_reg)
			INNER JOIN comuni ON (comuni.id_pro = province.id_pro)
			WHERE comuni.id_com = ".$info_utente["comune"].";"; //Query
			$comune_utente_ris = mysql_query($query); //Eseguo
			$comune_utente = mysql_fetch_array($comune_utente_ris); //Creo l'array	
			
			//Ricavo il tipo di ruolo dell'utente loggato
			$query = "select * from ruoli where id_ruolo=".$info_utente["ruolo"].""; //Query
			$ruolo_utente_ris = mysql_query($query); //Eseguo
			if($ruolo_utente_ris>0) //Controllo se il risultato è positivo
				$ruolo_utente = mysql_fetch_array($ruolo_utente_ris); //Creo l'array	
			
			//Ricavo le informazioni della classe, scuola, sezione, indirizzo dell'utente loggato
			$query = "SELECT scuole.nome, sezioni.nome_sezione, indirizzi.indirizzo, classi.classe
			FROM indirizzi
			INNER JOIN scuole ON ( indirizzi.scuola = scuole.id_scuola ) 
			INNER JOIN sezioni ON ( sezioni.indirizzo = indirizzi.id_indirizzo ) 
			INNER JOIN classi ON ( classi.sezione = sezioni.id_sezione ) WHERE classi.id_classe = ".$check_classe_utente['classe'].";"; //Query
			$info_scuola_utente_ris = mysql_query($query); //Eseguo
			if($info_scuola_utente_ris>0) //Controllo se il risultato è positivo
				$info_scuola_utente = mysql_fetch_array($info_scuola_utente_ris); //Creo l'array
				
			//Controllo se sono arrivati nuovi post solo per l'utente
			$query = "SELECT * FROM `post` WHERE `scritto_per_utente` = ".$_SESSION['utente']." AND data_ora > '".$info_utente["ultimo_accesso"]."'"; //Query
			$n_new_post_ris = mysql_query($query); //Eseguo
			$n_new_post = mysql_num_rows($n_new_post_ris); //Conto le righe
			//Controllo se sono arrivati nuovi post nelle classi
			while($check_classe_utente){
				$query = "SELECT * FROM `post` WHERE `scritto_per_classe` = ".$check_classe_utente["classe"]."AND data_ora > '".$info_utente["ultimo_accesso"]."'"; //Query
				$n_new_post_ris = mysql_query($query); //Eseguo
				$n_new_post = $n_new_post + mysql_num_rows($n_new_post_ris); //Conto le righe
				$check_classe_utente = mysql_fetch_array($check_classe_utente_ris);
			};
			
			//Registro l'ultimo accesso
			$ultimo_accesso = date ("Y-m-d H:i:s");
			$query = "UPDATE utenti SET ultimo_accesso = NOW() WHERE id_utente = ".$_SESSION['utente']."" ; //Query
			$esegui_query = mysql_query($query); //Eseguo
?>
		<html>
			
			<head>
				<title>School Social</title>
				<link rel="stylesheet" type="text/css" href="style.css"> 
				<link rel="stylesheet" href="script/jquery-ui.min.css" type="text/css" /> 
				<script type="text/javascript" src="script/jquery-1.9.1.min.js"></script>
				<script type="text/javascript" src="script/jquery-ui.min.js"></script>
				
				<script type="text/javascript">
					//Crea link per Google Maps
					$(document).ready(function () {
						var indirizzo = " <?php echo $info_utente["via"]." ".$comune_utente["comune"]." ".$comune_utente["nome_provincia"]." ".$comune_utente["nome_regione"]; ?>";
						$('#address').each(function () {
							var link = "<a href='http://maps.google.com/maps?q=" + encodeURIComponent( indirizzo ) + "' target='_blank'>" + $(this).text() + "</a>";
							$(this).html(link);
						});
					});	
					
					$(function() {
						$(".auto").autocomplete({
							source: "auto_cerca.php",
						});				
					});
				</script>
			</head>
					
					<div id="pat"></div>
					
					<div id="container">
						
						
<?php
	$query17 = "SELECT * FROM post WHERE data_ora > '".$info_utente["ultimo_accesso"]."' ORDER BY data_ora DESC";
	$ris17 = mysql_query($query17);
	if($ris17>0)
	$riga17 = mysql_fetch_array($ris17);
	while($riga17){
		$query18 ="SELECT * FROM amici WHERE ( (amico_mittente=".$_SESSION["utente"]." 
		AND amico_destinatario=".$riga17["scritto_per_utente"]." ) OR 
		(amico_mittente=".$riga17["scritto_per_utente"]." 
		AND amico_destinatario=".$_SESSION["utente"]." ) ) 
		AND stato = 1";
		$ris18 = mysql_query($query18);
		if( ($ris18) || ($riga17["scritto_per_utente"] == $_SESSION["utente"]) ){
			$riga18 = mysql_fetch_array($ris18);
			if( ($riga18>0) || ($riga17["scritto_per_utente"] == $_SESSION["utente"]) ){
				$query7="SELECT * FROM  `utenti` 
				WHERE id_utente = ".$riga17["scritto_da_utente"]."";
				$ris7=mysql_query($query7);
				$infopost=mysql_fetch_array($ris7);
				$query8="SELECT * FROM  `utenti` 
				WHERE id_utente = ".$riga17["scritto_per_utente"]."";
				$ris8=mysql_query($query8);
				$infopost2=mysql_fetch_array($ris8);
				?>
				<div id="user_ico">
				<img src="   <?php echo $infopost["avatar"] ?>   "height="60" width="60">
				</div>
				<div id="box" class="bubble">
				<?php echo $riga17["contenuto"]; ?>
				<div id="info_us">
				<?php echo "Scritto da: ".$infopost["nome"]." ".$infopost["cognome"]." per: ".$infopost2["nome"]." ".$infopost2["cognome"] ;?>
				</div>
				</div>
				<?php
			}									
		}

		$query2="SELECT * FROM ute_cla WHERE utente=".$_SESSION["utente"].";";
		$ris2=mysql_query($query2);
		$riga2=mysql_fetch_array($ris2);
		while($riga2){
			if($riga17["scritto_per_classe"] == $riga2["classe"] ){
				$query7="SELECT * FROM  `utenti` 
				WHERE id_utente = ".$riga17["scritto_da_utente"]."";
				$ris7=mysql_query($query7);
				$infopost=mysql_fetch_array($ris7);

				$query = "SELECT scuole.nome, sezioni.nome_sezione, indirizzi.indirizzo, classi.classe
				FROM indirizzi
				INNER JOIN scuole ON ( indirizzi.scuola = scuole.id_scuola ) 
				INNER JOIN sezioni ON ( sezioni.indirizzo = indirizzi.id_indirizzo ) 
				INNER JOIN classi ON ( classi.sezione = sezioni.id_sezione ) WHERE classi.id_classe = ".$riga2['classe'].";"; //Query
				$info_scuola_utente_ris = mysql_query($query); //Eseguo
				if($info_scuola_utente_ris>0) //Controllo se il risultato è positivo
				$info_scuola_utente = mysql_fetch_array($info_scuola_utente_ris); //Creo l'array

				?>
				<div id="user_ico">
				<img src="   <?php echo $infopost["avatar"] ?>   "height="60" width="60">
				</div>
				<div id="box" class="bubble">
				<?php echo $riga17["contenuto"]; ?>
				<div id="info_us">
				<?php echo "Scritto da: ".$infopost["nome"]." ".$infopost["cognome"]. " Per: ".$info_scuola_utente["classe"].$info_scuola_utente["nome_sezione"].' '.$info_scuola_utente["indirizzo"].' '.$info_scuola_utente["nome"] ;?>
				</div>
				</div>
				<?php
			}
			$riga2=mysql_fetch_array($ris2);
		};

		$riga17 = mysql_fetch_array($ris17);
	}
?>						
				
						
					</div>
					
					<div id="menu">
						<div id="userid">
							<img src="   <?php echo $info_utente["avatar"]; ?>   "height="135" width="135">
						</div>						
						
						<div align="center" id="nome"><a href="user.php?id= <?php echo $_SESSION["utente"];?>">
						<br><?php echo $info_utente["nome"].' '.$info_utente["cognome"]; ?></a></div>
						
						<ul id="info">
							<li id="data"><a href=""><?php echo $info_utente["data_nascita"];?>&nbsp;</a></li>
							<li id="indi"><div id="address"><?php echo $info_utente["via"];?>&nbsp;</div></li>
						
						<?php
							echo '
								<li id="mail"><a href="mailto:'.$info_utente["mail"].'">'.$info_utente["mail"].'&nbsp;</a></li>
								
								<li id="scuola"><a href="gruppoclasse.php?cl='.$check_classe_utente["classe"].'">'.$info_scuola_utente["classe"].$info_scuola_utente["nome_sezione"].' '.$info_scuola_utente["indirizzo"].'<br>'.$info_scuola_utente["nome"].'&nbsp;</a></li>';
								
								if($ruolo_utente["tipo"]=="Docente"){
									echo "<li id='scuola'> <a href='listascuole.php?id=".$_SESSION["utente"]."'> Altro... </a></li>"; 
								}
						?>
							</li>
								<li id="ruolo"><a href=""><?php echo $ruolo_utente["tipo"]?> &nbsp;</a></li>
							</ul>
					
					</div>
					
					<div id="header">
						<div id="search">
							<form method= "post" action="cerca.php">
								<input type="text" id="text_area" name="cerca" class='auto'> 
								<input type="submit" id="mybutton" value="Cerca">
							</form>
						</div>
			
						<div id="header2">	
							<a href="home.php"><img src="logogrande.png" id="logopic" height=71 width=120></a>
						</div>
							<img id="amici" src="ico/friends.png" width=39 height=35/>
							<?php if($n_richieste>0)
								echo  '<div id="r_amici"><a href="richieste.php">'.$n_richieste.'</div></a> '; ?>
							<img id="notifiche" src="ico/notifiche.png" width=53 height=34/>
							<?php if($n_new_post>0)
								echo  '<div id="new_post"><a href="post.php?data='.$ultimo_accesso.'">'.$n_new_post.'</div></a> '; ?>
							<a href="usercontrol.php" id="b_impostazioni">Impostazioni</a>
					</div>
				
			</body>
		</html>
<?php
	}
	else{
		?>
			<script>
				alert("Aggiorna tutte le informazioni del tuo profilo.");
				top.location="usercontrol.php";
			</script>
		<?php		
	}
	}
	else{
		header("location:index.php");
	}
?>
