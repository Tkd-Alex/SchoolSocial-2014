<?php
	include("settings.php");
		
	if ($con){
			$db=mysql_select_db($dbselected);

			$query= "select * from utente 
			where id_utente='".$_SESSION["utente"]."' " ;

			$ris=mysql_query($query);

			$riga=mysql_fetch_array($ris);
	}
?>

<html lang="en">
	<head>
	<meta charset="utf-8">
	<title>change demo</title>
	<style>
		#scuola_scritta {
		color: red;
		}
		#csi_scritta {
		color: blue;
		}
	</style>
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	</head>
	<body>

		Scuola: <select name="scuola" id="scuola">
			<?php
				$query="select * from scuola";
				$ris=mysql_query($query);
				$riga=mysql_fetch_array($ris);
				echo $riga;
				$i=1;
				while($riga){
					echo '<option value='.$i.'>'.$riga["nome"].'</option>';
					$i++;
					$riga=mysql_fetch_array($ris);
				} 
			?>
		</select> <br><br>
		
		<div id="scuola_scritta"></div>
		
		Classe - Sezione - Indirizzo: <select name="csi" id="csi">
			<?php
				$query="
				
				SELECT classe.classe, sezione.sezione, indirizzo.indirizzo
				FROM classe
				JOIN sezione ON classe.sezione = sezione.id_sezione LEFT JOIN indirizzo ON sezione.indirizzo = indirizzo.id_indirizzo
				
				";
				$ris=mysql_query($query);
				$riga=mysql_fetch_array($ris);
				echo $riga;
				$i=1;
				while($riga){
					echo '<option value='.$i.'>'.$riga["classe"].' - '.$riga["sezione"].' - '.$riga["indirizzo"].'</option>';
					$i++;
					$riga=mysql_fetch_array($ris);
				} 
			?>
		</select> <br><br>
		
		<div id="csi_scritta"></div>

		<script>
			$( "#scuola" ).change(function () {
				var str = "";
				$( "#scuola option:selected" ).each(function() {
					str += $( this ).text() + " ";
				});
				$( "#scuola_scritta" ).text( str );
			})
			.change();
			
			$( "#csi" ).change(function () {
				var str = "";
				$( "#csi option:selected" ).each(function() {
					str += $( this ).text() + " ";
				});
				$( "#csi_scritta" ).text( str );
			})
			.change();
		</script>

		Password: <input type="password" name="password" id="password" class="password"> <br>
		Password: <input type="password" name="v_password" id="v_password" class="v_password"> <br>
		
		<img src="ico/giusto.png" width="100" height="100" class="giusto"/>
		<img src="ico/sbagliato.png" width="100" height="100" class="sbagliato"/>
		
		<script>
		$( ".v_password, .password").keyup(function () {
				if( $( ".password" ).val() == $( ".v_password" ).val() && $( ".password" ).val() !="" && $( ".v_password" ).val()!="" ){
					$(".giusto").show();
					$(".sbagliato").hide();
				}
				else{
					$(".giusto").hide();
					$(".sbagliato").show();
				}
			})
			.keyup();
		</script>
	</body>
</html>

