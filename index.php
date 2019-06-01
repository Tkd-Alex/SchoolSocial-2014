<?php
	session_start(); //Apro la sessione
	if($_SESSION["utente"]!="") //Controllo se è stato effettuato il login
		header("location: home.php");
?>

<html>
	<body>
		<head>
			<title>School Social</title>
			<link rel="stylesheet" type="text/css" href="style.css"> 
			
			<script src="script/jquery-1.9.1.min.js"></script>
			<script>
				$(document).ready(function(){
					//Slide
					$('#reg').hide();
					$('#log').hide();
					
					$("#in").click(function(){
						$('#info').slideToggle();
						$('#reg').hide();
						$('#log').hide();
					});
					
					$("#ac").click(function(){
						$('#log').slideToggle();
						$('#reg').hide();
						$('#info').hide();
					});
					
					$("#re").click(function(){
						$('#reg').slideToggle();
						$('#log').hide();
						$('#info').hide();
					});
					
					//Controllo sulle password
					$( ".v_password, .password").keyup(function () {
						if($( ".password" ).val() =="" && $( ".v_password" ).val()==""){
							$(".mespsw").html('<font color="#910101">Inserisci la password in entrambi i campi.</font>');
						}
						else{
							if( $( ".password" ).val() == $( ".v_password" ).val()){
								$(".mespsw").html('<font color="#07810d">Password corrispondenti. Puoi procedere con la registrazione.</font>');
							}
							else{
								$(".mespsw").html('<font color="#910101">Le password inserite non concidono riprova.</font>');
							}
						}
					})
					.keyup();

					//Controllo se ci sono campi vuoti
					$( window ).change(function(){
						console.log( $(".nome").val() );
						if ( 
							 ( $(".nome").val() == "" ) ||
							 ( $(".cognome").val() == "" ) ||
							 ( $(".v_password").val() == "" ) ||
							 ( $(".password").val() == "" ) ||
							 ( $(".mail").val() == "" )
						){
							$(".messaggi").html('<font color="#910101">Non puoi registrarti al sito finch&egrave; non avrai riempito tutti i campi.</font>');
							$(".registra").attr("disabled", "disabled"); 
						}
						else{
							$(".messaggi").html('<font color="#07810d">Perfetto, puoi registrarti al sito.</font>');
							$(".registra").removeAttr("disabled"); 
						}
					});
				});		
			</script>
		
			<div id="pat"></div>
			
			<div id="containerindex">
				<div id="logo">
					<img src="logogrande.png" height=225 width=450>
				</div>
				
				<div id="contindex2">
					<div id="info">
						<iframe width="100%" height="200" class="tscplayer_inline" name="tsc_player" src="Trailer/Trailer_player.html" scrolling="no" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
						<br><br>
					</div>
					
					
					<div id="reg">
						<form method= "post" action="registrato.php">
							Nome: <input type="text" id="text_area" name="nome" class="nome"> <br>
							Cognome: <input type="text" id="text_area" name="cognome" class="cognome"> <br>
							E-Mail: <input type="email" id="text_area" name="mail" class="mail"> <br>
							Password: <input type="password" id="text_area" name="password" class="password"> 
							<br>
							Conferma password: <input type="password" id="text_area" name="v_password" class="v_password"> 
							<div class="messaggi"></div>
							<div class="mespsw"></div>
							<input type="submit" id="mybutton" value="Registrati" class="registra" disabled="disabled">
						</form>
					</div>
					
					<script>
						
					</script>
					
					<div id="log">
						<form method= "post" action="login.php">
							Mail: <input type="text" id="text_area" name="mail"> <br>
							Password: <input type="password" id="text_area" name="password"> <br>
							<input type="submit" id="mybutton" value="Login">
						</form>
					</div>
					
					<div id="bottoni">
						<input type="submit" id="in" value="Info">
						<input type="submit" id="ac" value="Accedi">
						<input type="submit" id="re" value="Registrati">
					</div>
				</div>
			</div>
			
		
	</body>
</html>