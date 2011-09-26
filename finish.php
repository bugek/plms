<?php 

/* 

VS SCORM 1.2 RTE - finish.php
Rev 2010-04-30-01
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

// read form variables
$course_id = $_REQUEST['course_id'] * 1;
$SCOInstanceID = $_REQUEST['SCOInstanceID'] * 1;

// ------------------------------------------------------------------------------------
// set cmi.core.lesson_status

// find existing value of cmi.core.lesson_status
$lessonstatus = readElement('cmi.core.lesson_status');
// if it's 'not attempted', change it to 'completed'
if ($lessonstatus == 'not attempted') {
	writeElement('cmi.core.lesson_status','completed');
}

// has a mastery score been specified in the IMS manifest file?
$masteryscore = readElement('adlcp:masteryscore');
$masteryscore *= 1;

if ($masteryscore) {

	// yes - so read the score
	$rawscore = readElement('cmi.core.score.raw');
	$rawscore *= 1;

	// set cmi.core.lesson_status to passed/failed
	if ($rawscore >= $masteryscore) {
		writeElement('cmi.core.lesson_status','passed');
	}
	else {
		writeElement('cmi.core.lesson_status','failed');
	}

}

// ------------------------------------------------------------------------------------
// set cmi.core.entry based on the value of cmi.core.exit

// clear existing value
writeElement('cmi.core.entry','');

// new entry value depends on exit value
$exit = readElement('cmi.core.exit');
if ($exit == 'suspend') {
	writeElement('cmi.core.entry','resume');
}
else {
	writeElement('cmi.core.entry','');
}

// ------------------------------------------------------------------------------------
// process changes to cmi.core.total_time

// read cmi.core.total_time from the 'scormvars' table
$totaltime = readElement('cmi.core.total_time');

// convert total time to seconds
$time = explode(':',$totaltime);
$totalseconds = $time[0]*60*60 + $time[1]*60 + $time[2];

// read the last-set cmi.core.session_time from the 'scormvars' table
$sessiontime = readElement('cmi.core.session_time');

// no session time set by SCO - set to zero
if (! $sessiontime) {
	$sessiontime = "00:00:00";
}

// convert session time to seconds
$time = explode(':',$sessiontime);
$sessionseconds = $time[0]*60*60 + $time[1]*60 + $time[2];

// new total time is ...
$totalseconds += $sessionseconds;

// break total time into hours, minutes and seconds
$totalhours = intval($totalseconds / 3600);
$totalseconds -= $totalhours * 3600;
$totalminutes = intval($totalseconds / 60);
$totalseconds -= $totalminutes * 60;

// reformat to comply with the SCORM data model
$totaltime = sprintf("%04d:%02d:%02d",$totalhours,$totalminutes,$totalseconds);

// save new total time to the 'scormvars' table
writeElement('cmi.core.total_time',$totaltime);

// delete the last session time
writeElement('cmi.core.session_time','');

// delete the last score raw
writeElement('cmi.core.score.raw','');

//update score

$sql = "select * from sco_data where student_id={$SCOInstanceID} and organizations_id={$course_id}";

$result = mysql_query($sql);



while($row = mysql_fetch_array($result)){
	$min_scrore = $row['min_scrore'];
	$max_scrore = $row['max_scrore'];
	$num_count = $row['raw_score'];

	$time = explode(':',$row['total_time']);
	$total_time_seconds = $time[0]*60*60 + $time[1]*60 + $time[2];
	
	$score_item = unserialize($row['score_item']);
	
	if(count($score_item) > 0){
		$score_sum = 0;
		foreach ($score_item as $item_k => $item_value) {
			$score_sum+=$item_value;
		}
		$score = floor($score_sum/count($score_item));
	}else{
		$score = 0;
	}
	//$score = $row['raw_score'];
	if($row['raw_score'] > $max_scrore){
		$max_scrore = $row['raw_score'];
	}else if($row['raw_score'] < $min_scrore || $min_scrore == 0){
		$min_scrore = $row['raw_score'];
	}
	
	if($row['lesson_graduate_ps'] == 100 && $row['status'] != "return" && $row['status'] != "completed"){
		writeElement('cmi.core.lesson_status','completed');
	}else if($row['status'] == "completed"){
		writeElement('cmi.core.lesson_status','return');
	}else if($row['lesson_graduate_ps'] > 0){
		writeElement('cmi.core.lesson_status','incomplete');
	}
	$sql = "update sco_data set min_scrore='{$min_scrore}' ,max_scrore='{$max_scrore}' ,num_count=num_count+1 ,score='{$score}'  ,total_time_sec='{$total_time_seconds}'  where student_id={$SCOInstanceID} and organizations_id=".$course_id;
	mysql_query($sql);

}

// ------------------------------------------------------------------------------------

// return value to the calling program
print "true";
die;

?>