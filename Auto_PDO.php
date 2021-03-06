<?php
 /**
 * Auto_PDO class
 * 
 * Automatic CRUD Based on PDO  
 * 
 * @Auther: Farid Ahmadian <ahmadian.farid@yahoo.com>
 * @License: GNU GPL
 * @Date: August 2 2012
 */

class Auto_PDO
{

	private $db,$language;

	/*
	 * initialization
	 * @param string $pdo_driver
	 * @param string $host
	 * @param string $dbname 
	 * @param string $user 
	 * @param string $password 
	 */
	public function __construct($pdo_driver,$host,$dbname,$user,$password)
	{
		$dsn="$pdo_driver:host=$host;dbname=$dbname";
		$this->db = new PDO($dsn, $user, $password);
		
	}
	
	/*
	 * convert input word to destination language
	 * @param string $word 
	 * @return string
	 */
	private function _l($word)
	{
			$query="SELECT `value` FROM meta_data WHERE `group`='".$this->language."' AND `name`='$word'";
			$result = $this->db->query($query);
			if($colum=$result->fetchColumn())
					return $colum;
			else
					return $word;
	}

	/*
	 * generate and execute update SQL
	 * @param string $table_name 
	 * @param associative array $source
	 * @param array $condition_field
	 * @return  true on success, false on fail
	 */
	public function auto_generate_update($table_name,$source,$condition_field)
	{
         
                $condition="";
                $set="";
                       
		foreach($source as $item=>$value)
		{
                 	if(in_array($item,$condition_field))
                        	 $condition.="`".$item."`='".$value."' AND ";
			else
                                 $set.="`".$item."`='".$value."' , ";
		}
                       
		$condition=substr($condition,0,strlen($condition)-4);
		$set=substr($set,0,strlen($set)-3);
		$sql="UPDATE $table_name SET $set WHERE $condition";
                       
                       
		if($this->db->query($sql))
			return true;
		 else
			 return false;
	}
 
 	/*
	 * generate and execute delete SQL
	 * @param string $table_name 
	 * @param associative array $source
	 * @return  true on success, false on fail
	 */
	function auto_generate_delete($table_name,$source)
	{
                $condition="";
                foreach($source as $item=>$value)
                	$condition .="`".$item."`='".$value."' AND";
                       
                $condition=substr($condition,0,strlen($condition)-4);
                $sql="DELETE FROM $table_name WHERE $condition";
               
                if($this->db->query($sql))
                        return true;
                else
                        return false;
	}
 
 	/*
	 * generate and execute insert SQL
	 * @param string $table_name 
	 * @param associative array $source
	 * @param array $escape
	 * @return  true on success, false on fail
	 */
	function auto_generate_insert($table_name,$source,$escape=null)
	{
 

		$column="";
		$columnvalue="";

		foreach($source as $item=>$value)
		{
			if(isset($escape))
			        if(in_array($item,$escape))
			                continue;

			if(!is_array($value))
			{
			        $column.="`".$item."`,";
			        $columnvalue.="'".$value."',";
		        }
			else
			{
			                foreach($value as $tmp)
					{
			                        $value2.=$tmp.",";
			                }

			        $column.="`".$item."`,";
			        $columnvalue.="'".$value2."',";
			        $value2="";

	                }

		}

		$column=substr($column,0,strlen($column)-1);

		$columnvalue = substr($columnvalue,0,strlen($columnvalue)-1);
		$sql = "INSERT INTO $table_name ($column) VALUES ($columnvalue) ";

		if($this->db->query($sql))
			return true;
		else
			return false;
	
	}
	
	/*
	 * generate dynamic data table 
	 * @param string $table_name 
	 * @param string $where
	 * @param string $key
	 * @param string $style_class
	 * @param string $lang
	 * @return  string
	 */
	function show($table_name,$where=NULL,$key=NULL,$style_class=NULL,$lang=NULL){
		$column=""; 
		$number=0;

		$column.="<tr>";		
		if($where === NULL || strpos($where,'*') !== FALSE)
		{
			$column_names="select DISTINCT column_name from information_schema.columns where table_name = '".$table_name."'";		
			$result_column_names= $this->db->query($column_names);
			for($i=1;$list = $result_column_names->fetch(PDO::FETCH_ASSOC);$i++)
			{	
					if($lang===NULL)
					{				
						$column.="<th>".$list['column_name']."</th>";
					}
					else
					{
						$this->language = $lang;
						$column.="<th>".$this->_l($list['column_name'])."</th>";
					}
						
					$number++;
			}	
		}
		else
		{
			$tmp = explode(" ",$where);
			$tmp = explode(",",$tmp[1]);
			foreach($tmp as $column_name)
			{
					if($lang===NULL)
					{				
						$column.="<th>".$column_name."</th>";
					}
					else
					{
						$this->language = $lang;
						$column.="<th>".$this->_l($column_name)."</th>";
					}
						
					$number++;
			}

		}
		$column.="</tr>";		
		
		
		if($where === NULL)
			$query="select * From ".$table_name;
		else
			$query=$where;
		
		$result= $this->db->query($query);
		
		for($i=1;$list = $result->fetch(PDO::FETCH_NUM);$i++)	
		{
			$column.="<tr>";
			for($j=0;$j<$number;$j++)
			{
				
				if($j==0 && $key !== NULL)
				{
					$column.="<td>";
					foreach($key as $name=>$value)
					{
						$column.="<a href='".$value."?id=".$list[$j]."'>".$name."</a>&nbsp;";
					}
					$column.="</td>";
				}
				else
				{
					$column.="<td>&nbsp;".$list[$j]."</td>";
				}
			}
			$column.="</tr>";
		}
		
		if($style_class===NULL)
			return "<table border='1' style='width:100%;text-align:center;'>".$column."</table>";
		else
			return "<table ".$style_class." >".$column."</table>";
	}



}
