<?php
	session_start();
	
	$cerca=$_REQUEST["cerca"];

	if($cerca==""){
		//header("location:errore.php"); //Valore Vuoto
	}
	else{
		include("settings.php");
		if ($con){

			$db=mysql_select_db($dbselected);
			
			$fullname = explode(" ",$cerca); //Esplode nome e cognome
			$dim = count($fullname); //Conta i valori

			//Il valore trovato è uno esegue normale query
			if($dim==1){ 
			$query="SELECT * 
					FROM  utenti 
					WHERE ( CONVERT(  `nome`  USING utf8 ) LIKE  '%".$cerca."%') 
					OR ( CONVERT(  `cognome`  USING utf8 ) LIKE  '%".$cerca."%')";
			}
			//Altrimenti ci creeamo la query noi concatenando le stringe
			else{
					$i=0;
					$query="SELECT *  FROM `utenti` WHERE
							(CONVERT(`nome` USING utf8) LIKE '%".$fullname[$i]."%' 
							OR CONVERT(`cognome` USING utf8) LIKE '%".$fullname[$i]."%') " ;
					
					while($i<$dim){
							$query2= "AND (CONVERT(`nome` USING utf8) LIKE '%".$fullname[$i]."%' 
							OR CONVERT(`cognome` USING utf8) LIKE '%".$fullname[$i]."%') " ;
							$query = $query.$query2;
							$i++;
					}
			} //Fine concatenazione
		
			$ris=mysql_query($query);
			$riga=mysql_fetch_array($ris);
			$n_trovati =mysql_num_rows($ris);

			if($n_trovati==1){
				header("location:user.php?id=".$riga["id_utente"].""); //Account trovato
			} 
			else if($n_trovati>1){
				$i=0;
				while($riga){
					$id_trovati[$i]=$riga["id_utente"];
					$i++;
					$riga=mysql_fetch_array($ris);
				}
				
				?>
				<form name="myform" method="post" action="trovati.php">
					<input type="hidden" name="vettore" value='<?php print(serialize($id_trovati)); ?>'>
					<script language="JavaScript">document.myform.submit();</script>
				</form>
				<?		
			}			
			else{
				header("location:errore.php"); //Errore
			}
			mysql_close($con);
		}
	}

?>