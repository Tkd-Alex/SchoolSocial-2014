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
		
		include_once 'select.class.php';
		$opt = new SelectList();
		
		//Controllo se l'utente si è già registratore in una classe
		$query="SELECT * FROM ute_cla WHERE utente=".$_SESSION["utente"].";"; //Query
		$check_classe_utente_ris = mysql_query($query); //Eseguo
		$check_classe_utente = mysql_fetch_array($check_classe_utente_ris); //Creo l'array
		$check_classe_utente_backup = $check_classe_utente; //Creo l'array
		
		
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
		$query = "SELECT scuole.*, sezioni.*, indirizzi.*, classi.*
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
		
		//Ricavo le informazioni sui ruoli
		$query="select * from ruoli"; //Query
		$ruoli_ris=mysql_query($query); //Eseguo
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
				
				//Funziona di ricerca
				$(function() {
					$(".auto").autocomplete({
						source: "auto_cerca.php",
					});				
				});
				
				//Controlli sulla finestra
				$(document).ready(function(){

					var scegli = '<option value="0">Scegli</option>';
					var attendere = '<option value="0">Attendere</option>';
					
					<?php if( ($info_utente["regione"] && $info_utente["provincia"] && $info_utente["comune"]) ==null ){ ?>
						$("select#province").html(scegli);
						$("select#province").attr("disabled", "disabled");
						$("select#comuni").html(scegli);
						$("select#comuni").attr("disabled", "disabled");
					<?php } ?>
					
					
					$("select#regioni").change(function(){
						var regione = $("select#regioni option:selected").attr('value');
						$("select#province").html(attendere);
						$("select#province").attr("disabled", "disabled");
						$("select#comuni").html(scegli);
						$("select#comuni").attr("disabled", "disabled");
						
						if(regione==0){
							$("select#province").html(scegli);
							$("select#province").attr("disabled", "disabled");
							$("select#comuni").html(scegli);
							$("select#comuni").attr("disabled", "disabled");
						}
						else{
							$.post("select.php", {id_reg:regione}, function(data){
								$("select#province").removeAttr("disabled"); 
								$("select#province").html(data);	
							});
						}
					});	
					
					$("select#province").change(function(){
						$("select#comuni").attr("disabled", "disabled");
						$("select#comuni").html(attendere);
						var provincia = $("select#province option:selected").attr('value');
						if(provincia==0){
							$("select#comuni").html(scegli);
							$("select#comuni").attr("disabled", "disabled");	
						}
						else{
							$.post("select.php", {id_pro:provincia}, function(data){
								$("select#comuni").removeAttr("disabled");
								$("select#comuni").html(data);	
							});
						}
					});	
					
					
					<?php if(!$info_utente){ ?>
						$("select#indirizzo").html(scegli);
						$("select#indirizzo").attr("disabled", "disabled");
						$("select#sezione").html(scegli);
						$("select#sezione").attr("disabled", "disabled");
						$("select#classe").html(scegli);
						$("select#classe").attr("disabled", "disabled");
					<?php } ?>
		
					
					$("select#scuola").change(function(){
						var scuola = $("select#scuola option:selected").attr('value');
						$("select#indirizzo").html(attendere);
						$("select#indirizzo").attr("disabled", "disabled");
						$("select#sezione").html(scegli);
						$("select#sezione").attr("disabled", "disabled");
						$("select#classe").html(scegli);
						$("select#classe").attr("disabled", "disabled");
						
						$("#settingsschool").hide();
						
						if(scuola==0){
							$("select#indirizzo").html(scegli);
							$("select#indirizzo").attr("disabled", "disabled");
							$("select#sezione").html(scegli);
							$("select#sezione").attr("disabled", "disabled");
							$("select#classe").html(scegli);
							$("select#classe").attr("disabled", "disabled");
						}
						else{
							$.post("select.php", {id_scuola:scuola}, function(data){
								$("select#indirizzo").removeAttr("disabled"); 
								$("select#indirizzo").html(data);	
							});
							
							$("#settingsschool").show();						
						}
					});	
					
					$("select#indirizzo").change(function(){
						var indirizzo = $("select#indirizzo option:selected").attr('value');
						$("select#sezione").html(attendere);
						$("select#sezione").attr("disabled", "disabled");
						$("select#classe").html(scegli);
						$("select#classe").attr("disabled", "disabled");
						
						if(indirizzo==0){
							$("select#sezione").html(scegli);
							$("select#sezione").attr("disabled", "disabled");
							$("select#classe").html(scegli);
							$("select#classe").attr("disabled", "disabled");	
						}
						else{
							$.post("select.php", {id_indirizzo:indirizzo}, function(data){
								$("select#sezione").removeAttr("disabled"); 
								$("select#sezione").html(data);	
							});
						}
					});	
					
					$("select#sezione").change(function(){
						var sezione = $("select#sezione option:selected").attr('value');
						$("select#classe").html(attendere);
						$("select#classe").attr("disabled", "disabled");
						
						if(sezione==0){
							$("select#classe").html(scegli);
							$("select#classe").attr("disabled", "disabled");
						}
						else{
							$.post("select.php", {id_sezione:sezione}, function(data){
								$("select#classe").removeAttr("disabled"); 
								$("select#classe").html(data);	
							});
						}
					});	

					$("#scuola").val("<?php echo $info_scuola_utente['id_scuola'] ;?>");
					$("#indirizzo").val("<?php echo $info_scuola_utente['id_indirizzo'] ;?>");
					$("#sezione").val("<?php echo $info_scuola_utente['id_sezione'] ;?>");
					$("#classe").val("<?php echo $info_scuola_utente['id_classe'] ;?>");	
					
					$("#regioni").val("<?php echo $info_utente["regione"] ;?>");
					$("#province").val("<?php echo $info_utente["provincia"] ;?>");
					$("#comuni").val("<?php echo $info_utente["comune"] ;?>");		

					$("#ruolo").val("<?php echo $info_utente["ruolo"] ;?>");	

					//
					if(
							$("#data").val()=="" ||
							$("#regioni").val()==0 ||
							$("#province").val()==0 ||
							$("#comuni").val()==0 ||
							$("#via").val()=="" ||
							$("#mail").val()=="" ||
							$("#scuola").val()==0 ||
							$("#indirizzo").val()==0 ||
							$("#sezione").val()==0 ||
							$("#classe").val()==0 
						){
							$(".messaggi").html('<font color="#910101">Il tasto salva non funzioner&agrave; affinch&egrave; non saranno riempiti tutti i campi.</font>');
							$(".modifica_u").attr("disabled", "disabled");
						}
						else{
							$(".messaggi").html('<font color="#07810d">Perfetto, puoi salvare le modifiche.</font>');
							$(".modifica_u").removeAttr("disabled"); 
						}
				
					$( window ).change(function(){
						if(
							$("#data").val()=="" ||
							$("#regioni").val()==0 ||
							$("#province").val()==0 ||
							$("#comuni").val()==0 ||
							$("#via").val()=="" ||
							$("#mail").val()=="" ||
							$("#scuola").val()==0 ||
							$("#indirizzo").val()==0 ||
							$("#sezione").val()==0 ||
							$("#classe").val()==0 
						){
							$(".messaggi").html('<font color="#910101">Il tasto salva non funzioner&agrave; affinch&egrave; non saranno riempiti tutti i campi.</font>');
							$(".modifica_u").attr("disabled", "disabled");
						}
						else{
							$(".messaggi").html('<font color="#07810d">Perfetto, puoi salvare le modifiche.</font>');
							$(".modifica_u").removeAttr("disabled"); 
						}
					});	
					
					$( ".v_password, .password, .hold_password").keyup(function () {
						if( $(".hold_password").val() != "<?php echo $info_utente["password"] ; ?>"){
							$(".messaggi_v_password").html('<font color="#910101">La password non coincide con quella attuale.</font>');
							$(".modifica_password").attr("disabled", "disabled");
							var controllo = false;
						}
						else{
							$(".messaggi_v_password").html('<font color="#07810d">Password corrispondente.</font>');
							var controllo = true;
						}
						
						if($( ".password" ).val() =="" && $( ".v_password" ).val()==""){
							$(".mespsw").html('<font color="#910101">Inserisci la password in entrambi i campi.</font>');
							$(".modifica_password").attr("disabled", "disabled");
						}
						else{
							if( ($( ".password" ).val() == $( ".v_password" ).val())&& controllo == true ){
								$(".mespsw").html('<font color="#07810d">Password corrispondenti. Puoi procedere con la registrazione.</font>');
								$(".modifica_password").removeAttr("disabled"); 
							}
							else{
								$(".mespsw").html('<font color="#910101">Le password inserite non concidono riprova.</font>');
								$(".modifica_password").attr("disabled", "disabled");
							}
						}
					}).keyup();			
				});				
			
			</script>
		</head>
				
				<div id="pat"></div>
				
				<div id="container_impo">
				
					<form method= "post" action="salva_utente.php">
						Data di nascita: <input type="date" id="data" name="data" value="<?php echo $info_utente["data_nascita"] ;?>" > <br>

						Seleziona una regione:
						<select id="regioni" name="regioni">
							<?php echo $opt->ShowRegioni(); ?>
						</select>
						<br>

						Seleziona una provincia:
						<select id="province" name="province">
							<?php echo $opt->ShowProvince1($info_utente["regione"]); ?>
						</select>
						<br>

						Seleziona un comune:
						<select id="comuni" name="comuni">
							<?php echo $opt->ShowComuni1($info_utente["provincia"]); ?>
						</select>
						<br>

						Via : <input type="text" id="via" name="via" value="<?php echo $info_utente["via"] ;?>"> <br>

						E-Mail : <input type="email" id="mail" name="mail" value="<?php echo $info_utente["mail"] ;?>"> <br>

						Ruolo: <select name="ruolo" id="ruolo">
						<?php

						while($ruoli = mysql_fetch_array($ruoli_ris)){
							echo '<option value='.$ruoli['id_ruolo'].'>'.$ruoli["tipo"].'</option>';
						}
						?>

						</select><br>

						<?php if($info_utente["ruolo"]==2){
							echo "<ul type='square'>";
							$query="SELECT * FROM ute_cla WHERE utente=".$_SESSION["utente"].";"; //Query
							$check_classe_utente_ris = mysql_query($query); //Eseguo
							if($check_classe_utente_ris>0) //Controllo se il risultato è positivo
								$check_classe_utente = mysql_fetch_array($check_classe_utente_ris); //Creo l'array

							while($check_classe_utente){ //Ciclo le classi dell'utente
								$query = "SELECT scuole . * , sezioni . * , indirizzi . * ,classi.*
								FROM indirizzi
								INNER JOIN scuole ON ( indirizzi.scuola = scuole.id_scuola ) 
								INNER JOIN sezioni ON ( sezioni.indirizzo = indirizzi.id_indirizzo ) 
								INNER JOIN classi ON ( classi.sezione = sezioni.id_sezione ) WHERE classi.id_classe = ".$check_classe_utente['classe'].";";

								$class_utente_docente_ris = mysql_query($query);
								$class_utente_docente = mysql_fetch_array($class_utente_docente_ris);
								?>

								<li><a href='gruppoclasse.php?cl= <?php echo $check_classe_utente["classe"]."'>".$class_utente_docente["classe"].$class_utente_docente["nome_sezione"]." ".$class_utente_docente["indirizzo"]." ".$class_utente_docente["nome"]."</a>"; ?> 

								<a href="javascript:;" id="settingsschool" onclick="window.open('win_dscontrol.php?cl=<?php echo $check_classe_utente['classe'] ; ?>', 'Modifica Scuola', 'width=460, height=180, scrollbars=yes, location=no, status=no, menubar=no, toolbar=no, fullscreen=no');">
								<img src="ico/schoolSet.png" height=15 width=15 onmouseover="this.src='ico/schoolSetHover.png'" onmouseout="this.src='ico/schoolSet.png'" border=0></a>								
								<br>

								<?php $check_classe_utente = mysql_fetch_array($check_classe_utente_ris); //Creo l'array
							} ?>
							</ul>

							<a href="javascript:;" id="" onclick="window.open('scuoladocente.php', 'Aggiungi scuola', 'width=450, height=170, scrollbars=yes, location=no, status=no, menubar=no, toolbar=no, fullscreen=no');">Aggiungi una scuola</a>

						<?php }
						else{ ?>

							Seleziona la scuola:
							<select id="scuola" name="scuola">
								<?php echo $opt->ShowScuole(); ?>
							</select>

							<a href="javascript:;" onclick="window.open('win_addscuola.php', 'Aggiungi scuola', 'width=600, height=600, scrollbars=yes, location=no, status=no, menubar=no, toolbar=no, fullscreen=no');">
							<img src="ico/add.png" height=15 width=15 onmouseover="this.src='ico/addHover.png'" onmouseout="this.src='ico/add.png'" border=0></a>
							<a href="javascript:;" id="settingsschool" onclick="window.open('win_schoolcontrol.php?sc=<?php echo $info_scuola_utente['id_scuola'] ; ?>' , 'Modifica scuola', 'width=660, height=320, scrollbars=yes, location=no, status=no, menubar=no, toolbar=no, fullscreen=no');">
							<img src="ico/schoolSet.png" height=15 width=15 onmouseover="this.src='ico/schoolSetHover.png'" onmouseout="this.src='ico/schoolSet.png'" border=0></a>

							<br>
			
							Seleziona l'indirizzo:
							<select id="indirizzo" name="indirizzo">
								<?php  if($check_classe_utente_backup)
									echo $opt->ShowIndirizzi1($info_scuola_utente['id_scuola']);?>
								</select>
							<br>
					
							Seleziona la sezione:
							<select id="sezione" name="sezione">
								<?php  if($check_classe_utente_backup)
									echo $opt->ShowSezioni1($info_scuola_utente['id_indirizzo']); ?>
								</select>
							<br>

							Seleziona la classe:
							<select id="classe" name="classe">
								<?php if($check_classe_utente_backup)
									echo $opt->ShowClassi1($info_scuola_utente['id_sezione']);  ?>
							</select>
							<br>	
						<?php } ?>

						<div class="messaggi"></div>

						<input type="submit" id="mybutton" value="Salva" class="modifica_u" disabled="disabled">
					</form>

					<form method= "post" action="salva_avatar.php" enctype="multipart/form-data">
						Seleziona l'avatar dal tuo PC:
						<input type="file" name="upload">  <br>
						<input type="submit" id="mybutton" value="Salva">
					</form>

					<form method= "post" action="cambia_psw.php">
						Vecchia password: <input type="password" name="hold_password" class="hold_password"> <br>
						<div class="messaggi_v_password"></div>
						Nuova password: <input type="password" name="password" class="password"> <br>
						Conferma password: <input type="password" name="v_password" class="v_password"> <br>
						<div class="mespsw"></div>
						<input type="submit" id="mybutton" value="Salva" class="modifica_password" disabled="disabled">
					</form>

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
}else{ //Se il login non è stato effettuato
	header("location:index.php"); //Redirect
}
?>
