<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Finish Work List</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.21" />
	<link href="bootstrap.min.css" rel="stylesheet">
	<style>
		.farsi{
			direction:rtl;
		}
		.farsi tr td{
						text-align:right;
					}
		.farsi tr th{
						text-align:right;
					}
	</style>
</head>

<body>
	<a href="index.php" class="btn btn-success" style="width:100%">Back to TODO Manager</a> 
<hr>
<?php
	require_once("db.php");
	echo $mydb->show("todo","SELECT work,priority FROM todo WHERE status='done'",NULL,"class='table table-striped  farsi'","FA");
?>
</body>

</html>	
