<?php 

$SCOInstanceID = $_REQUEST['SCOInstanceID'] * 1;
$course_id = $_REQUEST['course_id'] * 1;
$item_id = $_REQUEST['item_id'] * 1;

$data = $_REQUEST['data'];
if (! is_array($data)) { $data = array($data); }


$sql = "select * from sco_data where student_id={$SCOInstanceID} and organizations_id={$course_id}";

$result = mysql_query($sql);

$lesson_graduate_ps = 0;

while($row = mysql_fetch_array($result)){
	
	$lesson_all = unserialize($row['lesson_all']);
	$lesson_graduate = unserialize($row['lesson_graduate']);
	if ($item_id != 0 && !in_array($item_id, $lesson_graduate)) {
		$lesson_graduate[] = $item_id;
	}
	if(count($lesson_all) == 0){
		$lesson_graduate_ps  = 0;
	}else{
		$lesson_graduate_ps = floor((count($lesson_graduate)/count($lesson_all))*100);
	}
	
	$sql="UPDATE `sco_data` SET `lesson_graduate` = '".serialize($lesson_graduate)."',
			`lesson_graduate_ps` = '".$lesson_graduate_ps."'  where student_id={$SCOInstanceID} and organizations_id={$course_id}";
	mysql_query($sql);
}
?>
<ul>
	<li>
		แถบความก้าวหน้า
	</li>
	<li>
		<div class="bg_ps">
			<div style="width: <? echo ($lesson_graduate_ps/100)*166; ?>px;" class="curent_ps">&nbsp;</div>
		</div>
	</li>
	<li>
		<?=$lesson_graduate_ps?> %
	</li>
</ul>