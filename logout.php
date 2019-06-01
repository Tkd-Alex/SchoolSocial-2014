<?php
session_start();
$_SESSION["utente"]="";
?>
<script>
	alert("Logout effettuato con successo!");
	top.location="index.php";
</script>

