<?

$class_id = (int) get_param("class");

$sql="
		SELECT organizations_class.class, organizations.organizations_id, organizations.title_bg, organizations.manifests_id, organizations.sequencing, organizations.author, organizations.date
		FROM `organizations_class`
		LEFT JOIN organizations ON organizations_class.organizations_id = organizations.organizations_id
		WHERE organizations_class.class = {$class_id}
		";
		$result = mysql_query($sql,$link);
?>
<div class="course_list">
<a id="opener_chart1" href="#">ดูกราฟ</a>
	<h1>
	ตารางแสดงจำนวนผู้เรียนในแต่ละรายวิชา ของ ป. <?=$class_id?>
	</h1>
	<table width="98%" border="1">
	<tr>
		<th>
			ชื่อรายวิชา
		</th>
		<th>
			จำนวนผู้เรียน
		</th>
	</tr>
<?
		$chart = array();
		while($row = mysql_fetch_array($result)){
			$sql_class = "SELECT count(sco_data.organizations_id) as num_student ,sum(sco_data.num_count) as num 
								,sum(total_time_sec)/count(sco_data.organizations_id) as total_time
								FROM  sco_data
								Where sco_data.organizations_id = ".$row['organizations_id']."
								group by sco_data.organizations_id";
			$result_class = mysql_query($sql_class,$link);
			$num_student = 0;
			while($row_class = mysql_fetch_array($result_class)){
				$chart[] = "['".$row['title_bg']."',".$row_class['num_student']."]";
				// break total time into hours, minutes and seconds
				$totalseconds = $row_class['total_time'];
				$totalhours = intval($totalseconds / 3600);
				$totalseconds -= $totalhours * 3600;
				$totalminutes = intval($totalseconds / 60);
				$totalseconds -= $totalminutes * 60;
				$num_student = $row_class['num_student'];
				// reformat to comply with the SCORM data model
				$totaltime = sprintf("%04d:%02d:%02d",$totalhours,$totalminutes,$totalseconds);
			}
?>
	<tr>
		<td>
			<a href="?cmd=report_course_detail&class=<?=$class_id?>&organizations_id=<?=$row['organizations_id']?>&organizations_title=<?=$row['title_bg']?>"><?=$row['title_bg']?></a>
		</td>
		<td>
			<?=$num_student?>
		</td>
	</tr>
<?
		}
?>
</table>
</div>
<script id="example_1" type="text/javascript">$(document).ready(function(){
    s1 = [<?=implode(",",$chart)?>];
        
    plot1 = $.jqplot('chart1', [s1], {
        grid: {
            drawBorder: false, 
            drawGridlines: false,
            background: '#ffffff',
            shadow:false
        },
        axesDefaults: {
            
        },
        seriesDefaults:{
            renderer:$.jqplot.PieRenderer,
            rendererOptions: {
                showDataLabels: true
            }
        },
        legend: {
            show: true,
            rendererOptions: {
                numberRows: 1
            },
            location: 's'
        }
    }); 
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-message" ).dialog({
		modal: true,
		autoOpen: false,
		width: 600,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
			}
		}
	});
	
	$( "#opener_chart1" ).click(function() {
		$( "#dialog-message" ).dialog( "open" );
		return false;
	});

	
});
</script>

<div id="dialog-message" title="กราฟวงกลมแสดงนักเรียนต่อรายวิชา">
	<div id="chart1" style="margin-top:20px; margin-left:20px; width:500px; height:300px;"></div>
</div>