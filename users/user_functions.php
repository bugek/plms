<?php // every page needs to start with these basic things

// I'm using a separate config file. so pull in those config values
require(realpath("./include/php_mysql_class/config.inc.php"));

// pull in the file with the database class
require(realpath("./include/php_mysql_class/Database.singleton.php"));

// create the $db singleton object

class Validate
{
	/*DEFINE("PREFIX_BOY","ด.ช.");
	DEFINE("PREFIX_GIRL","ด.ญ.");
	DEFINE("PREFIX_MEN","นาย");
	DEFINE("PREFIX_WOMEN","นางสาว"); 
	DEFINE("DEBUG",false); */
	
 	const PREFIX_BOY = "ด.ช.";
	const PREFIX_GIRL = "ด.ญ.";
	const PREFIX_MEN = "นาย";
	const PREFIX_WOMEN = "นางสาว";

	private  $debug = true;
	private  $db;
  
	public function __constructor()
	{
		$this->db = Database::obtain(DB_SERVER,DB_USER,DB_PASS,DB_DATABASE);
		$this->db->connect();
	}



// if all validate 
// save data

	public function validates($var = array(),$field,$param = array()) 	
	{
		$ret = false; 
		$count = 1 ;

		if($debug){
			echo "start $var = ".$var."<br/>";
			echo "     $field= ".$field."<br/>";
			echo "    $param = ".var_dump($param)."<br/>";
			echo "<hr/>";
		}
	
		foreach($param as $key => $value){
		
			if($debug) echo "<br/>count = ".$count++." and key = ".$key."  ";
		
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
				   		$ret = $tnis->uniqueness($field , $var[$field]);
						break;
				case "inclusion" : 
					$ret = $this->inclusion($var[$field],$value);
			    	break;
				case "exclusion" :
					$ret = $this->exclusion($var[$field],$value);
					break;
				}
		
			if ($ret==false) {
				echo "<br/>** fail at " .$key."<br/>";
				return false;
			}
		
		}
	
		return $ret;
   	}
	
	private function numericality($value) 
	{
	
	if ($debug) {
		echo "<br/>in numericality function with parameter " .$value ; 
	}
	
	if (is_numeric($value))
		return true;
	else
		return false;
	}

	private function length($data,$value = array())
	{
		if ($debug) {
		 	echo "<br/>in length function with parameter " . $data ." and length = ". strlen($data) . "-".var_dump($value) ; 
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
		if ($debug) {
			echo "<br/>in uniqueness function with parameter " . $field. "-".$value ; 
		}
	
		if (is_exists($field,$value))
			return false;
		else 
			return true;
	
	}
	
	private function presence($value)
	{
	//not blank
		if ($debug) {
			echo "<br/>in presence function with parameter " .$value ; 
		}
		if (empty($value))
			return false;
		else
			return true;
	}

	private function format($data,$pattern)
	{
		if ($debug) {
			echo "<br/>in format function with parameter " . $data . "-".$pattern ; 
		}
	
		if (preg_match($pattern,$data)) 
			return true;
		else
			return false;
	
	}

	private function inclusion($field,$array = array())
	{
		if ($debug) {
			echo "<br/>in inclusion function with parameter " . $field . "-" .print_r($array) ; 
		}
		if (array_search($field,$array)) 
			return true;
		else 
			return false;
	}

	private function exclusion($field,$array = array()){
		if ($debug) {
			echo "<br/>in exclusion function with parameter " . $field . "-" .print_r($array) ; 
		}
		if (array_search($field,$array)) 
			return false;
		else 
			return true;
	}

	private function is_exists($field,$value)
	{
		if ($debug) {
			echo "<br/>in is_exists function with parameter " . $field . "-" .$value ;
		}
		
		$this->db = Database::obtain();
		
		if(is_string($value)) 
	   		$v = "'".$this->db->escape("$value")."'";
		else if (is_int($value))
	   		$v = $value;
		else 
	   		return false;
    
		$sql="SELECT `".$field."` FROM `student` WHERE $field=".$v;
    	$row = $this->db->query_first($sql);
    
    	// if user exists
    	if(!empty($row['student_id'])) 
        	return true;
	
    	else 
        	return false;
	}

}
?>