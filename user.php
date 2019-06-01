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
			
			//Ricavo informazioni sull'utente della pagina
			$query = "select * from utenti where id_utente=".$_GET['id']; //Query
			$info_id_ris = mysql_query($query); //Eseguo
			$info_id = mysql_fetch_array($info_id_ris); //Creo l'array
			
			//Ricavo informazioni sull'indirizzo dell'utente della pagina	
			$query="SELECT regioni.nome_regione, comuni.comune, province.nome_provincia 
			FROM regioni
			INNER JOIN province ON (province.id_reg = regioni.id_reg)
			INNER JOIN comuni ON (comuni.id_pro = province.id_pro)
			WHERE comuni.id_com = ".$info_id["comune"].";"; //Query
			$residenza_ris = mysql_query($query); //Eseguo
			if($residenza_ris>0)//Controllo se il risultato è positivo
				$residenza=mysql_fetch_array($residenza_ris); //Creo l'array	 

			//Ricavo il tipo di ruolo dell'utente della pagina	
			$query = "select * from ruoli where id_ruolo=".$info_id["ruolo"].""; //Query
			$ruolo_id_ris = mysql_query($query); //Eseguo
			if($ruolo_id_ris>0) //Controllo se il risultato è positivo
				$ruolo_id = mysql_fetch_array($ruolo_id_ris); //Creo l'array	 	
		
			//Ricavo la prima classe registrata dall'utente
			$query="SELECT * FROM `ute_cla` WHERE `utente` = ".$_GET['id'].";" ; //Query 
			$classe_id_ris=mysql_query($query); //Eseguo
			if($classe_id_ris>0) //Controllo se il risultato è positivo
				$classe_id = mysql_fetch_array($classe_id_ris); //Creo l'array
				
			//Ricavo le informazioni della classe, scuola, sezione, indirizzo dell'utente attuale	
			$query = "SELECT scuole.nome, sezioni.nome_sezione, indirizzi.indirizzo, classi.classe
			FROM indirizzi
			INNER JOIN scuole ON ( indirizzi.scuola = scuole.id_scuola ) 
			INNER JOIN sezioni ON ( sezioni.indirizzo = indirizzi.id_indirizzo ) 
			INNER JOIN classi ON ( classi.sezione = sezioni.id_sezione ) WHERE classi.id_classe = ".$classe_id['classe'].";"; //Query
			$info_id_scuola_ris=mysql_query($query); //Eseguo
			if($info_id_scuola_ris>0) //Controllo se il risultato è positivo
				$info_id_scuola = mysql_fetch_array($info_id_scuola_ris); //Creo l'array		

			//Controllo se c'è qualche relazione di amicizia tra l'utente attuale e quello loggato
			$query = "SELECT * FROM amici where (amico_mittente = ".$_SESSION["utente"]." and amico_destinatario = ".$_GET['id'].") 
			or (amico_mittente = ".$_GET['id']." and amico_destinatario = ".$_SESSION["utente"].")"; //Query
			$controllo_amicizia_ris = mysql_query($query); //Eseguo
			if($controllo_amicizia_ris>0)  //Controllo se il risultato è positivo
				$controllo_amicizia = mysql_fetch_array($controllo_amicizia_ris); //Creo l'array

			//Ricavo le informazioni sui post inviati all'utente della pagina
			$query = "SELECT * FROM post WHERE scritto_per_utente = ".$_GET['id']." ORDER BY data_ora DESC" ; //Query
			$post_ris = mysql_query($query); //Eseguo
			if($post_ris>0) //Controllo se il risultato è positivo
				$post = mysql_fetch_array($post_ris); //Creo l'array	
				
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
						var indirizzo = " <?php echo $info_id["via"]." ".$residenza["comune"]." ".$residenza["nome_provincia"]." ".$residenza["nome_regione"]; ?>";
						$('#address_id').each(function () {
							var link = "<a href='http://maps.google.com/maps?q=" + encodeURIComponent( indirizzo ) + "' target='_blank'>" + $(this).text() + "</a>";
							$(this).html(link);
						});
					});	
					
					//Crea link per Google Maps dell'utente loggato
					$(document).ready(function () {
						var indirizzo = " <?php echo $info_utente["via"]." ".$comune_utente["comune"]." ".$comune_utente["nome_provincia"]." ".$comune_utente["nome_regione"]; ?>";
						$('#address').each(function () {
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
								<?php echo $info_id["nome"].' '.$info_id["cognome"] ; ?>							
								<img src="sottonome.png" id="sottonome">
							</div>
							<div id="utente_avatar">
								<img src="   <?php echo $info_id["avatar"] ?>   "height="135" width="135">
							</div>

							<div id="informazioni_u">
								<img src=" ico/data.png">  <?php echo $info_id["data_nascita"] ?> &nbsp;
								<img src=" ico/indi.png"> <a href="" id="address_id"> <?php echo $info_id["via"] ?> </a> &nbsp; 
								<img src=" ico/mail.png"> <a href="mailto:<?php echo $info_id["mail"] ?>"> <?php echo $info_id["mail"] ?>  </a> &nbsp; <br>
								<img src=" ico/scuola.png"> <a href="gruppoclasse.php?cl=<?php echo $classe_id['classe']; ?>"> <?php echo $info_id_scuola["classe"].$info_id_scuola["nome_sezione"].' '.$info_id_scuola["indirizzo"].' '.$info_id_scuola["nome"] ?> </a> &nbsp;
								<?php
								if ($ruolo_id["tipo"]=="Docente"){
									echo "<a href='listascuole.php?id=".$_GET['id']."'> Altro... </a> &nbsp;"; 
								}
								?>

								<img src=" ico/ruolo.png">  <?php echo $ruolo_id["tipo"]?> &nbsp;	 
							</div>

							<?php if(!$controllo_amicizia){ ?>
								<div id="addAmico">
									<form method= "post" action="richiesta.php?id=  <?php echo $_GET['id']; ?>   ">
										<input type="image" src="ico/add_amico.png" alt="Invia richiesta d'amicizia." title="invia il modulo" width=39 
										height=35 onmouseover="this.src='ico/addHover_amico.png'" onmouseout="this.src='ico/add_amico.png'"> 
										</form>
								</div>
							<?php } ?>

						</div>

						<?php if( ($controllo_amicizia["stato"]==1) || ($_GET['id'] == $_SESSION["utente"]) ) { //Verifico che l'utente loggato sia amico con il proprietario della pagina o sia lo stesso ?>

							<div id="user_ico">
								<img src="   <?php echo $info_utente["avatar"] ?>   "height="60" width="60">
							</div>

							<div id="box" class="bubble">
								<form method= "post" action="inviapostutente.php?id=<?php echo $_GET['id']; ?>">
									<textarea name="post" id="text_post"></textarea><br>
									<div style="text-align: right"><input type="submit" id="mybutton" value="Invia"></div>
								</form>
							</div>

							<?php while($post){
								//Ricavo le informazioni sul singolo post
								$query="SELECT * FROM  `utenti` WHERE id_utente = ".$post["scritto_da_utente"].""; //Query
								$infopost_ris = mysql_query($query); //Eseguo
								if($infopost_ris>0) //Controllo se il risultato è positivo
									$infopost = mysql_fetch_array($infopost_ris); //Creo l'array
								?>
								<div id="user_ico">
									<img src="   <?php echo $infopost["avatar"] ?>   "height="60" width="60">
								</div>
								
								<div id="box" class="bubble">
									<?php echo $post["contenuto"]; ?>
									<div id="info_us">
										<?php echo "Scritto il: ".$post["data_ora"]." da: <a href='user.php?id=".$infopost["id_utente"]."'>".$infopost["nome"]." ".$infopost["cognome"]."</a>" ;
										if( ($post["scritto_per_utente"] == $_SESSION["utente"]) || ($post["scritto_da_utente"]  == $_SESSION["utente"]) )
											echo " | <a href='delete_post.php?post=".$post["id_post"]."'>Elimina</a>	";
										?>
									</div>
								</div>
								
								<?php $post = mysql_fetch_array($post_ris); 
							} ?>
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
