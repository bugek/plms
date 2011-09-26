<?php 
require("include/user.class.php");
require("user.config.php");
if(isset($_COOKIE["theme_id"])){
	$theme_id = (int) $_COOKIE["theme_id"];

	if($theme_id){
		switch(@$theme_id){
			case 1: $theme = 'ui-lightness'; break;
			case 2: $theme = 'redmond'; break;
			case 3: $theme = 'cupertino'; break;
			
		}
	}else{
		$theme = 'ui-lightness';
	}

}

?>
<style id="u-manage" class="u-manage">
	@import url("users/css/<?php echo $theme; ?>/jquery-ui-1.8.12.custom.css");

</style>

<?php
	
	if($userdata['data']['student_type'] != "admin"){
		js_redirect("index.php");
	}
	
	if(isset($_FILES["csv_file"])) { $tab_selected = 1 ; }
	else { $tab_selected = 0; } 

	function fopen_utf8($file_name){
		$encoding = '';
		$handle = fopen($file_name, 'r');
		$bom = fread($handle, 2);
		
		rewind($handle);
		
		if ($bom === chr(0xff).chr(0xfe) || $bom === chr(0xfe).chr(0xff)){
			$encoding = 'UTF-16';
		}else {
			$file_sample = fread($handle, 100) ; // read first 1000 byte , + e is a workaround fo mb_string bug
			
			rewind($handle);
			
			$encoding = mb_detect_encoding($file_sample , 'UTF-8',true);
			//$encoding = iconv_get_encoding($file_sample);
		}
	
		//exit;
		if (!$encoding){
			
			stream_filter_append($handle, 'convert.iconv.TIS-620/UTF-8');
		}
		
		return($handle);
	}	
	
?>
<div id="user_m" align="center">
 <br/>
		<script type="text/javascript" charset="utf-8">
			
			 $(document).ready(function(){

				$("#tabs").tabs( {selected: <?php echo $tab_selected; ?> });
				$(".tabs").tabs( {selected: <?php echo $tab_selected; ?> })
				
				var lastSel;
				$("#list").jqGrid({
							url:'users/get_student.php',
							//datatype: 'json',  
							datatype: 'json',
							mtype: 'GET', 
							colNames: [ 'รหัส', 'คำนำหน้า', 'ชื่อ', 'นามสกุล', 'วันลงทะเบียน', 'ใช้งานครั้งสุดท้าย' ], 
							//colNames: [ 'student_id', 'prefix_name', 'student_name', 'student_lastname', 'register_date', 'lastlogin_date'],
							colModel: [
										{name:'student_id', index:'student_id', width:55,editable:true},
										{name:'prefix_name',index:'prefix_name', width:90,editable:true , edittype:'select', 
																		editoptions:{value:"ด.ช.:ด.ช.;ด.ญ.:ด.ญ.;นาย:นาย;นางสาว:นางสาว"}},
										{name:'student_name', index:'student_name', width:180,editable:true},
										{name:'student_lastname', index:'student_lastname', width:200,editable:true},
										{name:'register_date', index:'register_date', width:220},
										{name:'lastlogin_date', index:'lastlogin_date', width:220} 
									   ],
							height: '420',width:'800',
							pager: '#pager',
							rowNum:18,
							sortname: 'student_id',
							sortorder: 'desc',
							viewrecords: true, 
							caption: 'รายชื่อนักเรียน',
							/*onSelectRow: function(id) {
									if(id && id!==lastSel){
										jQuery('#list').restoreRow(lastSel);
										jQuery('#list').editRow('editRow',id,true);
										lastSel = id;
									}
									jQuery('#list').editRow(id, true);
								},*/
							//editurl: "users/new.php"

					});		
				//$('#list').jqGrid('navButtonAdd' , 
					//			  '#pager', 
						//		  { caption:"เปลี่ยนรหัสผ่าน", buttonicon:"ui-icon-locked", onClickButton:null, position:"last", title:"เปลี่ยนรหัสผ่าน", cursor: "pointer" });

				$('#list').jqGrid('navGrid','#pager', { view:false , search:false , find:false , 
									addtitle:"เพิ่มรายชื่อ",
									edittitle:"แก้ไขรายชื่อ",
									deltitle:"ลบรายชื่อ",
								    refreshtitle:"รีเฟรซ" ,
									recordtext: "จำนวน {0} - {1} จาก {2}",
									emptyrecords: "ไม่พบรายชื่อ นักเรียน",
									loadtext: "กำลังโหลด ...",
									pgtext : "หน้า {0} จาก {1}"
									} ,
								{ url:"users/edit.php",
								  edittitle:"แก้ไข",
								  editCaption:"แก้รายชื่อ นักเรียน",
								  title:"แก้ไข",
								  bSubmit:'ตกลง', 
								  bCancel:'ยกเลิก', 
								  bClose:'ปิด', 
								  saveData: "บันทึกข้อมูลเรียบร้อย",
								  closeAfterEdit:true ,
								  afterComplete: function(response, postdata, formid){ 
									status = response.responseText; 
									var dialogOpts = { modal: true,
													   buttons: { "ปิด" : function(){ $(this).dialog("close"); } },
													   title: 'สถานะ',resizable:false,
													   height: 150} 
									var message = '';
					
									switch(response.responseText) {
										case '0' :  
												 //message = '<span class="ui-icon ui-state-default ui-icon-check"></span>';
												
												 message += ' บันทึกข้อมูล เสร็จเรียบร้อย!!';
												// message += '';    
												 break;
										case '1' :
												 //message = '<span class="ui-icon ui-state-error ui-icon-cancel"></span>';
												 message += ' เกิดข้อผิดพลาดระหว่างการบันทึกข้อมูล !!';
												 //message += '';   
												 break;
										case '99':
												 //message = '<span class="ui-state-default ui-icon ui-icon-check"></span>';
												 message += ' ข้อมูลผิดพลาด โปรดตรวจสอบข้อมูลที่บันทึก';
												 //message += '';   
											     break;

									}
									$('#status').empty();
									$('#status').append(message);
								 	$('#status').dialog(dialogOpts);
								  }
								},
									//edit
								{ url:"users/new.php",
								  addCaption:'เพิ่มรายชื่อนักเรียน',
								  bSubmit:'ตกลง', 
								  bCancel:'ยกเลิก', 
								  bClose:'ปิด', 
								  afterComplete: function(response, postdata, formid){ 
									status = response.responseText; 
									var dialogOpts = { modal: true,
													   buttons: { "ปิด" : function(){ $(this).dialog("close"); } },
													   title: 'สถานะ',resizable:false,
													   height: 150} 
									var message = '';
					
									switch(response.responseText) {
										case '0' :  
												 //message = '<span class="ui-icon ui-state-default ui-icon-check"></span>';
												 message += ' บันทึกข้อมูล เสร็จเรียบร้อย!!';
												 message += '';    
												 break;
										case '1' :
												 //message = '<span class="ui-icon ui-state-error ui-icon-cancel"></span>';
												 message += ' เกิดข้อผิดพลาดระหว่างการบันทึกข้อมูล !!';
												 message += '';   
												 break;
										case '99':
												 //message = '<span class="ui-state-default ui-icon ui-icon-check"></span>';
												 message += ' ข้อมูลผิดพลาด โปรดตรวจสอบข้อมูลที่บันทึก';
												 message += '';   
											     break;

									}
									$('#status').empty();
									$('#status').append(message);
								 	$('#status').dialog(dialogOpts);
								  }
								},//add
							 	{ url:"users/delete.php",
								  caption: "ลบข้อมูล", 
								  msg:"ต้องการลบข้อมูล รายชื่อนี้??", 
								  bSubmit:'ลบรายชื่อ', 
								  bCancel:'ยกเลิก' ,//delete)
								  afterComplete: function(response, postdata, formid){ 
									status = response.responseText; 
									var dialogOpts = { modal: true,
													   buttons: { "ปิด" : function(){ $(this).dialog("close"); } },
													   title: 'สถานะ',resizable:false,
													   height: 150} 
									var message = '';
								    
									switch(response.responseText) {
										case '0' :  
												 //message = '<span class="ui-icon ui-state-default ui-icon-check"></span>';
												 
												 message += ' ลบข้อมูล เสร็จเรียบร้อย!!';
												message += '';    
												 break;
										case '1' :
												 //message = '<span class="ui-icon ui-state-error ui-icon-cancel"></span>';
												 message += ' เกิดข้อผิดพลาดระหว่างการลบข้อมูล !!';
												 message += '';   
												 break;
										case '99':
												 //message = '<span class="ui-state-default ui-icon ui-icon-check"></span>';
												 message += ' ข้อมูลผิดพลาด โปรดตรวจสอบข้อมูลที่บันทึก';
												 message += '';   
											     break;

									}
									$('#status').empty();
									$('#status').append(message);
								 	$('#status').dialog(dialogOpts);
								  }
							 } 
					   ).navButtonAdd( '#pager',  { caption:"", 
													buttonicon:"ui-icon-locked", 
													onClickButton:reset_password, 
													position:"last", 
													title:"รีเซ็ทรหัสผ่าน", 
													cursor: "pointer" });
				
					function reset_password(){
						
						var selRow = jQuery('#list').jqGrid('getGridParam','selrow');
						
						if(selRow != null){
							var dialogOpts = { modal: true,
							buttons: { "ยกเลิก": function(){ 
													$(this).dialog("close"); } , "ตกลง": function(){ 
												$(this).dialog("close");
												var reset_status = '';
												jQuery.ajax( {
																url:'users/reset_password.php',
																type:'POST',
																data:{ 'oper':'reset','id':selRow },
																dataType:'html',
																success: function(data){
																	if(data == 0){
																		reset_status = "รีเซ็ทรหัสผ่านเสร็จสิ้น";
																	}else if(data == 1){
																		reset_status = "รหัสผ่านถูกรีเซ็ทแล้ว นักเรียนสามารถล็อกอินเข้าใช้านได้ โดยใช้รหัสผ่านดีฟอล์ต (ให้นักเรียนแก้รหัสผ่านได้ในหน้าข้อมูลส่วนตัว)";
																	}else if(data == 99) {

																		reset_status = "ข้อมูลผิดพลาด";
																	}
																	var dialog_ops = { model:true, 
																					   title:'สถานะ', height:100 }
																	$('#status2').empty();
																	$('#status2').append(reset_status);
																	$('#status2').dialog(dialog_ops);
																}
															  });
												

												}
				
									    },
							title: 'รีเซทรหัสผ่าน',resizable:false,
							height: 150} 
						    cellData = $('#list').getCell(selRow,'student_name');
							var message = '!!รีเซท รหัสผ่าน ของนักเรียน ชื่อ ' + cellData + ' รหัสนักเรียน ' + selRow;
							$('#status').empty();
							$('#status').append(message);
							$('#status').dialog(dialogOpts);
						}else {
							var dialogOpts = { modal: true,
							buttons: { "ปิด" : function(){ $(this).dialog("close");  } },
							title: 'รเซ็ทรหัสผ่าน',resizable:false,
							height: 150} 
							var message = 'โปรดเลือกรายชื่อที่ต้องการรีเซท รหัสผ่าน';
							$('#status').empty();
							$('#status').append(message);
							$('#status').dialog(dialogOpts);
						}

					}
			});
		</script>
	<div id="tabs" class="tabs">
		<ul>
			<li><a href="#tabs-1">จัดการนักเรียน</a></li>
			<li><a href="#tabs-2">นำเข้าข้อมูลนักเรียน จำนวนมาก</a></li>
		</ul>
		<div id="tabs-1"><table id="list" ></table>
			<div id="pager" ></div>
			<div id="status" class="status" title="สถานะ"></div><div id="status2" class="status2" title="สถานะ"></div>
			<div id="passord_change"></div><br/>
		</div>  
		<div id="tabs-2">

	
			<div id='help' class='' width="400" align="center" >

			<br/>
				<div id="list_of_instruction" align="center" style="align:center;border:1px solid #eeeeee;width:600px;margin:1px;padding:10px">
				<ol style="text-align: left">
					<li>คลิกดาว์นโหลดไฟล์ตัวอย่าง  <a href="users/example_file.csv" style="text-decorate:underline;color:blue"> ดาวน์โหลด </a></li>
					<li>จะปรากฏหน้าต่างๆ Popup ให้คลิกปุ่ม Save เพื่อบันทึกไฟล์ลงเครื่องคอมพิวเตอร์</li>
					<li>จากนั้นจะได้ไฟล์ชื่อ example_file.csv (*ใช้โปรแกรม Microsoft Excel เปิดได้เท่านั้น)</li>
					<li>เปิดไฟล์ที่ดาว์นโหลดมาด้วยโปรแกรม Microsoft Excel ในข้อที่ 3 หลังจากนั้นทำการกรอกรายชื่อนักเรียนลงในคอลัมน์ ที่กำหนดให้ ดังนี้
						<ul>
							<li> - คอลัมน์  A  คือ รหัสนักเรียน</li>
							<li> - คอลัมน์  B  คือ คำนำหน้า</li>
							<li> - คอลัมน์  A  คือ ชื่อ</li>
							<li> - คอลัมน์  A  คือ นามสกุล</li>

						</ul>
					</li>
					<li>จากนั้นทำการ Save ไฟล์ เป็นชื่อตามที่ต้องการ ด้วยนามสกุลไฟล์ .csv  ดังรูปตัวอย่าง<br/>
						<img src="users/csv.jpg"></img>
					</li>
					<li>ทำการ Upload เข้าสู่ระบบจากช่องทางนี้</li>
				</ol>
				<div class="formupload">
				<form action="#" method="post" enctype="multipart/form-data" name="form">
	  			<input name="csv_file" type="file" id="csv_file">
	 			<input name="submit" type="submit" id="submit" value="Submit">
				</form><br/>

				 <span id="csv_comment"> **หากผู้ใช้งานเตรียมไฟล์ตามรูปแบบที่กำหนดไว้ให้ในข้อ 4 แล้ว ให้ข้ามขั้นตอนการ Upload มาที่ข้อ 6 ได้เลย </span>
			</div>
				</div>
		

			<br/>
		
			</div>
			
		</div>
<?php

	if(isset($_FILES['csv_file'])){
		copy($_FILES["csv_file"]["tmp_name"],$_FILES["csv_file"]["name"]); // Copy/Upload CSV
 
		/*$objConnect = mysql_connect("localhost","root","root") or die("Error Connect to Database"); // Conect to MySQL
		$objDB = mysql_select_db("mydatabase");*/

		//$FILE_CSV = fopen($_FILES["csv_file"]["name"], "r");
		
		$user = new User();
		if (@$_GET['debug'] == 1)
			$user->show_debug(FALSE);
			
		$all_prefix = $user->get_prefix();
		$student = array();
		$i = 0; 
		$is_valid = FALSE;
				
		/*$correct_type = strpos(strtolower($_FILES['csv_file']['type']),'ms-excel') || strpos(strtolower($_FILES['csv_file']['type']),'text');
		if($correct_type == false) die('ชนิดของไฟล์ไม่ถูกต้อง');*/
		
		$is_csv = strpos(strtolower($_FILES["csv_file"]["name"]),'.csv');
		if($is_csv == false) die('ไมใช่ไฟล์ csv!!!');
		
		$FILE_CSV = fopen_utf8($_FILES["csv_file"]["name"],"r");
		
		echo '<div class="line-record" padding="5"><table width=350>';
		echo '<tr>';
		echo '<th width=280 class="ui-state-default"> ข้อมูลนักเรียน </th><th class="ui-state-default">สถานะ</th></tr>';
		$success_student = 0 ;
		while (($objArr = fgetcsv($FILE_CSV, 1000, ",")) !== FALSE) {
   	 		//loop insert db
   	 		/*$j = 0;
   	 		foreach($objArr as $v){
   	 			$romeo = mb_detect_encoding($v,"UTF-8");
				echo $romeo;
   	 			If(!preg_match("/UTF-8/",$romeo)){
   	 				echo "convert" ;
				    $objArr[$j] = iconv("TIS-620","utf-8",$v);	
				    echo " to ".$objArr[$j];
				}
				$j++;
				
			} */
			/*var_dump($objArr);*/
			
   	 		list( $student_id, $prefix_name, $student_name, $student_lastname) = $objArr;

   	 		$student['student_id'] = (int)trim($student_id);
			$student['prefix_name'] = trim($prefix_name);
			$student['student_name'] = trim($student_name);
			$student['student_lastname'] = trim($student_lastname);
			$student['student_password'] = $default_password;
			$student['student_type'] = $default_student_type;
			$student['register_date'] = 'NOW()';
			$student['lastlogin_date'] = "";
			/*exit;*/
			$is_valid = $user->validates($student,"student_id", $student_id_cond) && $user->validates($student,"prefix_name", $prefix_name_cond) &&
    					$user->validates($student,"student_name", $student_name_cond) && $user->validates($student,"student_lastname",$student_name_cond) &&
				    	$user->validates($student,"student_password",$student_password_cond) && $user->validates($student,"student_type",$student_type_cond) &&
				    	$user->validates($student,"register_date",$register_date_cond) ;
				    	
		
    		if ($is_valid !== false){
    	    
				echo '<td align="left"> ' .$student['student_id'].' '.$student['prefix_name'].' ';
				echo $student['student_name']." ".$student['student_lastname']."</td>";

		   		if($user->save($student)){
		   			echo '<td align="center"><span class="ui-state-hilight"><span class="ui-icon ui-icon-check "></span></span>';
					echo '</td>'; 
					$success_student++ ;
		   		}else { 
		   			echo '<td align="center"><span class="ui-state-hilight"><span class="ui-icon ui-icon-cancel "></span></span>';
					echo '</td>';
		   		}

			}else {
				echo '<td align="left"> ' .$student['student_id'].' '.$student['prefix_name'].' ';
				echo $student['student_name']." ".$student['student_lastname']."</td>";
				echo '<td align="center">';
				echo '<a href="#" alt="ผิดพลาด" title="'.$user->error.'"><span class="ui-state-hilight"><span class="ui-icon ui-icon-close"></span></span></a>';
				echo '</td>';
			}		
				echo '</tr>';
				usleep(500 * $i );
				$i++;
	   }
		echo '</table></div>';
	
		fclose($FILE_CSV);
		echo " <br/> <span class='ui-state-default'>...ดำเนินการเสร็จสิ้น จำนวนรายชื่อนักเรียนที่เพิ่มสำเร็จ ".$success_student. " รายชื่อ จากรายชื่อทั้งหมด ".$i. " รายชื่อ</span>";
	}//end if is $_FILES['csv_file'];
?>
		</div>
	</div>
</div>
