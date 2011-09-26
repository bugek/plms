<?

$class_id = (int) get_param("class_id","post");
$cate_id = (int) get_param("cate_id","post");

$user_score = array();

if($userdata && $userdata['data']['student_id'] != 1 && $userdata['data']['student_id'] != 2){
	$sql_score = "select * from sco_data where sco_data.student_id = ".$userdata['data']['student_id'];
	$result_score = mysql_query($sql_score);
	while($row = mysql_fetch_array($result_score)){
		$user_score[$row['organizations_id']] = $row;
	}
}


$sql="
		SELECT organizations_class.*,organizations.*,manifests.author as dir
		FROM `organizations_class`
		LEFT JOIN organizations ON organizations_class.organizations_id = organizations.organizations_id
		LEFT JOIN manifests ON organizations.manifests_id = manifests.manifests_id
		WHERE organizations_class.class = {$class_id} and organizations_class.cate_id = {$cate_id}
		order by manifests.author asc
		";
		$result = mysql_query($sql);
?>
	<h2>หน่วยการเรียนรู้</h2>
	<ul class="ul_course_list">
<?
		$k = 0;
		while($row = mysql_fetch_array($result)){
			$k++;
			$img_thumb = "images/no_img.gif";
			if(file_exists("src/".$row['dir']."/thumb.jpg")){
				$img_thumb = "src/".$row['dir']."/thumb.jpg";
			}
?>
		<li>
		<img src="<?=$img_thumb?>" width="164" height="124" />
		<br />
		<a href="?cmd=play_course&course=<?=$row['organizations_id']?>"><?=$row['title_bg']?></a>
		<?
			if(isset($user_score[$row['organizations_id']])){
		?>
		<div class="ps_title">ความก้าวหน้า</div>
		<div class="bg_ps">
			<div class="curent_ps" style="width: <? echo ($user_score[$row['organizations_id']]['lesson_graduate_ps']/100)*166; ?>px;">&nbsp;</div>
		</div>
		<div class="test_score">
			คะแนน
		<span><?=$user_score[$row['organizations_id']]['score']?></span> จาก
		<span>100</span>
		</div>
		<?
			}
		?>
		</li>
<?
		}
?>
	</ul>