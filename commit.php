<?php 

/* 

VS SCORM 1.2 RTE - commit.php
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

//  essential functions
require "subs.php";

//  read database login information and connect
require "config.php";
dbConnect();

// read SCOInstanceID
$SCOInstanceID = $_REQUEST['SCOInstanceID'] * 1;
$course_id = $_REQUEST['course_id'] * 1;
$item_id = $_REQUEST['item_id'] * 1;

$data = $_REQUEST['data'];
if (! is_array($data)) { $data = array($data); }


$sql = "select * from sco_data where student_id={$SCOInstanceID} and organizations_id={$course_id}";

$result = mysql_query($sql);

$start_sco = true;

while($row = mysql_fetch_array($result)){
	$start_sco = false;
	$ss_time = array();
	
	$score_item = unserialize($row['score_item']);
	
	foreach ($data as $varname => $varvalue) {
		// save data to the 'scormvars' table
		writeElement($varname,$varvalue);
		// special cases - set appropriate values in the LMS tables when they are set by the course
		//if ($varname == "cmi.core.score.raw") { setInLMS('TestScore',$varvalue); }
		//if ($varname == "cmi.core.lesson_status") { setInLMS('Finished',$varvalue); }
		if($varname=="cmi.core.score.raw"){
				if( (int) $varvalue > 0){
					$score_item[$item_id] = $varvalue;
				}
		}
	}
	
	


	$sql="UPDATE `sco_data` SET `score_item` = '".serialize($score_item)."'
	where student_id={$SCOInstanceID} and organizations_id={$course_id}";
	mysql_query($sql);
	
}

if ($start_sco) {
	$ss_time = array();
	if($data["cmi.core.session_time"] != ""){
		$ss_time[] = $data["cmi.core.session_time"];
	}
	
	$lesson_all = array();
	$lesson_graduate = array();
	$score_item = array();
	$lesson_graduate_ps = 0;
	
	$sql="SELECT * FROM items WHERE items.organizations_id ={$course_id} and parent_id !=0 order by item_id asc";
	$result = mysql_query($sql);

	while($row = mysql_fetch_array($result)){
		if(count($lesson_graduate) <= 0){
			$lesson_graduate[] = $row['item_id'];
		}
		$lesson_all[] = $row['item_id'];
	}
	
	$lesson_graduate_ps = floor((count($lesson_graduate)/count($lesson_all))*100);
	
	$sql = "INSERT INTO `sco`.`sco_data` (
			`sco_id` ,`organizations_id` ,`entry` ,`location` ,`suspend_data` ,`launch_data` ,`student_id` ,`raw_score` ,`min_scrore` ,`max_scrore` ,`mastery_score` ,`num_count` ,`score` ,`total_time` ,`session_time` ,`exit` ,`start_time` ,`finish_time` ,`status`
			,`last_update`
			,`lesson_all`
			,`lesson_graduate`
			,`score_item`
			,`lesson_graduate_ps`
			)
			VALUES (
			NULL , '{$course_id}', '', '".$data["cmi.core.lesson_location"]."', '', '', '{$SCOInstanceID}', '', '', '', '', '', '', '', '".$data["cmi.core.session_time"]."','', NOW( ) , '', '".$data["cmi.core.lesson_status"]."'
			, NOW( )
			, '".serialize($lesson_all)."'
			, '".serialize($lesson_graduate)."'
			, '".serialize($score_item)."'
			, '".$lesson_graduate_ps."'
			)";
			
	$result = mysql_query($sql);

}
print "true";

?>