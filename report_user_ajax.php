<?	
$student_id = get_param("student_id","post");
$sql="
		SELECT * FROM  sco_data
			LEFT JOIN organizations ON sco_data.organizations_id = organizations.organizations_id
			LEFT JOIN organizations_class ON organizations.organizations_id = organizations_class.organizations_id
			LEFT JOIN category ON organizations_class.cate_id = category.cate_id
			Where sco_data.student_id = ".$student_id." order by organizations_class.class asc,category.cate_id asc,organizations.author asc
		";
		$result = mysql_query($sql,$link);

		$i = 1;
		$subject_group = "";
		$subject_group_old = "";
		$score_sum = 0;
		$ps_count = 0;
		$ps_sum = 0;
		while($row = mysql_fetch_array($result)){
			$subject_group = $row['cate_name']." ป.".$row['class'];
			$totalseconds = $row['total_time_sec'];
			$totalhours = intval($totalseconds / 3600);
			$totalseconds -= $totalhours * 3600;
			$totalminutes = intval($totalseconds / 60);
			$totalseconds -= $totalminutes * 60;
			$i++;
			// reformat to comply with the SCORM data model
			$totaltime = sprintf("%04d:%02d:%02d",$totalhours,$totalminutes,$totalseconds);
			if($subject_group != $subject_group_old && $subject_group_old != ""){
				echo "</fieldset></div>";
			}
			if($subject_group != $subject_group_old){
				$score_sum = 0;
				$ps_count = 0;
				$ps_sum = 0;
				?>
					<div class="item">
					<fieldset>
					<legend><?=$subject_group?></legend>
					<ul class="head">
						<li class="subject_title">
							หน่วยการเรียน
						</li>
						<li class="user_ps">
							ความคืบหน้า
						</li>
						<li class="user_status">
							สถานะ
						</li>
						<li class="score_test">
							คะแนนทดสอบ
						</li>
					</ul>
				<?
			}
			?>
				<ul>
					<li class="subject_title">
						<?=$row['title_bg']?>
					</li>
					<li class="user_ps">
					<div class="bg_ps">
						<div class="curent_ps" style="width: <? echo ($row['lesson_graduate_ps']/100)*166; ?>px;">&nbsp;</div>
					</div>
					</li>
					<li class="user_status">
						<?=$status_sco[$row['status']];?>
					</li>
					<li class="score_test">
						<?=$row['score']?>%
					</li>
				</ul>
			<?
			$score_sum += $row['score'];
			$ps_count++;
			$ps_sum += $row['lesson_graduate_ps'];
			$subject_group_old = $subject_group;
		}
		if($subject_group != ""){
			if($ps_count > 0){
				$score_avg = $score_sum/$ps_count;
				$ps_avg = $ps_sum/$ps_count;
			}else{
				$score_avg = 0;
				$ps_avg = 0;
			}
			$score_avg = floor($score_avg);
			$ps_avg = floor($ps_avg);
?>
				<ul>
					<li class="subject_title">
						<strong>
							เฉลี่ย
						</strong>
					</li>
					<li class="user_ps">
					<div class="bg_ps">
						<div class="curent_ps" style="width: <? echo ($ps_avg/100)*166; ?>px;"></div>
					</div>
					</li>
					<li class="user_status">
						
					</li>
					<li class="score_test">
						<?=$score_avg;?>%
					</li>
				</ul>
<?
			echo "</fieldset></div>";
		}
?>