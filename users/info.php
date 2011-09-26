<!DOCTYPE html>
<html>
	<head>
	  <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
	  <title>info</title>
  	  <link rel="stylesheet" href="css/main.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
	  <link rel="stylesheet" href="css/site.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
	  <link rel="stylesheet" href="css/redmond/jquery-ui-1.8.12.custom.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
	  <link rel="stylesheet" href="css/ui.jqgrid.css" type="text/css" media="screen" title="no title" charset="utf-8"/>


	 	<script src="js/jquery-1.5.2.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/jquery-ui-1.8.12.custom.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/i18n/grid.locale-en.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/jquery.jqGrid.min.js" type="text/javascript" charset="utf-8"></script>

		<script type="text/javascript" charset="utf-8">
			$(document).ready(function(){ 
				var selIRow = 1 ;
				var lastSel;
				$('#student_grid').jqGrid({
					datatype: 'local',
					colNames: ['รหัส', 'คำนำหน้า', 'ชื่อ', 'นามสกุล'],
					colModel: [
								{name:'student_id', index:'student_id', width:55,editable:true},
								{name:'prefix_name',index:'prefix_name', width:90,editable:true , edittype:'select', 
																		editoptions:{value:"ด.ช.:ด.ช;ด.ญ.:ด.ญ.;นาย:นาย;นางสาว:นางสาว"}},
								{name:'student_name', index:'student_name', width:180,editable:true},
								{name:'student_lastname', index:'student_lastname', width:200,editable:true ,
										editoptions: {
											
												dataInit: function(elem) { $(elem).focus(function() { this.select();  }) },
												dataEvents: [ 
													{
														type: 'keypress',
														fn: function(e) {
														  var key = e.keyCode;
														  if (key == 9)
														  {
																var grid = $('#student_grid');
																grid.jqGrid('saveRow', selIRow, false, 'clientArray');
																if(selIRow++ == grid.getDataIDs().length) {
																	grid.addRowData(selIRow,{});
															
																}
																grid.jqGrid('editRow',selIRow, false, 'clientArray');
																getGridData();
														 }
														}
													}	
												]
											}	
										}
							],
					height: '140',
					pager: '#pager',
					rowNum:20,
					sortname: 'student_id',
					sortorder: 'desc',
					viewrecords: true, 
					caption: 'รายชื่อนักเรียน',
					rowNumbers:true,
					scroll:1,
					onSelectRow: function(id) {
									if(id && id!==lastSel){
										jQuery('#student_grid').saveRow(lastSel,false,'clientArray');
										jQuery('#student_grid').editRow('editRow',id);
										lastSel = id;
									}
									jQuery('#student_grid').editRow(id);
								},
					

				});
				var mydata = [ {student_id:'', prefix_name:'', student_name:'',student_lastname:''},
							   ] ;
				for(var i=0; i < mydata.length; i++)
					$('#student_grid').jqGrid('addRowData',mydata[i].id,mydata[i]);
			
			});
			
			function getGridData(){
				var gridData = $("student_grid").jqGrid('getGridParam','data');
				console.log(gridData);
			}
			
			
		</script>
	</head>
	<body style="padding:15px ;margin:5px">
	<table id="student_grid"></table>
	<div id="pager"></div>
	
	
<?php
 	//phpinfo();
	
?>
</body>
</html>