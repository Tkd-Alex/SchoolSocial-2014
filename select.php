<?php

include_once 'select.class.php';
$opt = new SelectList();

if(isset($_POST['id_reg']))
{
	echo $opt->ShowProvince();
	die;
}

if(isset($_POST['id_pro']))
{
	echo $opt->ShowComuni();
	die;
}

//Scuole

if(isset($_POST['id_scuola']))
{
	echo $opt->ShowIndirizzi();
	die;
}

if(isset($_POST['id_indirizzo']))
{
	echo $opt->ShowSezioni();
	die;
}

if(isset($_POST['id_sezione']))
{
	echo $opt->ShowClassi();
	die;
}

//SchoolControl

if(isset($_POST['id_scuola_info']))
{
	echo $opt->ShowInfoScuola($_POST['id_scuola_info']);
	die;
}

?>