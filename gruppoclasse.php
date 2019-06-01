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
		$check_classe_utente_backup = $check_classe_utente; //Creo l'array
		
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
			if($n_new_post_ris>0)
				$n_new_post = mysql_num_rows($n_new_post_ris); //Conto le righe
			//Controllo se sono arrivati nuovi post nelle classi
			while($check_classe_utente){
				$query = "SELECT * FROM `post` WHERE `scritto_per_classe` = ".$check_classe_utente["classe"]." AND data_ora > '".$info_utente["ultimo_accesso"]."'"; //Query
				$n_new_post_ris = mysql_query($query); //Eseguo
				if($n_new_post_ris>0)
					$n_new_post = $n_new_post + mysql_num_rows($n_new_post_ris); //Conto le righe
				$check_classe_utente = mysql_fetch_array($check_classe_utente_ris);
			};
			
			//Ricavo le informazioni riguardati la classe
			$query = "SELECT scuole.*, sezioni.nome_sezione, indirizzi.indirizzo, classi.classe
			FROM indirizzi
			INNER JOIN scuole ON ( indirizzi.scuola = scuole.id_scuola ) 
			INNER JOIN sezioni ON ( sezioni.indirizzo = indirizzi.id_indirizzo ) 
			INNER JOIN classi ON ( classi.sezione = sezioni.id_sezione ) WHERE classi.id_classe = ".$_GET['cl'].";"; //Query
			$info_classe_ris = mysql_query($query); //Eseguo
			if($info_classe_ris>0) //Controllo se il risultato è positivo
				$info_classe = mysql_fetch_array($info_classe_ris); //Creo l'array
				
			//Ricavo le informazioni sull'indirizzo della scuola				
			$query = "SELECT regioni.nome_regione, comuni.comune, province.nome_provincia 
			FROM regioni
			INNER JOIN province ON (province.id_reg = regioni.id_reg)
			INNER JOIN comuni ON (comuni.id_pro = province.id_pro)
			WHERE comuni.id_com = ".$info_classe["comune"].";"; //Query
			$info_indirizzo_cl_ris = mysql_query($query); //Eseguo
			if($info_indirizzo_cl_ris>0) //Controllo se il risultato è positivo
				$info_indirizzo_cl = mysql_fetch_array($info_indirizzo_cl_ris); //Creo l'array			 	

			//Controllo se l'utente è registrato alla classe				
			$query = "SELECT * FROM ute_cla WHERE utente=".$_SESSION["utente"]." and classe =".$_GET['cl'].";"; //Query
			$check_classe_ris = mysql_query($query); //Eseguo
			if($check_classe_ris>0) //Controllo se il risultato è positivo
				$check_classe = mysql_fetch_array($check_classe_ris); //Creo l'array

			//Ricavo le informazioni sui post scritti nella classe
			$query = "SELECT * FROM  post WHERE scritto_per_classe = ".$_GET['cl']." ORDER BY data_ora DESC" ; //Query
			$post_ris = mysql_query($query); //Eseguo
			if($post_ris>0) //Controllo se il risultato è positivo
				$post = mysql_fetch_array($post_ris); //Crea l'array					
?>
		<html>
			
			<head>
				<title>School Social</title>
				<link rel="stylesheet" type="text/css" href="style.css"> 
				<link rel="stylesheet" href="script/jquery-ui.min.css" type="text/css" /> 
				<script type="text/javascript" src="script/jquery-1.9.1.min.js"></script>
				<script type="text/javascript" src="script/jquery-ui.min.js"></script>
				<script type="text/javascript" src="script/tinymce/tinymce.min.js"></script>
				
				<script type="text/javascript">
					//Crea link per Google Maps
					$(document).ready(function () {
						var indirizzo = " <?php echo $info_utente["via"]." ".$comune_utente["comune"]." ".$comune_utente["nome_provincia"]." ".$comune_utente["nome_regione"]; ?>";
						$('#address').each(function () {
							var link = "<a href='http://maps.google.com/maps?q=" + encodeURIComponent( indirizzo ) + "' target='_blank'>" + $(this).text() + "</a>";
							$(this).html(link);
						});
					});	
					
					//Crea link per Google Maps della scuola
					$(document).ready(function () {
						var indirizzo = " <?php echo $info_classe["via"]." ".$info_indirizzo_cl["comune"]." ".$info_indirizzo_cl["nome_provincia"]." ".$info_indirizzo_cl["nome_regione"]; ?>";
						$('#address_scuola').each(function () {
							var link = "<a href='http://maps.google.com/maps?q=" + encodeURIComponent( indirizzo ) + "' target='_blank'>" + $(this).text() + "</a>";
							$(this).html(link);
						});
					});	
					
					//Funziona di ricerca
					$(function() {
						$(".auto").autocomplete({
							source: "auto_cerca.php",
						});				
					});
					
					//Script per TinyMCE				
					tinymce.init({
						selector: "textarea",
						language: "it",
						plugins: "textcolor",
						menubar: false,
						toolbar1: "styleselect fontselect fontsizeselect formatselect ",
						toolbar2: "bold italic underline strikethrough alignleft aligncenter alignright alignjustify | cut copy paste undo redo | bullist numlist outdent indent | forecolor backcolor",
					});								
				</script>
			</head>
					
					<div id="pat"></div>
					
					<div id="container">

						<div id="info_utente">
							<div id="utente_nome">	

								<?php echo $info_classe["classe"].$info_classe["nome_sezione"].' '.$info_classe["indirizzo"].' '.$info_classe["nome"]; ?>							
								<img src="sottonome.png" id="sottonome">
							</div>
							<div id="utente_avatar">
								<img src="classe.png"height="135" width="135">
							</div>

							<div id="informazioni_u">
								<img src=" ico/indi.png"> <a href="" id="address_scuola"> <?php echo $info_classe["via"] ?> </a>  &nbsp;        
								<img src=" ico/mail.png"><a href="mailto:<?php echo $info_classe["mail"] ?>"> <?php echo $info_classe["mail"] ?> </a> &nbsp;
								<br>
								<img src=" ico/telefono.png"> Tefono: <?php echo $info_classe["telefono"]." | Fax: ".$info_classe["fax"] ?> &nbsp;
								<img src=" ico/website.png"> <a href="<?php echo $info_classe["sitoweb"]?> " target="_blank"> <?php echo $info_classe["sitoweb"]?> </a> &nbsp;	 
							</div>

						</div>

						<?php if($check_classe){ ?>

							<div id="user_ico">
								<img src="   <?php echo $info_utente["avatar"] ?>   "height="60" width="60">
							</div>

							<div id="box" class="bubble">
								<form method= "post" action="inviapostclasse.php?cl=<?php echo $_GET['cl']; ?>">
									<textarea name="post" id="text_post"></textarea><br>
								<div style="text-align: right"><input type="submit" id="mybutton" value="Invia"></div>
								</form>
							</div>

							<?php while($post){
								$query="SELECT * FROM  `utenti` WHERE id_utente = ".$post["scritto_da_utente"]."";
								$infopost_ris = mysql_query($query);
								$infopost = mysql_fetch_array($infopost_ris ); ?>

								<div id="user_ico">
									<img src="   <?php echo $infopost["avatar"] ?>   "height="60" width="60">
								</div>
								<div id="box" class="bubble">
									<?php echo $post["contenuto"]; ?>
									<div id="info_us">
										<?php echo "Scritto il: ".$post["data_ora"]." da: <a href='user.php?id=".$infopost["id_utente"]."'>".$infopost["nome"]." ".$infopost["cognome"]."</a>" ;
										if( $post["scritto_da_utente"]  == $_SESSION["utente"] )
											echo " | <a href='delete_post.php?post=".$post["id_post"]."'>Elimina</a>	";
										?>
									</div>
								</div>
								<?php $post = mysql_fetch_array($post_ris); 
							}?>

							<br><br><br>

						<?php } ?>						
	
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
								
								<li id="scuola"><a href="gruppoclasse.php?cl='.$check_classe_utente_backup["classe"].'">'.$info_scuola_utente["classe"].$info_scuola_utente["nome_sezione"].' '.$info_scuola_utente["indirizzo"].'<br>'.$info_scuola_utente["nome"].'&nbsp;</a></li>';
								
								if($ruolo_utente["tipo"]=="Docente"){ //Se l'utente è un docente, stampa la voce "Altro..." per la gestione di altre classi
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
							<?php if($n_richieste>0) //Se le richieste d'amicizie sono > 0 stampo il relativo numero
								echo  '<div id="r_amici"><a href="richieste.php">'.$n_richieste.'</div></a> '; ?>
							<img id="notifiche" src="ico/notifiche.png" width=53 height=34/>
							<?php if($n_new_post>0) //Se i nuovi post sono > 0 stampo il relativo numero
								echo  '<div id="new_post"><a href="post.php">'.$n_new_post.'</div></a> '; ?>
							<a href="logout.php"><img src="ico/exit.png"  onmouseover="this.src='ico/exitHover.png'" onmouseout="this.src='ico/exit.png'" border=0 id="logout"></a>	
							<a href="usercontrol.php" id="b_impostazioni">Impostazioni</a>
					</div>
				
			</body>
		</html>
<?php
		}else{
			//Se l'utente non ha ancora registrato una classe
			?>
				<script>
					alert("Aggiorna tutte le informazioni del tuo profilo."); //Alert
					top.location="usercontrol.php"; //Redirect
				</script>
			<?php		
		}
	}else{ //Se il login non è stato effettuato
		header("location:index.php"); //Redirect
	}
?>
