<?

$class_id = (int) get_param("class");
$organizations_id = (int) get_param("organizations_id");
$organizations_title = get_param("organizations_title");

$sql="
SELECT * FROM  organizations_class 
LEFT JOIN category ON organizations_class.cate_id = category.cate_id
group by organizations_class.cate_id
";
$result_cate = mysql_query($sql);

$sql="
SELECT * FROM  organizations_class 
group by organizations_class.class
";
$result_class = mysql_query($sql);


?>
<form action="" name="fillter_form" id="fillter_form">
	<ul>
		<li class="dis_subject_name">
			วิชา 
			<select name="subject_name" id="subject_name">
				<option value="0">ทั้งหมด</option>
				<?
				while($row = mysql_fetch_array($result_cate)){
				?>
				<option value="<?=$row['cate_id']?>"><?=$row['cate_name']?></option>
				<?
				}
				?>
			</select>
		</li>
		<li class="dis_class">
			ชั้นเรียน
			<select name="subject_class" id="subject_class">
				<option value="0">ทั้งหมด</option>
				<?
				while($row = mysql_fetch_array($result_class)){
				?>
				<option value="<?=$row['class']?>">ป. <?=$row['class']?></option>
				<?
				}
				?>
			</select>
		</li>
		<li class="dis_subject_unit">
			หน่วยการเรียนรู้ 
			<select name="subject_unit" id="subject_unit">
				<option value="0">ทั้งหมด</option>
			</select>
		</li>
		<!--li class="dis_type_fillter">
			ตัวกรอง 
			<select name="type_fillter" id="type_fillter">
				<option value="0">none</option>
				<option value="1">สถานะ</option>
				<option value="2">เปอร์เซ็น</option>
			</select>
		</li-->
		<li class="dis_student_status">
			สถานะ
			<select name="student_status" id="student_status">
				<option value="none">ทั้งหมด</option>
				<option value="incomplete"><?=$status_sco['incomplete'];?></option>
				<option value="completed"><?=$status_sco['completed'];?></option>
				<option value="return"><?=$status_sco['return'];?></option>
				<!--option value="failed">failed</option>
				<option value="passed">passed</option>
				<option value="browsed">browsed</option>
				<option value="not attempted">not attempted</option-->
			</select>
		</li>
		<li class="dis_student_score">
			คะแนน
			<select name="student_score" id="student_score">
				<option value="none">ทั้งหมด</option>
				<option value="1">&lt; 50</option>
				<option value="2">50 - 60</option>
				<option value="3">61 - 70</option>
				<option value="4">71 - 80</option>
				<option value="5">&gt; 80</option>
			</select>
		</li>
		<li class="dis_student_ps">
			ความก้าวหน้า
			<select name="student_ps" id="student_ps">
				<option value="none">ทั้งหมด</option>
				<option value="1">&lt; 50</option>
				<option value="2">50 - 60</option>
				<option value="3">61 - 70</option>
				<option value="4">71 - 80</option>
				<option value="5">&gt; 80</option>
			</select>
		</li>
		<li class="dis_apply">
			<input type="submit" value="แสดง" name="apply" id="apply" />
		</li>
	</ul>
</form>
<script type="text/javascript">
	var chart_data;
	function gen_chart(){
		data_array = chart_data;
		var chart;
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'container',
				zoomType: 'xy'
			},
			title: {
				text: 'กราฟแสดงความก้าวหน้าของนักเรียน'
			},
			xAxis: [{
				categories: ['0%', '1-50%', '51-60%', '61-70%', '71-80%', '81-100%']
			}],
			yAxis: [{ // Primary yAxis
				min:0,
				labels: {
					formatter: function() {
						return this.value +'คน';
					},
					style: {
						color: '#89A54E'
					}
				},
				title: {
					text: 'จำนวนคน',
					style: {
						color: '#89A54E'
					}
				}
			}, { // Secondary yAxis
				title: {
					text: 'จำนวนคน',
					style: {
						color: '#4572A7'
					}
				},
				labels: {
					formatter: function() {
						return this.value +' คน';
					},
					style: {
						color: '#4572A7'
					}
				},
				opposite: true
			}],
			tooltip: {
				formatter: function() {
					return ''+
						this.x +': '+ this.y +
						(this.series.name == 'จำนวนคน' ? ' คน' : ' คน');
				}
			},
			legend: {
				layout: 'vertical',
				align: 'left',
				x: 120,
				verticalAlign: 'top',
				y: 100,
				floating: true,
				backgroundColor: '#FFFFFF'
			},
			series: [{
				name: 'ความก้าวหน้า',
				color: '#4572A7',
				type: 'column',
				yAxis: 1,
				data: data_array		
			
			}, {
				name: 'ความก้าวหน้า',
				color: '#89A54E',
				type: 'spline',
				data: data_array
			}]
		});
		
		$( "#dialog-message" ).dialog({
			modal: true,
			buttons: {
				Ok: function() {
					$( this ).dialog( "close" );
				}
			},
			title:"กราฟแสดงความก้าวหน้า",
			width:900,
			height:520
		});
	}
	
	function report_user(student_id,student_name){
			data_post = {student_id: student_id};
			$.post("index_ajax.php?cmd=report_user", data_post,
			function(data) {
				$( "#container" ).html(data);
				$( "#dialog-message" ).dialog({
					modal: true,
					buttons: {
						"ปิดหน้าต่าง": function() {
							$( this ).dialog( "close" );
						}
					},
					title:"ผลการเรียนของ "+student_name,
					width:900,
					height:520
				});
			});
	}
	
	function get_subject_unit(){
			data_post = {cate_id: $("#subject_name").val(),subject_class: $("#subject_class").val()};
			$.post("index_ajax.php?cmd=get_subject_unit", data_post,
			function(data) {
				$("#subject_unit").html(data);
				$(".dis_subject_unit").css({display:"inline"});
			});
	}
	
	function get_report(){
			var data_post;
			/*if($("#type_fillter").val() == 1){
				data_post = {student_status: $("#student_status").val(),cate_id: $("#subject_name").val(),subject_class: $("#subject_class").val(),organizations_id: $("#subject_unit").val()};
			}else if($("#type_fillter").val() == 2){
				data_post = {dis_student_ps: $("#student_ps").val(),cate_id: $("#subject_name").val(),subject_class: $("#subject_class").val(),organizations_id: $("#subject_unit").val()};
			}else{*/
				data_post = {student_score: $("#student_score").val(),student_status: $("#student_status").val(),dis_student_ps: $("#student_ps").val(),cate_id: $("#subject_name").val(),subject_class: $("#subject_class").val(),organizations_id: $("#subject_unit").val()};
			//}
			$.post("index_ajax.php?cmd=report_course_detail_ajax&class=<?=$class_id?>&organizations_id=<?=$organizations_id?>&organizations_title=<?=$organizations_title?>", data_post,
			function(data) {
				var array_data = data.split("split_data");
				$(".display_stage").html(array_data[0]);
				var myObject = eval('(' + array_data[1] + ')');
				chart_data = myObject;
				$("#table_sort").tablesorter().tablesorterPager({container: $("#pager")});; 
			});
	}
	
	$(document).ready(function(){
	
		$("#subject_name").change( function() {
				get_subject_unit();
		});
		$("#subject_class").change( function() {
				get_subject_unit();
		});
		$("#type_fillter").change( function() {
			var expression = $(this).val() ;
		
			switch (expression){
				case  "1": 
					$(".dis_student_status").css("display","inline");
					$(".dis_student_ps").css("display","none");
					break;
				case  "2": 
					$(".dis_student_status").css("display","none");
					$(".dis_student_ps").css("display","inline");
					break;
				default: 
					$(".dis_student_status").css("display","none");
					$(".dis_student_ps").css("display","none");
			}
			
		});
		
		$('#fillter_form').submit(function() {
		   get_report();
		  return false;
		});
	});
	
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
	
	get_report();
	
</script>
<div id="dialog-message" title="" style="display:none">
	<div id="container" style="width: 800px; height: 400px; margin: 0 auto"></div> 
	</div>
</div>
<div id="sub_score" title="รายละเอียดคะแนน">

</div>