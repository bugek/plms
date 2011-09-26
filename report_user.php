<script type="text/javascript">

	function open_sub_score(sco_id,title_sub){
			$.post("index_ajax.php?cmd=sub_score", {sco_id:sco_id},
			function(data) {
				$("#sub_score").html(data);
			});
	
		$("#sub_score").dialog({
			title:title_sub,
			modal: true,
			autoOpen: true,
			width: 600,
			buttons: {
				"ปิดหน้าต่าง": function() {
					$(this).dialog( "close" );
				}
			}
		});
	}
	
</script>
<?		
$sql="
		SELECT * FROM  sco_data
			LEFT JOIN organizations ON sco_data.organizations_id = organizations.organizations_id
			LEFT JOIN organizations_class ON organizations.organizations_id = organizations_class.organizations_id
			LEFT JOIN category ON organizations_class.cate_id = category.cate_id
			Where sco_data.student_id = ".$student_id." order by organizations_class.class asc,category.cate_id asc,organizations.author asc
		";
		$result = mysql_query($sql,$link);
?>
<div class="right_panel">
	<div class="content_inner">
<?
		$chart = array();
		$i = 1;
		$subject_group = "";
		$subject_group_old = "";
		$score_array = array();
		$score_sum = 0;
		$ps_count = 0;
		$ps_sum = 0;
		while($row = mysql_fetch_array($result)){
			$subject_group = $row['cate_name']." ป.".$row['class'];
			$chart['title'][] = "{label:'".$row['title_bg']."', renderer:$.jqplot.BarRenderer}";
			$chart['def'][] = "line".$i." = [".$row['lesson_graduate_ps']."];";
			$chart['name'][] = "line".$i;
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
				$score_array = array();
				$score_sum = 0;
				$ps_count = 0;
				$ps_sum = 0;
				?>
					<div class="item">
					<fieldset>
					<legend><?=$subject_group?></legend>
					<ul class="head">
						<li class="subject_title">
							หน่วยการเรียนรู้
						</li>
						<li class="user_ps">
							ความก้าวหน้า
						</li>
						<li class="user_status">
							สถานะ
						</li>
						<li class="score_test">
							คะแนน
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
						<div class="curent_ps" style="width: <? echo ($row['lesson_graduate_ps']/100)*166; ?>px;"><?=$row['lesson_graduate_ps'];?>%</div>
					</div>
					</li>
					<li class="user_status">
						<?=$status_sco[$row['status']];?>
					</li>
					<li class="score_test">
						<a href="javascript:open_sub_score(<?=$row['sco_id']?>,'<?=$row['title_bg']?>');"><?=$row['score']?>%</a>
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
						<div class="curent_ps" style="width: <? echo ($ps_avg/100)*166; ?>px;"><?=$ps_avg;?>%</div>
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
	</div>
</div>
<div id="sub_score" title="ลงทะเบียนเรียน">

</div>