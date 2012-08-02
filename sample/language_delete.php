<?php
	require_once("db.php");
	$source=array("id"=>$_GET["id"]);
	$mydb->auto_generate_delete("meta_data",$source);
	header("location:language_manager.php");

