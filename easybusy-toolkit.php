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
