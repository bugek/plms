<?
$organizations_id = (int) get_param("organizations_id","post");
$cate_id = (int) get_param("cate_id","post");
$subject_class = (int) get_param("subject_class","post");
$student_status = get_param("student_status","post");
$student_score = get_param("student_score","post");
$dis_student_ps = (int) get_param("dis_student_ps","post");

$sql_where = array();
$student_stat = array();

$student_stat[0] = 0;
$student_stat[1] = 0;
$student_stat[2] = 0;
$student_stat[3] = 0;
$student_stat[4] = 0;

if($organizations_id != 0){
	$sql_where[] = "sco_data.organizations_id = {$organizations_id}";
}


if($student_status != false && $student_status != "none"){
	$sql_where[] = "sco_data.status = '{$student_status}'";
}

if($dis_student_ps != false && $dis_student_ps != "none"){
	switch ($dis_student_ps) {
			case "1":
				$sql_where[] = "sco_data.lesson_graduate_ps < 50 ";
				break;
			case "2":
				$sql_where[] = "(sco_data.lesson_graduate_ps >= 50 and sco_data.lesson_graduate_ps < 60) ";
				break;
			case "3":
				$sql_where[] = "(sco_data.lesson_graduate_ps >= 60 and sco_data.lesson_graduate_ps < 70) ";
				break;
			case "4":
				$sql_where[] = "(sco_data.lesson_graduate_ps >= 70 and sco_data.lesson_graduate_ps < 80) ";
				break;
			case "5":
				$sql_where[] = "(sco_data.lesson_graduate_ps >= 80) ";
				break;
	}
}

if($student_score != false && $student_score != "none"){
	switch ($student_score) {
			case "1":
				$sql_where[] = "sco_data.score < 50 ";
				break;
			case "2":
				$sql_where[] = "(sco_data.score >= 50 and sco_data.score < 60) ";
				break;
			case "3":
				$sql_where[] = "(sco_data.score >= 60 and sco_data.score < 70) ";
				break;
			case "4":
				$sql_where[] = "(sco_data.score >= 70 and sco_data.score < 80) ";
				break;
			case "5":
				$sql_where[] = "(sco_data.score >= 80) ";
				break;
	}
}

if($cate_id != 0){
	$sql_where[] = "cate_id={$cate_id} ";
}
if($subject_class != 0){
	$sql_where[] = "class={$subject_class} ";
}

if(count($sql_where)>0){
	$sql_where = "where ".implode(" and ",$sql_where)." and sco_data.student_id != 1 and sco_data.student_id != 2 and sco_data.student_id != 0";
}else{
	$sql_where = "where sco_data.student_id != 1 and sco_data.student_id != 2 and sco_data.student_id != 0";
}

$sql="
		SELECT *
		FROM `sco_data`
		LEFT JOIN student ON sco_data.student_id = student.student_id
		LEFT JOIN organizations_class ON sco_data.organizations_id = organizations_class.organizations_id
		LEFT JOIN organizations ON sco_data.organizations_id = organizations.organizations_id
		{$sql_where}
		order by student_name asc
		";
		$result = mysql_query($sql);
?>
	<div class="show_graph">
		<a href="javascript:gen_chart();">กราฟ</a>
	</div>
	<table id="table_sort" class="tablesorter">
	<thead>
	<tr>
		<th>
			ลำดับ
		</th>
		<th>
			รหัสนักเรียน
		</th>
		<th>
			ชื่อ - นามสกุล
		</th>
		<th>
			หน่วยการเรียน
		</th>
		<th>
			สถานะ
		</th>
		<th>
			คะแนน
		</th>
		<th>
			ความก้าวหน้า
		</th>
		<th>
			เรียนล่าสุด
		</th>
	</tr>
	</thead>
	
	<tbody>
<?
		$chart = array();
		$i=0;
		$count_row = 0;
		while($row = mysql_fetch_array($result)){
			$i++;
			$count_row++;
			if($row['lesson_graduate_ps'] <= 0){
				$student_stat[0]++;
			}else if($row['lesson_graduate_ps'] <= 50){
				$student_stat[1]++;
			}else if($row['lesson_graduate_ps'] <= 60){
				$student_stat[2]++;
			}else if($row['lesson_graduate_ps'] <= 70){
				$student_stat[3]++;
			}else if($row['lesson_graduate_ps'] <= 80){
				$student_stat[4]++;
			}else{
				$student_stat[5]++;
			}
?>
	<tr class="<? if($count_row%2 == 0) echo "odd"; else echo "add"; ?>">
		<td>
			<?=$i?>.
		</td>
		<td>
			<?=$row['student_id']?>
		</td>
		<td>
			<a href="javascript:report_user(<?=$row['student_id']?>,'<?=$row['student_name']?> <?=$row['student_lastname']?>');"><?=$row['student_name']?> <?=$row['student_lastname']?></a>
		</td>
		<td>
			<?=$row['title_bg']?>
		</td>
		<td>
			<?=$status_sco[$row['status']];?>
		</td>
		<td>
			<a href="javascript:open_sub_score(<?=$row['sco_id']?>,'<?=$row['title_bg']?>');"><?=$row['score']?>%</a>
		</td>
		<td>
			<div class="bg_ps left">
				<div class="curent_ps" style="width: <? echo ($row['lesson_graduate_ps']/100)*166; ?>px;">&nbsp;</div>
			</div>
			<div class="left"><?=$row['lesson_graduate_ps']?> %</div> <br clear="all" />
		</td>
		<td>
			<?=$row['last_update']?>
		</td>
	</tr>
<?
		}
?>
</tbody>
</table>
<div id="pager" class="pager">
	<form>
		<img src="images/first.png" class="first"/>
		<img src="images/prev.png" class="prev"/>
		<input type="text" class="pagedisplay"/>
		<img src="images/next.png" class="next"/>
		<img src="images/last.png" class="last"/>
		<select class="pagesize">
			<option selected="selected"  value="10">10</option>

			<option value="20">20</option>
			<option value="30">30</option>
			<option  value="40">40</option>
		</select>
	</form>
</div>
split_data<?=json_encode($student_stat);?>