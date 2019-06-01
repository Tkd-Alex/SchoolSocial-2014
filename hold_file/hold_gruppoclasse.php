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
		$query2="SELECT ute_cla.utente FROM ute_cla WHERE utente=".$_SESSION["utente"].";";
		$ris2=mysql_query($query2);
		$riga2=mysql_fetch_array($ris2);
		if($riga2){
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
							
								<?php
									$query= "SELECT scuole.*, sezioni.nome_sezione, indirizzi.indirizzo, classi.classe
									FROM indirizzi
									INNER JOIN scuole ON ( indirizzi.scuola = scuole.id_scuola ) 
									INNER JOIN sezioni ON ( sezioni.indirizzo = indirizzi.id_indirizzo ) 
									INNER JOIN classi ON ( classi.sezione = sezioni.id_sezione ) WHERE classi.id_classe = ".$_GET['cl'].";";

									$ut=mysql_query($query);

									$ut2=mysql_fetch_array($ut);
									
									echo $ut2["classe"].$ut2["nome_sezione"].' '.$ut2["indirizzo"].' '.$ut2["nome"];
								?>
								
							<?php
							$query152="SELECT regioni.nome_regione, comuni.comune, province.nome_provincia 
							FROM regioni
							INNER JOIN province ON (province.id_reg = regioni.id_reg)
							INNER JOIN comuni ON (comuni.id_pro = province.id_pro)
							WHERE comuni.id_com = ".$ut2["comune"].";";
							
							$ris152 = mysql_query($query152);
							$riga152=mysql_fetch_array($ris152);
	
							?>							
								
							<script>
							$(document).ready(function () {
								var indirizzo = " <?php echo $ut2["via"]." ".$riga152["comune"]." ".$riga152["nome_provincia"]." ".$riga152["nome_regione"]; ?>";
								$('#address').each(function () {
									var link = "<a href='http://maps.google.com/maps?q=" + encodeURIComponent( indirizzo ) + "' target='_blank'>" + $(this).text() + "</a>";
									$(this).html(link);
								});
							});	
							</script>	
								
								
							<img src="sottonome.png" id="sottonome">
							</div>
							<div id="utente_avatar">
								<img src="classe.png"height="135" width="135">
							</div>

							<div id="informazioni_u">
								<img src=" ico/indi.png"> <a href="" id="address"> <?php echo $ut2["via"] ?> </a>  &nbsp;        
								<img src=" ico/mail.png"><a href="mailto:<?php echo $ut2["mail"] ?>"> <?php echo $ut2["mail"] ?> </a> &nbsp;
								<br>
								<img src=" ico/telefono.png"> Tefono: <?php echo $ut2["telefono"]." | Fax: ".$ut2["fax"] ?> &nbsp;
								<img src=" ico/website.png"> <a href="<?php echo $ut2["sitoweb"]?> " target="_blank"> <?php echo $ut2["sitoweb"]?> </a> &nbsp;	 
							</div>
							
						</div>
						
						<?php 
						$query26="SELECT * FROM ute_cla WHERE utente=".$_SESSION["utente"]." and classe =".$_GET['cl'].";";
						$ris26=mysql_query($query26);
						$riga26=mysql_fetch_array($ris26);
										
						if($riga26){ ?>
						
						<div id="user_ico">
								<img src="   <?php echo $riga["avatar"] ?>   "height="60" width="60">
						</div>
						
						<div id="box" class="bubble">
							<form method= "post" action="inviapostclasse.php?cl=<?php echo $_GET['cl']; ?>">
							<textarea name="post" id="text_post"></textarea><br>
							<div style="text-align: right"><input type="submit" id="mybutton" value="Invia"></div>
							</form>
						</div>
						
						<?php
						$query5="SELECT * FROM  post 
						WHERE scritto_per_classe = ".$_GET['cl']."" ;
						$ris5=mysql_query($query5);
						$post1=mysql_fetch_array($ris5);	
						
						while($post1){
							$query7="SELECT * FROM  `utenti` 
							WHERE id_utente = ".$post1["scritto_da_utente"]."";
							$ris7=mysql_query($query7);
							$infopost=mysql_fetch_array($ris7);
							?>
							<div id="user_ico">
								<img src="   <?php echo $infopost["avatar"] ?>   "height="60" width="60">
							</div>
							<div id="box" class="bubble">
								<?php echo $post1["contenuto"]; ?>
								<div id="info_us">
									<?php echo $infopost["nome"]." ".$infopost["cognome"] ;?>
								</div>
							</div>
						 <?php
						$post1=mysql_fetch_array($ris5); }
						?>
					
						<br><br><br><br><br><br>
						
						<?php } ?>
						
					</div>
					
					<div id="menu">
						<div id="userid">
							<img src="   <?php echo $riga["avatar"] ?>   "height="135" width="135">
						</div>
						
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
						$row3 = mysql_fetch_array($ris3);
						
						echo '
						<ul id="info">
							<li id="data"><a href="">'.$riga["data_nascita"].'&nbsp;</a></li>
							<li id="indi"><a href="">'.$riga["via"].'&nbsp;</a></li>
							<li id="mail"><a href="">'.$riga["mail"].'&nbsp;</a></li>
							<li id="scuola"><a href="">'.$row3["classe"].$row3["nome_sezione"].' '.$row3["indirizzo"].'<br>'.$row3["nome"].'&nbsp;</a></li>
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
							<img id="amici" src="ico/friends.png" width=39 height=35/>
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
