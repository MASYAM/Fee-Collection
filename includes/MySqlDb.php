<?php

require_once ("config.php");

class myDatabase{
    
    private $connection;
    private $magic_quotes_active;
    private $real_escape_string_exists;
    public $last_query;
   
            
    function __construct($user_db_select) 
    {
        $this->open_connection($user_db_select);
        $this->magic_quotes_active = get_magic_quotes_gpc();
	$this->real_escape_string_exists = function_exists( "mysql_real_escape_string" ); // i.e. PHP >= v4.3.0
    }
    
    public function open_connection($user_db_select)
    {
        $this->connection = mysqli_connect("localhost","root","",$user_db_select) or die(mysql_error());
        $this->select_db($user_db_select);
    }
    
    public function select_db($user_db_select)
    {
        $db_select = mysqli_select_db($this->connection,$user_db_select) or die(mysqli_error());
    }

    public function close_connection()
    {
        if(isset($this->connection))
        {
            mysqli_close($this->connection);
            unset($this->connection);
        }
    }
    
    public function query($sql)
    {
       $this->last_query = $sql;
       $query_run = mysqli_query($this->connection, $sql);
       if(!$query_run)
       {
          $error = 'Last query: '.$this->last_query .'<br>Mysql error: '.mysqli_error();
          die($error);
       }
       return $query_run;
    }
    
    public function escape_value( $value ) 
    {	
	if( $this->real_escape_string_exists ) 
        { // PHP v4.3.0 or higher
		// undo any magic quote effects so mysql_real_escape_string can do the work
		if( $this->magic_quotes_active ) { $value = stripslashes( $value ); }
		$value = mysql_real_escape_string( $value );
	} else 
            { // before PHP v4.3.0
		// if magic quotes aren't already on then add slashes manually
		if( !$this->magic_quotes_active ) { $value = addslashes( $value ); }
		// if magic quotes are active, then the slashes already exist
	     }
	return $value;
    }
    
    public function fetch_array($result)
    {
        return mysqli_fetch_array($result);
    }
    
    public function fetch_field($result)
    {
        return mysqli_fetch_field($result);
    }
    
    public function num_rows($result)
    {
        return mysqli_num_rows($result);
    }
    
    public function affected_rows()
    {
        return mysqli_affected_rows($this->connection);
    }
    
    public function insert_id()
    {
        //get the last id inserted over the current db connection
        return mysqli_insert_id($this->connection);
    }
    
   
    
    public function find_by_sql($column,$table,$where,$limit)
    {
        
        if((empty($where) == 1) && (empty($limit) == 1))
        {
            $query_run = $this->query("SELECT $column FROM $table");
        }elseif (empty($where) == 0) 
        {
            $query_run = $this->query("SELECT $column FROM $table WHERE $where");
        }elseif (empty($limit) == 0) 
        {
            $query_run = $this->query("SELECT $column FROM $table LIMIT $limit");
        }
        
        
        if($this->num_rows($query_run) == NULL)
        {
            return 'No Result Found';           
        }else
            {
                while ($row2 = $this->fetch_field($query_run))
                {
                    $field[] = $row2->name;
                }

                while($row = $this->fetch_array($query_run))
                {
                    $result[] = $this->set_result_array($row,$field);
                } 
                return $result;
            }              
    }

    
    private function set_result_array($result,$field)
    {
        foreach($result as $key => $value)
        {
            if(in_array($key,$field))
            {
               if($key !== 0)
               {             
                 $catch[$key] = $value; // just like upper style : $object->username = $record['username'];
               }
            }
        }

        return $catch;
    }
    
    
    public function insert($table,$columns,$values,$check_exist)
    {
        if(empty($check_exist) == TRUE)
        {
                $check = $this->query(" SELECT * FROM $table ");
                $sql = " INSERT INTO $table ($columns) VALUES ($values)";
                $query_run = $this->query($sql);

                return $this->affected_rows() ? 'created succesfully' : 'not been created';
            
            
        }else{
                $check = $this->query(" SELECT * FROM $table WHERE $check_exist ");
                if($this->num_rows($check) == 1)
                {
                    return 'already exist';
                }else 
                    {
                        $sql = " INSERT INTO $table ($columns) VALUES ($values)";
                        $query_run = $this->query($sql);

                        return $this->affected_rows() ? 'created succesfully' : 'not been created';
                    }   
             }
        
    }
      
    
    public function update($table,$set,$where)
    {      
        $sql = " UPDATE $table SET $set WHERE $where";
        $query_run = $this->query($sql);

        return $this->affected_rows() ? 'updated succesfully' : 'not been updated';           
    }
    
    
    public function delete($table,$where)
    {
        $sql = " DELETE FROM $table WHERE $where";
        $query_run = $this->query($sql);

        return $this->affected_rows() ? TRUE : FALSE;
    }
    
    
    
    public function set_checked($selected_value,$specifier)
    {
        foreach($selected_value as $key => $value)
        {            
            $catch[$value] = $specifier; 
        }
        return $catch;
    }
    
    public function fourth_checked($selected_value,$specifier)
    {
        $i = 0;
        foreach($selected_value as $key => $value)
        {            
            $catch[$i][$value] = $specifier;
            $i++;
        }
        return $catch;
    }
    
    public function duplicate_value_check($field)
    {
        $ready_field = array_count_values($field);

        foreach($ready_field as $key => $value)
        {
                if($value > 1 )
                {
                    return 'Duplicate value: "'.$key.'" found';
                    break;          
                }
        }
    }
    
    
    
}



$user115122 = $_SESSION['username'];
//$first_four_words = substr($user115122,0,4);
$user_db_select = get_user_database($user115122);

$db = new myDatabase($user_db_select);

//Refer database according to username
function get_user_database($user)
{
    if($user == 'admincontrol')
    {
       $r = "fee_collection";
    }
    
  return $r ;
}


$getting_4_codes = substr($user_db_select,11,4);











?>
