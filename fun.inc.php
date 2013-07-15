<?php
include("config.php");
function db_connect(){	
	$connection_server=mysql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD);
	if(!$connection_server){
	   return false;	
	}
	$connection_db=mysql_select_db(DB_DB);
	if(!$connection_db){
		return false;
	}	
		return $connection_server;
}	

function escape_data($data) { 
	db_connect();
	$data = stripslashes($data);	
	return mysql_real_escape_string(trim($data));
}

function get_tables(){
	db_connect();
	$result = mysql_query("SHOW tables FROM ".$dbname);
		$tables = array();
		$q = 0;
		$v = 0;
		while ($row = mysql_fetch_array($result)) {
			//Fetch all table 
			$tables['tables'][$q] = array("table_name" => $row[0]);
				//If you want to pull more details for the table then set as true
				if($deep){
					//This is to fetch fields
					$query_level = mysql_query("SHOW columns FROM ".$row[0]);
					if($query_level){
						$fields = array();
						while($fields = mysql_fetch_array($query_level)){
							$tables['tables'][$q]['table_fields'][$v] = $fields;
							$v++;
						}
					}
				}
			$q++;
		}	
		return $tables;
}

function get_table_structure($tablename){
	db_connect();
	$tables = array();
		$query_level = mysql_query("SHOW columns FROM ".$tablename);
				if($query_level){
						$v = 0;
						$fields = array();
						while($fields = mysql_fetch_array($query_level)){
							$tables[$v] = $fields;
							$v++;
						}
				}
		return $tables;
}

function update_simple($table,$data,$where) {
		 db_connect();
		 $query = "UPDATE $table SET $data $where"; 
		 $result = mysql_query($query)or die("query failed ".mysql_error());
		//$result = db_results($result);
		return mysql_affected_rows();
      }
 function db_results($result){
		$res_array = array();
		for($count=0;$row = mysql_fetch_array($result);$count++)

		{
          	$res_array[$count] = $row;
	    }

		return $res_array;
	}

