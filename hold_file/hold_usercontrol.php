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
		
	include_once 'select.class.php';
	$opt = new SelectList();

	$query="SELECT * FROM `ute_cla` WHERE `utente` = ".$_SESSION["utente"].";" ;
	$ris=mysql_query($query);
	$row = mysql_fetch_array($ris);
	
	$query14="SELECT scuole . * , sezioni . * , indirizzi . * ,classi.*
	FROM indirizzi
	INNER JOIN scuole ON ( indirizzi.scuola = scuole.id_scuola ) 
	INNER JOIN sezioni ON ( sezioni.indirizzo = indirizzi.id_indirizzo ) 
	INNER JOIN classi ON ( classi.sezione = sezioni.id_sezione ) WHERE classi.id_classe = ".$row['classe'].";";
	 
	$ris14=mysql_query($query14);
	$row14 = mysql_fetch_array($ris14);
?>	
		<html>
			
			<head>
				<title>School Social</title>
				<link rel="stylesheet" type="text/css" href="style.css"> 
				<link rel="stylesheet" href="script/jquery-ui.min.css" type="text/css" /> 
				<script type="text/javascript" src="script/jquery-1.9.1.min.js"></script>
				<script type="text/javascript" src="script/jquery-ui.min.js"></script>
			</head>		
		
				<script type="text/javascript">
						$(document).ready(function(){

							var scegli = '<option value="0">Scegli</option>';
							var attendere = '<option value="0">Attendere</option>';
							
							<?php if( ($riga["regione"] && $riga["provincia"] && $riga["comune"]) ==null ){ ?>
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
							
							
							<?php if(!$row){ ?>
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

							$("#scuola").val("<?php echo $row14['id_scuola'] ;?>");
							$("#indirizzo").val("<?php echo $row14['id_indirizzo'] ;?>");
							$("#sezione").val("<?php echo $row14['id_sezione'] ;?>");
							$("#classe").val("<?php echo $row14['id_classe'] ;?>");	
							
							$("#regioni").val("<?php echo $riga["regione"] ;?>");
							$("#province").val("<?php echo $riga["provincia"] ;?>");
							$("#comuni").val("<?php echo $riga["comune"] ;?>");		

							$("#ruolo").val("<?php echo $riga["ruolo"] ;?>");	

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
						});
				</script>
				
					<div id="pat"></div>
					
					<div id="container_impo">
					
					<form method= "post" action="salva_utente.php">
						Data di nascita: <input type="date" id="data" name="data" value="<?php echo $riga["data_nascita"] ;?>" > <br>
						
						Seleziona una regione:
						<select id="regioni" name="regioni">
							<?php echo $opt->ShowRegioni(); ?>
						</select>
						<br>

						Seleziona una provincia:
						<select id="province" name="province">
							<?php echo $opt->ShowProvince1($riga["regione"]); ?>
						</select>
						<br>

						Seleziona un comune:
						<select id="comuni" name="comuni">
							<?php echo $opt->ShowComuni1($riga["provincia"]); ?>
						</select>
						<br>
						
						Via : <input type="text" id="via" name="via" value="<?php echo $riga["via"] ;?>"> <br>
						
						E-Mail : <input type="email" id="mail" name="mail" value="<?php echo $riga["mail"] ;?>"> <br>
						
						Ruolo: <select name="ruolo" id="ruolo">
						<?php
							$query1="select * from ruoli";
							$ris1=mysql_query($query1);
							while($riga1 = mysql_fetch_array($ris1)){
								echo '<option value='.$riga1['id_ruolo'].'>'.$riga1["tipo"].'</option>';
							}
						?>
						
						</select><br>
						<?php if($riga["ruolo"]==2){
							echo "<ul type='square'>";
							while($row){
								$query1996="SELECT scuole . * , sezioni . * , indirizzi . * ,classi.*
								FROM indirizzi
								INNER JOIN scuole ON ( indirizzi.scuola = scuole.id_scuola ) 
								INNER JOIN sezioni ON ( sezioni.indirizzo = indirizzi.id_indirizzo ) 
								INNER JOIN classi ON ( classi.sezione = sezioni.id_sezione ) WHERE classi.id_classe = ".$row['classe'].";";
								 
								$ris1996=mysql_query($query1996);
								$row1996 = mysql_fetch_array($ris1996);

								echo "<li>";
								echo "<a href=''>";
								echo $row1996["classe"].$row1996["nome_sezione"].' '.$row1996["indirizzo"].' '.$row1996["nome"];
								echo "</a>"; 
								?>
								<a href="javascript:;" id="settingsschool" onclick="window.open('win_dscontrol.php?cl=<?php echo $row['classe'] ; ?>', 'Modifica Scuola', 'width=400, height=150, scrollbars=yes, location=no, status=no, menubar=no, toolbar=no, fullscreen=no');">
								<img src="ico/schoolSet.png" height=15 width=15 onmouseover="this.src='ico/schoolSetHover.png'" onmouseout="this.src='ico/schoolSet.png'" border=0></a>								
								<?php echo "<br>";
								
								$row = mysql_fetch_array($ris);
							}
							echo "</ul>";
							
							?>
							<a href="javascript:;" id="" onclick="window.open('scuoladocente.php', 'Aggiungi scuola', 'width=400, height=150, scrollbars=yes, location=no, status=no, menubar=no, toolbar=no, fullscreen=no');">Aggiungi una scuola</a>
							<?php
						}
						else{ ?>
						
						
						Seleziona la scuola:
						<select id="scuola" name="scuola">
							<?php echo $opt->ShowScuole(); ?>
						</select>
		
						<a href="javascript:;" onclick="window.open('win_addscuola.php', 'Aggiungi scuola', 'width=600, height=600, scrollbars=yes, location=no, status=no, menubar=no, toolbar=no, fullscreen=no');">
						<img src="ico/add.png" height=15 width=15 onmouseover="this.src='ico/addHover.png'" onmouseout="this.src='ico/add.png'" border=0></a>
						<a href="javascript:;" id="settingsschool" onclick="window.open('win_schoolcontrol.php?sc=<?php echo $row14['id_scuola'] ; ?>', 'Modifica scuola', 'width=600, height=320, scrollbars=yes, location=no, status=no, menubar=no, toolbar=no, fullscreen=no');">
						<img src="ico/schoolSet.png" height=15 width=15 onmouseover="this.src='ico/schoolSetHover.png'" onmouseout="this.src='ico/schoolSet.png'" border=0></a>
			
						<br>
						
						Seleziona l'indirizzo:
						<select id="indirizzo" name="indirizzo">
							<?php  if($row){ 
							echo $opt->ShowIndirizzi1($row14['id_scuola']); }?>
						</select>
						<br>

						Seleziona la sezione:
						<select id="sezione" name="sezione">
							<?php  if($row){ 
							echo $opt->ShowSezioni1($row14['id_indirizzo']); } ?>
						</select>
						<br>
			
						Seleziona la classe:
						<select id="classe" name="classe">
							<?php if($row){ 
							echo $opt->ShowClassi1($row14['id_sezione']); } ?>
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
						Vecchia password: <input type="password" name="password" class="password"> <br>
						Nuova password: <input type="password" name="password" class="password"> <br>
						Conferma password: <input type="password" name="v_password" class="v_password"> <br>
						<input type="submit" id="mybutton" value="Salva">

					</form>
					
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
		header("location:index.php");
	}
?>
