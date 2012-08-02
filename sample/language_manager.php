<?php
/*
 * index.php
 * 
 * Copyright 2012 Unknown <ahmadian.farid@yahoo.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Auto_PDO Sample,PRO TODO List </title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="generator" content="Geany 0.21" />
	<link href="bootstrap.min.css" rel="stylesheet">
</head>

<body>
<a href="index.php" class="btn btn-success" style="width:100%">Back to TODO Manager</a> 
<hr>
<?php
	require_once("db.php");
	
	if(isset($_POST['name']))
	{

		if($mydb->auto_generate_insert("meta_data",$_POST))
			echo '<div class="fade in alert alert-success">
        <a class="close" data-dismiss="alert">×</a>
        Word Successfuly Inserted</div>';
		else
			echo '<div class="fade in alert alert-error">
        <a class="close" data-dismiss="alert">×</a>
        Sorry! An Error Has occurred</div>';
	}
?>


<form action="" method="post">
	<ul style="list-style-type:none;">
		<li>SOURCE WORD</li>
		<li><input type="text" name="name" /></li>
		<li>DESTIONATION WORD</li>
		<li><input type="text" name="value" /></li>
		<li>DESTINATION LANGUAGE</li>
		<li><input type="text" name="group" /></li>
		<li><input type="submit" value="save" class="btn btn-primary" /></li>
	</ul>
</form>

<?php
$key=array("Delete"=>"language_delete.php");
	echo $mydb->show("meta_data",NULL,$key);
?>
</body>

</html>
