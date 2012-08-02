<?php
	require_once("db.php");
	$source=array("id"=>$_GET["id"]);
	$mydb->auto_generate_delete("todo",$source);
	header("location:index.php");
