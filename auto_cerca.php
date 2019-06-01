<?php

include("settings.php");

mysql_select_db($dbselected);

$req = "SELECT nome, cognome "
	."FROM utenti "
	."WHERE nome LIKE '%".$_REQUEST['term']."%' OR cognome LIKE '%".$_REQUEST['term']."%' "; 

$query = mysql_query($req);

while($row = mysql_fetch_array($query))
{
	$results[] = array('label' => $row['nome']." ".$row['cognome']);
}

echo json_encode($results);
?>
