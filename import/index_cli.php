<?php 

require "subs.php";

/*$class_data = file_get_contents('class.txt');

$class_arr = split(",",$class_data);
$zip_path = $class_arr[0];
$class_id = $class_arr[1]; */

if (isset($argv[1])) { 
	$zip_path = $argv[1] ; 
	
} else {
	die("require command line argument");
}

if (isset($argv[2])) {
	$class_id = $argv[2] ;
}

if (isset($argv[3])) {
	$cate_id = $argv[3] ;
}
//echo "\n import scorm data zip => " . $zip_path ; 
//echo "\n import on class => " . $class_id ."\n"; 

$SCOdata = import_data($zip_path,$class_id,$cate_id);
if ($SCOdata ) {
	echo "0";
}else {
	echo "99";
}
die();
if(!$SCOdata){
	die("die");
}


/*$SCOdata = readIMSManifestFile('../scorm/playable/imsmanifest.xml');*/

// output table header row
$SCOListTable  = "<table cellpadding=3 cellspacing=0 border=1>\n";
$SCOListTable .= "<tr>\n";
$SCOListTable .= "\t<td valign=top align=left><b>Identifier</b></td>\n";
$SCOListTable .= "\t<td valign=top align=left><b>Title</b></td>\n";
$SCOListTable .= "\t<td valign=top align=left><b>MasteryScore</b></td>\n";
$SCOListTable .= "\t<td valign=top align=left><b>LaunchData</b></td>\n";
$SCOListTable .= "\t<td valign=top align=left><b>SCO Entry Point</b></td>\n";
$SCOListTable .= "\t<td valign=top align=left><b>Required Files</b></td>\n";
$SCOListTable .= "</tr>\n";

// loop through the list of items
foreach ($SCOdata as $identifier => $SCO) {

  // data that we want 
  $SCOListTable .= "<tr>\n";
  $SCOListTable .= "\t<td valign=top align=left>".cleanVar($identifier)."</td>\n";
  $SCOListTable .= "\t<td valign=top align=left>".cleanVar($SCO['title'])."</td>\n";
  $SCOListTable .= "\t<td valign=top align=left>".cleanVar($SCO['masteryscore'])."</td>\n";
  $SCOListTable .= "\t<td valign=top align=left>".cleanVar($SCO['datafromlms'])."</td>\n";
  $SCOListTable .= "\t<td valign=top align=left>".cleanVar($SCO['href'])."</td>\n";
  $SCOListTable .= "\t<td valign=top align=left>".implode('<br>',$SCO['files'])."</td>\n";
  $SCOListTable .= "</tr>\n";

}

$SCOListTable .= "</table>\n";

// function to clean data for display
function cleanVar($value) {
  //$value = (trim($value) == "") ? "&nbsp;" : htmlentities(trim($value));
  return $value;
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
  <title></title>
  <style type="text/css">
  p,td,li,body,input,select,textarea {
    font-family: verdana, sans-serif;
    font-size: 10pt;
  }
  h1 {
    font-weight: bold;
    font-size: 12pt;
  }
  h2 {
    font-weight: bold;
    font-size: 11pt;
  }
  </style>
</head>
<body bgcolor="#ffffff">

<h2>SCO Data</h2>
<p><?php print $SCOListTable; ?>

</body>
</html>