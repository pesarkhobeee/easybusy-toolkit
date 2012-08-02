<?php
	require_once("db.php");
	$source=array("status"=>"done","id"=>$_GET["id"]);
	$condition_field=array("id");
	$mydb->auto_generate_update("todo",$source,$condition_field);
	header("location:index.php");
 
