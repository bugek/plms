<?php 

/*

VS SCORM 1.2 RTE - rte.php
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
Boston, MA	02110-1301, USA.

*/

// read SCOInstanceID from the GET parameters
$SCOInstanceID = $_GET['SCOInstanceID'] * 1;

?>
<html>
<head>
	<title>VS SCORM</title>
</head>
<frameset frameborder="0" framespacing="0" border="0" rows="50,*" cols="*" onbeforeunload="API.LMSFinish('');" onunload="API.LMSFinish('');">
	<frame src="api.php?SCOInstanceID=123&course_id=2" name="API" noresize>
	<frame src="/sc/src/test/a001_quiz_q1.html" name="course">
</frameset>
</html>