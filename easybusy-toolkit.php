<?php

function login($username,$password)
{
        global $dbh;
        $password=md5($password);
        $stmt = $dbh->prepare("SELECT * FROM account WHERE username=? and password=?");
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $password);
 
        $stmt->execute();
 
        if($result=$stmt->fetch()){
                return $result["level"];
        }else{
                return "false";
        }
       
}
 
function restrict_zone($zone)
{      
        global $dbh;
        $stmt = $dbh->prepare("SELECT * FROM meta_data WHERE `group`='operatorsip' and `value`=?");
        $stmt->bindParam(1, $zone);
        $stmt->execute();
        if($stmt->fetch()){
                return true;
        }else{
                return false;
        }
       
}
 
function excute_config_db($group_name)
{
        global $dbh;
        $query="SELECT `name`,`value` FROM meta_data WHERE `group`='$group_name'";
        $result = $dbh->query($query);
        $tmp="";
        while($row=$result->fetch()){
                $tmp.='$'.$row["name"].'="'.$row["value"].'";';
        }
        return $tmp;   
}
 
function _l($word)
{
        global $dbh,$language;
        $query="SELECT `value` FROM meta_data WHERE `group`='$language' AND `name`='$word'";
        $result = $dbh->query($query);
        if($colum=$result->fetchColumn())
                echo $colum;
        else
                echo $word;
}
 
function auto_generate_update($table_name,$source,$condition_field)
{
                        global $dbh;
                        $condition="";
                        $set="";
                       
                        foreach($source as $item=>$value){
                                if(in_array($item,$condition_field)){
                                        $condition.="`".addcslashes($item, '%_')."`='".$value."' AND ";
                                }else{
                                        $set.="`".addcslashes($item, '%_')."`='".$value."' , ";
                                }      
                        }
                       
                        $condition=substr($condition,0,strlen($condition)-4);
                        $set=substr($set,0,strlen($set)-3);
                        $sql="UPDATE $table_name SET $set WHERE $condition";
                       
                       
                        if($dbh->query($sql))
                                return true;
                        else
                                return false;
}
 
function auto_generate_delete($table_name,$source)
{                      
                        global $dbh;
                        $condition="";
                        foreach($source as $item=>$value)
                                $condition .="`".addcslashes($item, '%_')."`='".addcslashes($value, '%_')."' AND";
                               
                        $condition=substr($condition,0,strlen($condition)-4);
                        $sql="DELETE FROM $table_name WHERE $condition";
                       
                        if($dbh->query($sql))
                                return true;
                        else
                                return false;
}
 
function auto_generate_insert($table_name,$source,$eskape=null)
{
 
                        global $dbh;
            $column="";
            $columnvalue="";
 
            foreach($source as $item=>$value){
                                if(isset($eskape))
                                        if(in_array($item,$eskape))
                                                continue;
                                if(!is_array($value)){
                                        $column.="`".addcslashes($item, '%_')."`,";
                                        $columnvalue.="'".addcslashes($value, '%_')."',";
                                        }else{
                                                foreach($value as $tmp){
                                                        $value2.=addcslashes($tmp, '%_').",";
                                                }
                                        $column.="`".addcslashes($item, '%_')."`,";
                                        $columnvalue.="'".$value2."',";
                                        $value2="";
                                                }
                                }
                        $column=substr($column,0,strlen($column)-1);
 
            $columnvalue = substr($columnvalue,0,strlen($columnvalue)-1);
            $sql = "INSERT INTO $table_name ($column) VALUES ($columnvalue) ";
           
            if($dbh->query($sql))
                                return true;
                        else
                                return false;
                                }
                                
# recursively remove a directory
function rrmdir($dir) {
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
       }
     }
     reset($objects);
     rmdir($dir);
   }
 }

function extractor($source,$destination){
		$zip = new ZipArchive;
	$open = $zip->open($source, ZIPARCHIVE::CHECKCONS);
//	 If the archive is broken(or just another file renamed to *.zip) the function will return error on httpd under windows, so it's good to check if the archive is ok with ZIPARCHIVE::CHECKCONS
	 if ($open === TRUE) {
	 if(!$zip->extractTo($destination)) {
	 die ("Error during extracting");
	 }
	 $zip->close();
}
 }
 
    function smartCopy($source, $dest, $options=array('folderPermission'=>0755,'filePermission'=>0755))
    {
        $result=false;
       
        if (is_file($source)) {
            if ($dest[strlen($dest)-1]=='/') {
                if (!file_exists($dest)) {
                    cmfcDirectory::makeAll($dest,$options['folderPermission'],true);
                }
                $__dest=$dest."/".basename($source);
            } else {
                $__dest=$dest;
            }
            $result=copy($source, $__dest);
            chmod($__dest,$options['filePermission']);
           
        } elseif(is_dir($source)) {
            if ($dest[strlen($dest)-1]=='/') {
                if ($source[strlen($source)-1]=='/') {
                    //Copy only contents
                } else {
                    //Change parent itself and its contents
                    $dest=$dest.basename($source);
                    @mkdir($dest);
                    chmod($dest,$options['filePermission']);
                }
            } else {
                if ($source[strlen($source)-1]=='/') {
                    //Copy parent directory with new name and all its content
                    @mkdir($dest,$options['folderPermission']);
                    chmod($dest,$options['filePermission']);
                } else {
                    //Copy parent directory with new name and all its content
                    @mkdir($dest,$options['folderPermission']);
                    chmod($dest,$options['filePermission']);
                }
            }

            $dirHandle=opendir($source);
            while($file=readdir($dirHandle))
            {
                if($file!="." && $file!="..")
                {
                     if(!is_dir($source."/".$file)) {
                        $__dest=$dest."/".$file;
                    } else {
                        $__dest=$dest."/".$file;
                    }
                    //echo "$source/$file ||| $__dest<br />";
                    $result=smartCopy($source."/".$file, $__dest, $options);
                }
            }
            closedir($dirHandle);
           
        } else {
            $result=false;
        }
        return $result;
    }


function import_db_file($sql_file_adress){
	$pdo_driver=$_POST["db_driver"];
	$dbhost=$_POST["db_host"];
	$dbname=$_POST["db_name"];
	$dsn="$pdo_driver:host=$dbhost;dbname=$dbname";
	$user=$_POST["db_username"];
	$password=$_POST["db_password"];
	$db = new PDO($dsn, $user, $password);
	$db->exec("SET NAMES 'utf8';");
	$sql = file_get_contents($sql_file_adress);
	$qr = $db->exec($sql);
}


function replace_in_file($search_str,$replace_str,$file_address){
	// read the file
	$file = file_get_contents($file_address);
	// replace the data
	$file = str_replace($search_str, $replace_str, $file);
	// write the file
	file_put_contents($file_address, $file);
}
