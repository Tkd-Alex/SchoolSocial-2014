<?php
	
	session_start();
	if($_SESSION["utente"]!=""){
	
		include("settings.php");
		
		if ($con){
				$db=mysql_select_db($dbselected);

		}
		
	include_once 'select.class.php';
	$opt = new SelectList();
	
	$query="select * from scuole"; //Query
	$info_scuole_ris=mysql_query($query); //Eseguo
	if($info_scuole_ris>0) //Controllo se il risultato è positivo
		$info_scuole = mysql_fetch_array($info_scuole_ris); //Creo l'array
	
	$query="SELECT indirizzi.*, scuole.* FROM indirizzi INNER JOIN scuole ON indirizzi.scuola = scuole.id_scuola"; //Query
	$info_indirizzi_ris=mysql_query($query); //Eseguo
	if($info_indirizzi_ris>0) //Controllo se il risultato è positivo
		$info_indirizzi = mysql_fetch_array($info_indirizzi_ris); //Creo l'array
	
	$query="SELECT scuole.*,sezioni.*, indirizzi.* FROM indirizzi INNER JOIN scuole ON (indirizzi.scuola = scuole.id_scuola) INNER JOIN sezioni ON (sezioni.indirizzo = indirizzi.id_indirizzo)"; //Query
	$info_sezioni_ris=mysql_query($query); //Eseguo
	if($info_sezioni_ris>0) //Controllo se il risultato è positivo
		$info_sezioni = mysql_fetch_array($info_sezioni_ris); //Creo l'array
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
					
					$("select#province").html(scegli);
					$("select#province").attr("disabled", "disabled");
					$("select#comuni").html(scegli);
					$("select#comuni").attr("disabled", "disabled");
					
					
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
					
					$("select#indirizzo").html(scegli);
					$("select#indirizzo").attr("disabled", "disabled");
					$("select#sezione").html(scegli);
					$("select#sezione").attr("disabled", "disabled");
					$("select#classe").html(scegli);
					$("select#classe").attr("disabled", "disabled");
					
					$("select#scuola").change(function(){
						var scuola = $("select#scuola option:selected").attr('value');
						$("select#indirizzo").html(attendere);
						$("select#indirizzo").attr("disabled", "disabled");
						$("select#sezione").html(scegli);
						$("select#sezione").attr("disabled", "disabled");
						$("select#classe").html(scegli);
						$("select#classe").attr("disabled", "disabled");
						
						$.post("select.php", {id_scuola:scuola}, function(data){
							$("select#indirizzo").removeAttr("disabled"); 
							$("select#indirizzo").html(data);	
						});
					});	
					
					$("select#indirizzo").change(function(){
						var indirizzo = $("select#indirizzo option:selected").attr('value');
						$("select#sezione").html(attendere);
						$("select#sezione").attr("disabled", "disabled");
						$("select#classe").html(scegli);
						$("select#classe").attr("disabled", "disabled");
						
						$.post("select.php", {id_indirizzo:indirizzo}, function(data){
							$("select#sezione").removeAttr("disabled"); 
							$("select#sezione").html(data);	
						});
					});	
					
					$("select#sezione").change(function(){
						var sezione = $("select#sezione option:selected").attr('value');
						$("select#classe").html(attendere);
						$("select#classe").attr("disabled", "disabled");
						
						$.post("select.php", {id_sezione:sezione}, function(data){
							$("select#classe").removeAttr("disabled"); 
							$("select#classe").html(data);	
						});
					});	
					
					if( ($("#nome").val()=="") || ($("#comuni").val()==0) || ($("#province").val()==0) || ($("#regioni").val()==0) ){
						$(".messaggi_scuola").html('<font color="#910101">Il tasto salva non funzioner&agrave; affinch&egrave; non sarrano riempiti i campi contrassegnati da *.</font>');
						$(".salva_scuola").attr("disabled", "disabled");
					}
					else{
						$(".messaggi_scuola").html('<font color="#07810d">Perfetto, puoi aggiungere una nuova scuola.</font>');
						$(".salva_scuola").removeAttr("disabled"); 
					}	
				
					if( $("#indirizzo").val()==""){
						$(".messaggi_indirizzo").html('<font color="#910101">Il tasto salva non funzioner&agrave; affinch&egrave; non sar&agrave riempito almeno il campo "Nome indirizzo".</font>');
						$(".salva_indirizzo").attr("disabled", "disabled");
					}
					else{
						$(".messaggi_indirizzo").html('<font color="#07810d">Perfetto, puoi aggiungere un nuovo indirizzo.</font>');
						$(".salva_indirizzo").removeAttr("disabled"); 
					}
				
					if( $("#sezione").val()==""){
						$(".messaggi_sezione").html('<font color="#910101">Il tasto salva non funzioner&agrave; affinch&egrave; non sar&agrave riempito almeno il campo "Nome sezione".</font>');
						$(".salva_sezione").attr("disabled", "disabled");
					}
					else{
						$(".messaggi_sezione").html('<font color="#07810d">Perfetto, puoi aggiungere una nuova sezione.</font>');
						$(".salva_sezione").removeAttr("disabled"); 
					}
			
					if( $("#classe").val()==""){
						$(".messaggi_classe").html('<font color="#910101">Il tasto salva non funzioner&agrave; affinch&egrave; non sar&agrave riempito almeno il campo "Nome classe".</font>');
						$(".salva_classe").attr("disabled", "disabled");
					}
					else{
						$(".messaggi_classe").html('<font color="#07810d">Perfetto, puoi aggiungere una nuova classe.</font>');
						$(".salva_classe").removeAttr("disabled"); 
					}
					
					$( window ).change(function(){
						if( ($("#nome").val()=="") || ($("#comuni").val()==0) || ($("#province").val()==0) || ($("#regioni").val()==0) ){
							$(".messaggi_scuola").html('<font color="#910101">Il tasto salva non funzioner&agrave; affinch&egrave; non sarrano riempiti i campi contrassegnati da *.</font>');
							$(".salva_scuola").attr("disabled", "disabled");
						}
						else{
							$(".messaggi_scuola").html('<font color="#07810d">Perfetto, puoi aggiungere una nuova scuola.</font>');
							$(".salva_scuola").removeAttr("disabled"); 
						}	
					
						if( $("#indirizzo").val()==""){
							$(".messaggi_indirizzo").html('<font color="#910101">Il tasto salva non funzioner&agrave; affinch&egrave; non sar&agrave riempito almeno il campo "Nome indirizzo".</font>');
							$(".salva_indirizzo").attr("disabled", "disabled");
						}
						else{
							$(".messaggi_indirizzo").html('<font color="#07810d">Perfetto, puoi aggiungere un nuovo indirizzo.</font>');
							$(".salva_indirizzo").removeAttr("disabled"); 
						}
				
						if( $("#sezione").val()==""){
							$(".messaggi_sezione").html('<font color="#910101">Il tasto salva non funzioner&agrave; affinch&egrave; non sar&agrave riempito almeno il campo "Nome sezione".</font>');
							$(".salva_sezione").attr("disabled", "disabled");
						}
						else{
							$(".messaggi_sezione").html('<font color="#07810d">Perfetto, puoi aggiungere una nuova sezione.</font>');
							$(".salva_sezione").removeAttr("disabled"); 
						}
			
						if( $("#classe").val()==""){
							$(".messaggi_classe").html('<font color="#910101">Il tasto salva non funzioner&agrave; affinch&egrave; non sar&agrave riempito almeno il campo "Nome classe".</font>');
							$(".salva_classe").attr("disabled", "disabled");
						}
						else{
							$(".messaggi_classe").html('<font color="#07810d">Perfetto, puoi aggiungere una nuova classe.</font>');
							$(".salva_classe").removeAttr("disabled"); 
						}
					});	
				});
			
			</script>
					
				
				<form method= "post" action="salva_scuola.php">
					<fieldset>
					<legend>Aggiungi scuola</legend>
						Nome istituto  <font color="#910101">*</font>: <input type="text" id="nome" name="nome"> <br>
						
						Seleziona una regione <font color="#910101">*</font>:
						<select id="regioni" name="regioni">
							<?php echo $opt->ShowRegioni(); ?>
						</select>
						<br>

						Seleziona una provincia <font color="#910101">*</font>:
						<select id="province" name="province">
							<option>Scegli</option>
						</select>
						<br>

						Seleziona un comune <font color="#910101">*</font>:
						<select id="comuni" name="comuni">
							<option>Scegli</option>
						</select>
						<br>
						
						Via : <input type="text" id="via" name="via"> <br>
						
						Telefono : <input type="text" id="telefono" name="telefono"> <br>
						Fax : <input type="text" id="fax" name="fax"> <br>
						Mail : <input type="text" id="mail" name="mail"> <br>
						Sito web : <input type="text" id="sitoweb" name="sitoweb"> <br>
						
						<div class="messaggi_scuola"></div>
						<input type="submit" id="mybutton" value="Salva" class="salva_scuola" disabled="disabled">
					</fieldset>
				</form>
				
				<form method= "post" action="salva_indirizzo.php">
					<fieldset>
					<legend>Aggiungi indirizzo</legend>
						Nome indirizzo : <input type="text" id="indirizzo" name="indirizzo"> <br>
						
						Scuola: <select name="scuola">
							<?php
								while($info_scuole){
									echo '<option value='.$info_scuole['id_scuola'].'>'.$info_scuole["nome"].'</option>';
									$info_scuole = mysql_fetch_array($info_scuole_ris);
								}
							?>
						</select><br>	
						<div class="messaggi_indirizzo"></div>
						<input type="submit" id="mybutton" value="Salva" class="salva_indirizzo" disabled="disabled">
					</fieldset>
				</form>
				
				<form method= "post" action="salva_sezione.php">
					<fieldset>
					<legend>Aggiungi sezione</legend>
					
						Sezione (A-B-C) : <input type="text" id="sezione" name="sezione"> <br>
						
						Indirizzo: <select name="indirizzo">
							<?php
								while($info_indirizzi){
									echo '<option value='.$info_indirizzi['id_indirizzo'].'>'.$info_indirizzi["indirizzo"].' - '.$info_indirizzi["nome"].'</option>';
									$info_indirizzi = mysql_fetch_array($info_indirizzi_ris);
								}
							?>
						</select><br>	
						<div class="messaggi_sezione"></div>						
						<input type="submit" id="mybutton" value="Salva" class="salva_sezione" disabled="disabled">
					</fieldset>
				</form>
				
				<form method= "post" action="salva_classe.php">
					<fieldset>
					<legend>Aggiungi classe</legend>
					
						Classe (1-2-3) : <input type="text" id="classe" name="classe"> <br>
						
						Sezione: <select name="sezione">
							<?php
								while($info_sezioni){
									echo '<option value='.$info_sezioni['id_sezione'].'>'.$info_sezioni["nome_sezione"].' - '.$info_sezioni["indirizzo"].' - '.$info_sezioni["nome"].'</option>';
									$info_sezioni = mysql_fetch_array($info_sezioni_ris);
								}
							?>
						</select><br>	
						<div class="messaggi_classe"></div>		
						<input type="submit" id="mybutton" value="Salva" class="salva_classe" disabled="disabled">
					</fieldset>
				</form>
				
			</body>
		</html>
<?php
	}
	else{
		header("location:index.php");
	}
?>
