<?php
	
	session_start();
	if($_SESSION["utente"]!=""){
	
		include("settings.php");
		
		if ($con){
				$db=mysql_select_db($dbselected);
		}
		
	include_once 'select.class.php';
	$opt = new SelectList();
	
	$query = "SELECT * FROM scuole WHERE id_scuola = ".$_REQUEST["sc"]."";
	$info_scuola_ris = mysql_query($query);
	if($info_scuola_ris>0)
		$info_scuola = mysql_fetch_array($info_scuola_ris);
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
							
							<?php if( ($info_scuola["regione"] && $info_scuola["provincia"] && $info_scuola["comune"]) ==null ){ ?>
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
							
							$("#regioni").val("<?php echo $info_scuola["regione"] ;?>");
							$("#province").val("<?php echo $info_scuola["provincia"] ;?>");
							$("#comuni").val("<?php echo $info_scuola["comune"] ;?>");

						if( ($("#nome").val()=="") || ($("#comuni").val()==0) || ($("#province").val()==0) || ($("#regioni").val()==0) ){
							$(".messaggi_scuola").html('<font color="#910101">Il tasto salva non funzioner&agrave; affinch&egrave; non sarrano riempiti i campi contrassegnati da *.</font>');
							$(".salva_scuola").attr("disabled", "disabled");
						}
						else{
							$(".messaggi_scuola").html('<font color="#07810d">Perfetto, puoi aggiornare le informazioni della scuola.</font>');
							$(".salva_scuola").removeAttr("disabled"); 
						}	
						
						$( window ).change(function(){
							if( ($("#nome").val()=="") || ($("#comuni").val()==0) || ($("#province").val()==0) || ($("#regioni").val()==0) ){
								$(".messaggi_scuola").html('<font color="#910101">Il tasto salva non funzioner&agrave; affinch&egrave; non sarrano riempiti i campi contrassegnati da *.</font>');
								$(".salva_scuola").attr("disabled", "disabled");
							}
							else{
								$(".messaggi_scuola").html('<font color="#07810d">Perfetto, puoi aggiornare le informazioni della scuola.</font>');
								$(".salva_scuola").removeAttr("disabled"); 
							}	
						});	
			});	
			</script>
					
				
				<form method= "post" action="salva_scuola.php">
					<fieldset>
					<legend>Modifica dettagli scuola</legend>
						
						Nome istituto <font color="#910101">*</font>: <input type="text" id="nome" name="nome" value='<?php echo $info_scuola["nome"] ;?>'> <br>
						
						Seleziona una regione <font color="#910101">*</font>:
						<select id="regioni" name="regioni">
							<?php echo $opt->ShowRegioni(); ?>
						</select>
						<br>

						Seleziona una provincia <font color="#910101">*</font>:
						<select id="province" name="province">
							<?php echo $opt->ShowProvince1($info_scuola["regione"]); ?>
						</select>
						<br>

						Seleziona un comune <font color="#910101">*</font>:
						<select id="comuni" name="comuni">
							<?php echo $opt->ShowComuni1($info_scuola["provincia"]); ?>
						</select>
						<br>
						
						Via : <input type="text" id="via" name="via" value="<?php echo $info_scuola["via"] ;?>"> <br>
						
						Telefono : <input type="text" id="telefono" name="telefono" value="<?php echo $info_scuola["telefono"] ;?>"> <br>
						Fax : <input type="text" id="fax" name="fax" value="<?php echo $info_scuola["fax"] ;?>"> <br>
						Mail : <input type="text" id="mail" name="mail" value="<?php echo $info_scuola["mail"] ;?>"> <br>
						Sito web : <input type="text" id="sitoweb" name="sitoweb" value="<?php echo $info_scuola["sitoweb"] ;?>"> <br>
						
						<div class="messaggi_scuola"></div>
						<input type="submit" id="mybutton" value="Salva" class="salva_scuola" disabled="disabled">
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
