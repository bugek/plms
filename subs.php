<?php 
include 'function.php';
$userdata = get_userdata();
/* 

VS SCORM 1.2 RTE - subs.php
Rev 2009-11-30-01
Copyright (C) 2009, Addison Robson LLC

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, 
Boston, MA 02110-1301, USA.

*/

// ------------------------------------------------------------------------------------
// Database-specific code
// ------------------------------------------------------------------------------------


function dbConnect() {

	// database login details
	global $dbname;
	global $dbhost;
	global $dbuser;
	global $dbpass;

	// link
	global $link;

	// connect to the database
	$link = mysql_connect($dbhost,$dbuser,$dbpass);
	mysql_select_db($dbname,$link);

}

function readElement($VarName) {

	global $link;
	global $SCOInstanceID;
	global $course_id;
	
	$field_name = "";
	
	switch ($VarName) {
		case "cmi.core.score.raw":
			$field_name = "raw_score";
			break;
		case "cmi.core.score.min":
			$field_name = "min_score";
			break;
		case "cmi.core.score.max":
			$field_name = "max_score";
			break;
		case "cmi.launch_data":
			$field_name = "launch_data";
			break;
		case "cmi.suspend_data":
			$field_name = "suspend_data";
			break;
		case "cmi.lesson_location":
			$field_name = "location";
			break;
		case "cmi.core.lesson_status":
			$field_name = "status";
			break;
		case "cmi.entry":
			$field_name = "entry";
			break;
		case "cmi.core.exit":
			$field_name = "exit";
			break;
		case "cmi.core.total_time":
			$field_name = "total_time";
			break;
		case "cmi.core.session_time":
			$field_name = "session_time";
			break;
	}

	
	$sql = "select * from sco_data where student_id={$SCOInstanceID} and organizations_id={$course_id}";
	$result = mysql_query($sql,$link);

	while($row = mysql_fetch_array($result)){
		/*if($field_name == "session_time"){
			$ss_time = array();
			$ss_time = unserialize($row['session_time']);
			$value = array_pop($ss_time);
		}else{*/
			$value = $row[$field_name];
		//}
	}
	return $value;

}

function writeElement($VarName,$VarValue) { 

	global $link;
	global $SCOInstanceID;
	global $course_id;
	
	$field_name = "";
			
	$safeVarName = "";
	$safeVarValue = "";
	
	$sql = "select * from sco_data where student_id={$SCOInstanceID} and organizations_id={$course_id}";
	$result = mysql_query($sql,$link);
	$row = mysql_fetch_array($result);
	
	
	switch ($VarName) {
		case "cmi.core.score.raw":
			$field_name = "raw_score";
			break;
		case "cmi.core.score.min":
			$field_name = "min_score";
			break;
		case "cmi.core.score.max":
			$field_name = "max_score";
			break;
		case "cmi.launch_data":
			$field_name = "launch_data";
			break;
		case "cmi.suspend_data":
			$field_name = "suspend_data";
			break;
		case "cmi.core.lesson_location":
			$field_name = "location";
			break;
		case "cmi.core.lesson_status":
			$field_name = "status";
			break;
		case "cmi.core.entry":
			$field_name = "entry";
			break;
		case "cmi.core.exit":
			$field_name = "exit";
			break;
		case "cmi.core.total_time":
			$field_name = "total_time";
			break;
		case "cmi.core.session_time":
			$field_name = "session_time";
			/*$ss_time = array();
			$ss_time = unserialize($row['session_time']);
			$ss_time[] = $VarValue;
			$safeVarValue = serialize($ss_time);
			*/
			break;
	}

	$safeVarName = mysql_escape_string($VarName);
	
	if($safeVarValue ==""){
		$safeVarValue = mysql_escape_string($VarValue);
	}
	$sql_update = array();
	$sql_update_text = "";
	
	$no_update = false;
	if($field_name == "status"){
		if($row['status'] == "completed" || $safeVarValue == "return"){
			if(isset($_SESSION["user_data"]) && $_SESSION["user_data"]['lastlogin_date'] != $row['sco_lastlogin_date']){
				$safeVarValue = "return";
			}else{
				$no_update = true;
			}
		}else if($row['status'] == "return"){
			$no_update = true;
		}else if($safeVarValue == "completed" && $row['lesson_graduate_ps'] != 100){
			$no_update = true;
		}
		if($safeVarValue == "completed" && !$no_update && isset($_SESSION["user_data"])){
			$sql_update[] = "sco_lastlogin_date = '".$_SESSION["user_data"]['lastlogin_date']."'";
		}
	}
	
	$sql_update[] = "last_update = NOW()";
	
	if(count($sql_update) > 0){
		$sql_update_text = ",".implode(",",$sql_update);
	}
	if(!$no_update){
		mysql_query("update sco_data set {$field_name}='{$safeVarValue}' {$sql_update_text} where student_id={$SCOInstanceID} and organizations_id=".$course_id,$link);
	}
	return;
}
/*
function initializeElement($VarName,$VarValue) {

	global $link;
	global $SCOInstanceID;

	// make safe for the database
	$safeVarName = mysql_escape_string($VarName);
	$safeVarValue = mysql_escape_string($VarValue);

	// look for pre-existing values
	$result = mysql_query("select VarValue from scormvars where ((SCOInstanceID=$SCOInstanceID) and (VarName='$safeVarName'))",$link);

	// if nothing found ...
	if (! mysql_num_rows($result)) {
		mysql_query("insert into scormvars (SCOInstanceID,VarName,VarValue) values ($SCOInstanceID,'$safeVarName','$safeVarValue')",$link);
	}

}

function initializeData($VarName,$VarValue) {

	global $link;
	global $SCOInstanceID;
	global $course_id;

	// make safe for the database
	$safeVarName = mysql_escape_string($VarName);
	$safeVarValue = mysql_escape_string($VarValue);

	// look for pre-existing values
	$result = mysql_query("select VarValue from scormvars where ((SCOInstanceID=$SCOInstanceID) and (VarName='$safeVarName'))",$link);

	// if nothing found ...
	if (! mysql_num_rows($result)) {
		mysql_query("insert into scormvars (SCOInstanceID,VarName,VarValue) values ($SCOInstanceID,'$safeVarName','$safeVarValue')",$link);
	}

}
*/
function initializeSCO() {

	global $link;
	global $SCOInstanceID;
	global $course_id;
	global $item_id;
	global $userdata;

	// has the SCO previously been initialized?
	//echo "select count(*) from sco_data where (student_id=$SCOInstanceID) and (organizations_id=$course_id)";
	$result = mysql_query("select count(*) from sco_data where (student_id=$SCOInstanceID) and (organizations_id=$course_id)",$link);
	list($count) = mysql_fetch_row($result);

	
	$cache_array = array();
	
	// elements that tell the SCO which other elements are supported by this API
	$cache_array['cmi.core._children'] = "student_id,student_name,lesson_location,credit,lesson_status,entry,score,total_time,exit,session_time";
	$cache_array['cmi.core.score._children'] = "raw";
	
	// student information
	$cache_array['cmi.core.student_name'] = $userdata['data']['student_name'];
	$cache_array['cmi.core.student_id'] = $userdata['data']['student_id'];
	
	// not yet initialized - initialize all elements
	if (! $count) {
		
		// test score
		$cache_array['cmi.core.score._children'] = "";
		//$cache_array['cmi.core.score.raw'] = "";
		$cache_array['cmi.core.score.min'] = "";
		$cache_array['cmi.core.score.max'] = "";
		$cache_array['adlcp:masteryscore'] = getFromLMS('adlcp:masteryscore');

		// SCO launch and suspend data
		$cache_array['cmi.launch_data'] = getFromLMS('cmi.launch_data');
		$cache_array['cmi.suspend_data'] ="";

		// progress and completion tracking
		
		$cache_array['cmi.core.lesson_location'] ="";
		$cache_array['cmi.core.credit'] ="no-credit";
		$cache_array['cmi.core.lesson_status'] ="not attempted";
		$cache_array['cmi.core.entry'] ="ab-initio";
		$cache_array['cmi.core.exit'] ="";
		
		// seat time
		$cache_array['cmi.core.total_time'] ="0000:00:00";
		$cache_array['cmi.core.session_time'] ="";
		
		$cache_array['cmi.core.lesson_mode'] ="";

	}else{
		
		$result = mysql_query("select * from sco_data where (student_id=$SCOInstanceID) and (organizations_id=$course_id)",$link);
		while($row = mysql_fetch_array($result)){
		
			// test score
			$cache_array['cmi.core.score._children'] = array($row['min_score'],$row['max_score'],$row['raw_score']);
			//$cache_array['cmi.core.score.raw'] = $row['raw_score'];
			$cache_array['cmi.core.score.min'] = $row['min_score'];
			$cache_array['cmi.core.score.max'] = $row['max_score'];
			$cache_array['adlcp:masteryscore'] = getFromLMS('adlcp:masteryscore');

			// SCO launch and suspend data
			$cache_array['cmi.launch_data'] = $row['launch_data'];
			$cache_array['cmi.suspend_data'] =$row['suspend_data'];

			// progress and completion tracking
			
			$cache_array['cmi.core.lesson_location'] =$row['location'];
			$cache_array['cmi.core.credit'] = "no-credit";
			if($row['status'] == "return"){
				$cache_array['cmi.core.lesson_status'] = "completed";
			}else{
				$cache_array['cmi.core.lesson_status'] = $row['status'];
			}
			
			$cache_array['cmi.core.entry'] = $row['entry'];
			$cache_array['cmi.core.exit'] = $row['exit'];
			
			// seat time
			$cache_array['cmi.core.total_time'] = $row['total_time'];
			/*$session_time_array = unserialize($row['session_time']);
			$cache_array['cmi.core.session_time'] = array_pop($session_time_array);*/
			$cache_array['cmi.core.session_time'] = $row['session_time'];
			$cache_array['cmi.core.lesson_mode'] ="";
		}
	}

	// new session so clear pre-existing session time
	//writeElement('cmi.core.session_time','');

	// create the javascript code that will be used to set up the javascript cache, 
	$initializeCache = "var cache = new Object();var course_id = {$course_id};\n var item_id = {$item_id};\n";
	
	foreach($cache_array as $k=>$v){
		$initializeCache .= "cache['$k'] = '$v';\n";
	}

	// return javascript for cache initialization to the calling program
	return $initializeCache;
}

// ------------------------------------------------------------------------------------
// LMS-specific code
// ------------------------------------------------------------------------------------
function setInLMS($varname,$varvalue) {
	return "OK";
}

function getFromLMS($varname) {
	global $userdata;
	switch ($varname) {

		case 'cmi.core.student_name':
			$varvalue = $userdata['data']['student_name'];
			break;

		case 'cmi.core.student_id':
			$varvalue = $userdata['data']['student_id'];
			break;

		case 'adlcp:masteryscore':
			$varvalue = 0;
			break;

		case 'cmi.launch_data':
			$varvalue = "";
			break;

		default:
			$varvalue = '';

	}

	return $varvalue;

}

?>