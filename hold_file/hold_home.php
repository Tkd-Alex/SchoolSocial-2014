<?php
	session_start();
	if($_SESSION["utente"]!=""){
	
		include("settings.php");
		
		if ($con){
				$db=mysql_select_db($dbselected);

				$query= "select * from utenti 
				where id_utente='".$_SESSION["utente"]."' " ;

				$ris=mysql_query($query);

				$riga=mysql_fetch_array($ris);
		}
		$query2="SELECT * FROM ute_cla WHERE utente=".$_SESSION["utente"].";";
		$ris2=mysql_query($query2);
		$riga2=mysql_fetch_array($ris2);
		
		if($riga2){
			$query5="SELECT  `amico_destinatario`, `stato` FROM  `amici` 
			WHERE amico_destinatario = ".$_SESSION["utente"]." AND stato =0";
			$ris5=mysql_query($query5);
			$richieste= mysql_num_rows($ris5);			
?>
		<html>
			
			<head>
				<title>School Social</title>
				<link rel="stylesheet" type="text/css" href="style.css"> 
				<link rel="stylesheet" href="script/jquery-ui.min.css" type="text/css" /> 
				<script type="text/javascript" src="script/jquery-1.9.1.min.js"></script>
				<script type="text/javascript" src="script/jquery-ui.min.js"></script>
			</head>
					
					<div id="pat"></div>
					
					<div id="container">
						<?php
						$query17 = "SELECT * FROM post ORDER BY data_ora DESC";
						$ris17 = mysql_query($query17);
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
						<br><br><br><br><br>
					</div>
					
					<div id="menu">
						<div id="userid">
							<img src="   <?php echo $riga["avatar"] ?>   "height="135" width="135">
						</div>
						
							<?php
							$query152="SELECT regioni.nome_regione, comuni.comune, province.nome_provincia 
							FROM regioni
							INNER JOIN province ON (province.id_reg = regioni.id_reg)
							INNER JOIN comuni ON (comuni.id_pro = province.id_pro)
							WHERE comuni.id_com = ".$riga["comune"].";";
							
							$ris152 = mysql_query($query152);
							$riga152=mysql_fetch_array($ris152);
	
							?>							
								
							<script>
							$(document).ready(function () {
								var indirizzo = " <?php echo $riga["via"]." ".$riga152["comune"]." ".$riga152["nome_provincia"]." ".$riga152["nome_regione"]; ?>";
								$('#address').each(function () {
									var link = "<a href='http://maps.google.com/maps?q=" + encodeURIComponent( indirizzo ) + "' target='_blank'>" + $(this).text() + "</a>";
									$(this).html(link);
								});
							});	
							</script>	
						
						<?php
						echo '<br><div align="center">'.$riga["nome"].' '.$riga["cognome"].'</div>' ;
					
						$query="select * from ruoli where id_ruolo=".$riga["ruolo"]."";
						$ris=mysql_query($query);
						if($ris>0)
						$riga1 = mysql_fetch_array($ris);
						
						$query3="SELECT * FROM `ute_cla` WHERE `utente` = ".$_SESSION["utente"].";" ;
						$ris3=mysql_query($query3);
						if($ris3>0)
						$row3 = mysql_fetch_array($ris3);
						$query3="SELECT scuole.nome, sezioni.nome_sezione, indirizzi.indirizzo, classi.classe
						FROM indirizzi
						INNER JOIN scuole ON ( indirizzi.scuola = scuole.id_scuola ) 
						INNER JOIN sezioni ON ( sezioni.indirizzo = indirizzi.id_indirizzo ) 
						INNER JOIN classi ON ( classi.sezione = sezioni.id_sezione ) WHERE classi.id_classe = ".$row3['classe'].";";
						$ris3=mysql_query($query3);
						if($ris3>0)
						$row4 = mysql_fetch_array($ris3);
						
						echo '
						<ul id="info">
							<li id="data"><a href="">'.$riga["data_nascita"].'&nbsp;</a></li>
							<li id="indi"><div id="address">'.$riga["via"].'&nbsp;</div></li>
							<li id="mail"><a href="mailto:'.$riga["mail"].'">'.$riga["mail"].'&nbsp;</a></li>
							
							<li id="scuola"><a href="gruppoclasse.php?cl='.$row3["classe"].'">'.$row4["classe"].$row4["nome_sezione"].' '.$row4["indirizzo"].'<br>'.$row4["nome"].'&nbsp;</a></li>';
							
							if($riga1["tipo"]=="Docente"){
								echo "<li id='scuola'> <a href='listascuole.php?id=".$_SESSION["utente"]."'> Altro... </a></li>"; 
							}
							
						echo '	</li>
							<li id="ruolo"><a href="">'.$riga1["tipo"].'&nbsp;</a></li>
						</ul>
						';
						
						?>
					</div>
					
					<div id="header">
						<div id="search">
							<form method= "post" action="cerca.php">
								<input type="text" id="text_area" name="cerca" class='auto'> 
								<input type="submit" id="mybutton" value="Cerca">
							</form>
						</div>
						
						
						<script>
							$(function() {
								$(".auto").autocomplete({
									source: "auto_cerca.php",
								});				
							});
						</script>
						
						<div id="header2">	
							<a href="home.php"><img src="logogrande.png" id="logopic" height=71 width=120></a>
						</div>
							<a href="richieste.php"><img id="amici" src="ico/friends.png" width=39 height=35/>
							<div id="r_amici"><?php echo $richieste ?></div></a>
							<img id="notifiche" src="ico/notifiche.png" width=53 height=34/>
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
