<?php
	
	session_start();
	if($_SESSION["utente"]!=""){
	
		include("settings.php");
		
		if ($con){
				$db=mysql_select_db($dbselected);
		}
			
	include_once 'select.class.php';
	$opt = new SelectList();
	
	//Ricavo le informazioni sulla scuola selezionata dalla pagina
	$query="SELECT scuole . * , sezioni . * , indirizzi . * ,classi.*
	FROM indirizzi
	INNER JOIN scuole ON ( indirizzi.scuola = scuole.id_scuola ) 
	INNER JOIN sezioni ON ( sezioni.indirizzo = indirizzi.id_indirizzo ) 
	INNER JOIN classi ON ( classi.sezione = sezioni.id_sezione ) WHERE classi.id_classe = ".$_REQUEST["cl"];""; //Query
	$info_scuola_ris=mysql_query($query); //Eseguo
	if($info_scuola_ris>0) //Controllo se il risultato è positivo
		$info_scuola = mysql_fetch_array($info_scuola_ris) //Creo l'array
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
					
					$("select#scuola").change(function(){
						var scuola = $("select#scuola option:selected").attr('value');
						$("select#indirizzo").html(attendere);
						$("select#indirizzo").attr("disabled", "disabled");
						$("select#sezione").html(scegli);
						$("select#sezione").attr("disabled", "disabled");
						$("select#classe").html(scegli);
						$("select#classe").attr("disabled", "disabled");
						
						$("#settingsschool").hide();
						
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
					
					
					$("#scuola").val("<?php echo $info_scuola['id_scuola'] ;?>");
					$("#indirizzo").val("<?php echo $info_scuola['id_indirizzo'] ;?>");
					$("#sezione").val("<?php echo $info_scuola['id_sezione'] ;?>");
					$("#classe").val("<?php echo $info_scuola['id_classe'] ;?>");	
					
					if(
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
					
			<form method= "post" action="aggclassedoc.php?cl=<?php echo $id_classe ;?>">
				Seleziona la scuola:
				<select id="scuola" name="scuola">
					<?php echo $opt->ShowScuole(); ?>
				</select>
				
				<a href="javascript:;" onclick="window.open('win_addscuola.php', 'Aggiungi scuola', 'width=600, height=600, scrollbars=yes, location=no, status=no, menubar=no, toolbar=no, fullscreen=no');">
				<img src="ico/add.png" height=15 width=15 onmouseover="this.src='ico/addHover.png'" onmouseout="this.src='ico/add.png'" border=0></a>
				
				<a href="javascript:;" onclick="window.open('win_schoolcontrol.php?sc=<?php echo $info_scuola['id_scuola'] ; ?>', 'Modifica scuola', 'width=600, height=320, scrollbars=yes, location=no, status=no, menubar=no, toolbar=no, fullscreen=no');">
				<img src="ico/schoolSet.png" height=15 width=15 onmouseover="this.src='ico/schoolSetHover.png'" onmouseout="this.src='ico/schoolSet.png'" border=0></a>
	
				<br>
				Seleziona l'indirizzo:
				<select id="indirizzo" name="indirizzo">
					<?php echo $opt->ShowIndirizzi1($info_scuola['id_scuola']); ?>
				</select>
				<br>

				Seleziona la sezione:
				<select id="sezione" name="sezione">
					<?php echo $opt->ShowSezioni1($info_scuola['id_indirizzo']); ?>
				</select>
				<br>
	
				Seleziona la classe:
				<select id="classe" name="classe">
					<?php echo $opt->ShowClassi1($info_scuola['id_sezione']); ?>
				</select>
				<br>
				<div class="messaggi"></div>
				
				<input type="submit" id="mybutton" value="Salva" disabled="disabled" class="modifica_u">
			</form>
				
			</body>
		</html>
<?php
	}
	else{
		header("location:index.php");
	}
?>