<?php // every page needs to start with these basic things

// I'm using a separate config file. so pull in those config values
require(realpath("./include/php_mysql_class/config.inc.php"));

// pull in the file with the database class
require(realpath("./include/php_mysql_class/Database.singleton.php"));

// create the $db singleton object

class Validate
{	
 	const PREFIX_BOY = "ด.ช.";
	const PREFIX_GIRL = "ด.ญ.";
	const PREFIX_MEN = "นาย";
	const PREFIX_WOMEN = "นางสาว";

	var $debug = false;
	var $debug_trace = "============================================================================<br/>
						:debug v<br/>";
	var $db;
  
	public function __constructor()
	{
		//nothing
	}

	public static function get_prefix(){
		return array(self::PREFIX_BOY, self::PREFIX_GIRL, self::PREFIX_MEN ,self::PREFIX_WOMEN);
	}

	public function validates($var = array(),$field,$param = array()) 	
	{
		
		/********************************************************************
		*
		* $data = $student , $fieldname , array( "presence" => true,
		*								"format" => "pattern", 
		*								"length" => 100, 
		*								"uniqueness" => true,
		*								"inclusion" => [1 , 2, 3 , 4],
		*								"exclusion" => [1 , 2, 3 , 4],
		*								"numericality" => true );
		*
		********************************************************************/
		
		$ret = false; 
		$count = 1 ;

		if($this->debug){
			$debug_trace +=  "start $var = ".$var."<br/>";
			$debug_trace +=  "     $field= ".$field."<br/>";
			$debug_trace +=  "    $param = ".var_dump($param)."<br/>";
			$debug_trace +=  "<hr/>";
		}
	
		foreach($param as $key => $value){
		
			if($this->debug) $debug_trace +=  "<br/>count = ".$count++." and key = ".$key."  ";
		
			switch($key){
				case "numericality":
					if ($param["numericality"] == true )
				   		$ret = $this->numericality($var[$field]);
						break;
				case "presence" : 
					$ret =  $this->presence($var[$field]);
					break;
				case "format" : 
					$ret = $this->format($var[$field],$value);
					break;
				case "length" : 
					$ret = $this->length($var[$field],$value);
					break;
				case "uniqueness" : 
					if ($param["uniqueness"] === true )
				   		$ret = $this->uniqueness($field , $var[$field]);
						break;
				case "inclusion" : 
					$ret = $this->inclusion($var[$field],$value);
			    	break;
				case "exclusion" :
					$ret = $this->exclusion($var[$field],$value);
					break;
				}
		
			if ($ret==false) {
				if($this->debug)
					$debug_trace +=  "<br/>** fail at " .$key."<br/>";
				return false;
			}
		
		}
	
		return $ret;
   	}
	
	private function numericality($value) 
	{
	
		if ($this->debug) {
			$debug_trace +=  "<br/>in numericality function with parameter " .$value ; 
		}
	
		if (is_numeric($value))
			return true;
		else
			return false;
		}

	private function length($data,$value = array())
	{
		if ($this->debug) {
		 	$debug_trace +=  "<br/>in length function with parameter " . $data ." and length = ". strlen($data) . "-".var_dump($value) ; 
		}
	
 	
		@$minimum = $value['min'];
		@$maximum = $value['max'];
		$length = strlen($data);
	
		if(isset($minimum) && isset($maximum)){
			if (($length <= $maximum) && ($length >= $minimum)){
				return true;
			}
			else
				return false;
			
		}else if (isset($minimum) && !isset($maximum)){
			if ($length < $minimum)
				return false;
			else 
				return true;
		}else if (!isset($minimum) && isset($maximum)){
			if ($length > $maximum)
				return false;
			else
				return true;
		}else {
			return false;
		}
	}

	private function uniqueness($field,$value)
	{
		if ($this->debug) {
			$debug_trace +=  "<br/>in uniqueness function with parameter " . $field. "-".$value ; 
		}
	
		if ($this->is_exists($field,$value))
			return false;
		else 
			return true;
	
	}
	
	private function presence($value)
	{
		if ($this->debug) {
			$debug_trace +=  "<br/>in presence function with parameter " .$value ; 
		}
		if (empty($value))
			return false;
		else
			return true;
	}

	private function format($data,$pattern)
	{
		if ($this->debug) {
			$debug_trace +=  "<br/>in format function with parameter " . $data . "-".$pattern ; 
		}
	
		if (preg_match($pattern,$data)) 
			return true;
		else
			return false;
	
	}

	private function inclusion($field,$array = array())
	{
		if ($this->debug) {
			$debug_trace +=  "<br/>in inclusion function with parameter " . $field . "- and array =" .print_r($array) ; 
		}
		if (in_array($field,$array)) 
			return true;
		else 
			return false;
	}

	private function exclusion($field,$array = array()){
		if ($this->debug) {
			$debug_trace +=  "<br/>in exclusion function with parameter " . $field . "- and array =" .print_r($array) ; 
		}
		if (in_array($field,$array)) 
			return false;
		else 
			return true;
	}

	private function is_exists($field,$value)
	{
		if ($this->debug) {
			$debug_trace +=  "<br/>in is_exists function with parameter " . $field . "-" .$value ;
		}
		
	
		$this->db = Database::obtain(DB_SERVER,DB_USER,DB_PASS,DB_DATABASE);
		$this->db->connect();
	
		if(is_string($value)) 
	   		$v = "'".$this->db->escape("$value")."'";
		else if (is_int($value))
	   		$v = $value;
		else 
	   		return false;
    
		$sql="SELECT `".$field."` FROM `student` WHERE $field=".$v;
    	$row = $this->db->query_first($sql);
        
        $this->db->close();
    	// if user exists
    	if(!empty($row['student_id'])) 
        	return true;
	
    	else 
        	return false;
	}
	
	public function show_debug($bool)
	{
		$this->debug = $bool;
	}
	
	public function debug_flush(){
		echo  $this->debug_trace;
		echo "<br/>============================================================================" ;
	}
}
?>