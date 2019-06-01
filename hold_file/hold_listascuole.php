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
						$query = "SELECT * FROM ute_cla WHERE utente = ".$_GET['id'].";";
						$ris111 = mysql_query($query);
						$scuole= mysql_fetch_array($ris111);	
						
						while($scuole){
							$query33="SELECT scuole.nome, sezioni.nome_sezione, indirizzi.indirizzo, classi.classe
							FROM indirizzi
							INNER JOIN scuole ON ( indirizzi.scuola = scuole.id_scuola ) 
							INNER JOIN sezioni ON ( sezioni.indirizzo = indirizzi.id_indirizzo ) 
							INNER JOIN classi ON ( classi.sezione = sezioni.id_sezione ) WHERE classi.id_classe = ".$scuole['classe'].";";
							$ris33=mysql_query($query33);
							$row33 = mysql_fetch_array($ris33);
							
							?>
							<div id="user_ico">
									<img src="classe.png"height="60" width="60">
								</div>
								<div id="box" class="bubble">
									<a href="gruppoclasse.php?cl=<?php echo $scuole['classe']; ?> ">
								 <?php echo $row33["classe"].$row33["nome_sezione"].' '.$row33["indirizzo"].'<br>'.$row33["nome"]; ?>	</a>
							</div>
							<?php
							
							$scuole= mysql_fetch_array($ris111);	
						}
						
						?>
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
							<li id="scuola"><a href="gruppoclasse.php?cl='.$row3["classe"].'">'.$row4["classe"].$row4["nome_sezione"].' '.$row4["indirizzo"].'<br>'.$row4["nome"].'&nbsp;</a></li>
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
