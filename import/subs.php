<?php 

require "../config.php";
// connect to the database
global $link;
$link = mysql_connect($dbhost,$dbuser,$dbpass);
mysql_set_charset('utf8',$link); 
mysql_select_db($dbname,$link);

/*

VS SCORM - IMS Manifest File Reader - subs.php 
Rev 2009-11-30-01
Copyright (C) 2009, Addison Robson LLC

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, 
Boston, MA 02110-1301, USA.

*/

// ------------------------------------------------------------------------------------


function import_data($file_path,$class_id,$cate_id){
		global $link;
		global $src_file;
		$return_data = true;
        // open zip
        $zip = zip_open($file_path);
            // find entry
		  do {
                $entry = zip_read($zip);
			 } while ($entry && zip_entry_name($entry) != "imsmanifest.xml");
            // open entry
            zip_entry_open($zip, $entry, "r");
			// read entry
			
			$entry_content = zip_entry_read($entry, zip_entry_filesize($entry));
			
			$xmlfile = new DomDocument;
			$xmlfile->preserveWhiteSpace = FALSE;
			$xmlfile->loadXML($entry_content);
			
			/*$organization = $xmlfile->getElementsByTagName('organization');
			foreach ($organization as $orgtemp) {
				$title_bg = $orgtemp->getElementsByTagName('title')->item(1)->nodeValue;
				$structure = $orgtemp->getAttribute('structure');
				$identifier = $orgtemp->getAttribute('identifier');
				$sql = "select count(*) as num from organizations where author ='{$identifier}' limit 1";
				$result = mysql_query($sql,$link);
				while($row = mysql_fetch_array($result)){
					if($row['num'] > 0){
						$return_data = false;
						break;
					}
				}
				if($return_data == false){
					break;
				}
			}*/
		$manifest = $xmlfile->getElementsByTagName('manifest');
		$author = $manifest->item(0)->getAttribute('identifier');
		
		$sql = "select count(*) as num from manifests where author ='{$author}' limit 1";
		$result = mysql_query($sql,$link);
		while($row = mysql_fetch_array($result)){
			if($row['num'] > 0){
				$return_data = false; // mean there's already course in system
				break;
			}
		}

		// close entry
		zip_entry_close($entry);
        // close zip
        zip_close($zip);
		
		if($return_data){
			$rs_data = insert_sco($entry_content,$class_id,$cate_id);
			if(count($rs_data) > 0){
				if(!is_dir($src_file."{$author}")){
					mkdir($src_file."{$author}", 0777);
				}
				
				$zip1 = new ZipArchive;
				$res = $zip1->open($file_path);
				if ($res === TRUE) {
					$zip1->extractTo($src_file."{$author}/");
					$zip1->close();
				} 
				$return_data = true;
			}
			
		}
		return $return_data;
}


function insert_sco($manifestfile,$class_id,$cate_id) {

  // PREPARATIONS

  // central array for resource data
  global $resourceData;
  global $link;
  // load the imsmanifest.xml file
  $xmlfile = new DomDocument;
  $xmlfile->preserveWhiteSpace = FALSE;
  $xmlfile->loadXML($manifestfile);

  // adlcp namespace
  $manifest = $xmlfile->getElementsByTagName('manifest');
  $adlcp = $manifest->item(0)->getAttribute('xmlns:adlcp');
	$metadata_link = $manifest->item(0)->getAttribute('xsi:schemaLocation');
	$author = $manifest->item(0)->getAttribute('identifier');
	$metadata_node_value = "";
	$metadata = $xmlfile->getElementsByTagName('metadata');
	if(isset($metadata->item(0)->nodeValue)){
		
		$metadata_node_value = $metadata->item(0)->nodeValue;
	}
	
	$sql="
	 INSERT INTO  `manifests` (
	`manifests_id` ,
	`metadata` ,
	`metadata_link` ,
	`author` ,
	`date`
	)
	VALUES (
	NULL ,  '{$metadata_node_value}',  '{$metadata_link}',  '{$author}', NOW( )
	)";

	$result = mysql_query($sql,$link);
	$manifests_id = mysql_insert_id($link);
	
  $organization = $xmlfile->getElementsByTagName('organization');

  foreach ($organization as $orgtemp) {
		$title_bg = $orgtemp->getElementsByTagName('title')->item(0)->nodeValue;
		$structure = $orgtemp->getAttribute('structure');
		$identifier = $orgtemp->getAttribute('identifier');
		
		$sql="
		INSERT INTO  `organizations` (
		`organizations_id` ,
		`manifests_id` ,
		`title_bg` ,
		`title_uk` ,
		`metadata` ,
		`metadata_link` ,
		`sequencing` ,
		`author` ,
		`date`
		)
		VALUES (
		NULL,  '{$manifests_id}',  '{$title_bg}',  '-',  '{$metadata_node_value}',  '{$metadata_link}',  '{$structure}',  '{$identifier}', NOW( )
		)";
		
		$result = mysql_query($sql,$link);
		$organizations_id = mysql_insert_id($link);
		
		//echo $organizations_id;
		

		////////////////////////////////////////////////////////////////////////////////////////

				$sql="INSERT INTO `organizations_class` (
					`organizations_id` ,
					`class`,
					`cate_id`
					)
					VALUES (
					'{$organizations_id}', '{$class_id}','{$cate_id}'
					)";
				$result = mysql_query($sql,$link);

		////////////////////////////////////////////////////////////////////////////////////////
	
	}
	
	// 
	


  // READ THE RESOURCES LIST

  // array to store the results
  $resourceData = array();

  // get the list of resource element
  $resourceList = $xmlfile->getElementsByTagName('resource');

  $r = 0;
  foreach ($resourceList as $rtemp) {

    // decode the resource attributes
    $identifier = $resourceList->item($r)->getAttribute('identifier');
    $resourceData[$identifier]['type'] = $resourceList->item($r)->getAttribute('type');
    $resourceData[$identifier]['scormtype'] = $resourceList->item($r)->getAttribute('adlcp:scormtype');
    $resourceData[$identifier]['href'] = $resourceList->item($r)->getAttribute('href');

    // list of files
    $fileList = $resourceList->item($r)->getElementsByTagName('file');

    $f = 0;
    foreach ($fileList as $ftemp) {
      $resourceData[$identifier]['files'][$f] =  $fileList->item($f)->getAttribute('href');
      $f++;
    }

    // list of dependencies
    $dependencyList = $resourceList->item($r)->getElementsByTagName('dependency');

    $d = 0;
    foreach ($dependencyList as $dtemp) {
      $resourceData[$identifier]['dependencies'][$d] =  $dependencyList->item($d)->getAttribute('identifierref');
      $d++;
    }

    $r++;

  }

  // resolve resource dependencies to create the file lists for each resource
  foreach ($resourceData as $identifier => $resource) {
    $resourceData[$identifier]['files'] = resolveIMSManifestDependencies($identifier);
  }

  // READ THE ITEMS LIST

  // arrays to store the results
  $itemData = array();

  // get the list of resource element
  $itemList = $xmlfile->getElementsByTagName('item');

  $i = 0;
  foreach ($itemList as $itemp) {
    // decode the resource attributes
    $identifier = $itemList->item($i)->getAttribute('identifier');
    $itemData[$identifier]['identifierref'] = $itemList->item($i)->getAttribute('identifierref');
    $itemData[$identifier]['title'] = $itemList->item($i)->getElementsByTagName('title')->item(0)->nodeValue;
	$itemData[$identifier]['invisible'] = $itemList->item($i)->getAttribute('isvisible');
	$itemData[$identifier]['time_limi_action'] = $itemList->item($i)->getAttribute('adlcp:timelimitaction');
    $itemData[$identifier]['masteryscore'] = $itemList->item($i)->getElementsByTagNameNS($adlcp,'masteryscore')->item(0)->nodeValue;
    $itemData[$identifier]['datafromlms'] = $itemList->item($i)->getElementsByTagNameNS($adlcp,'datafromlms')->item(0)->nodeValue;

    $i++;

  }

  // PROCESS THE ITEMS LIST TO FIND SCOS
  
  // array for the results
  $SCOdata = array();

  // loop through the list of items
   $parent_id = 0;
  foreach ($itemData as $identifier => $item) {
  


    // find the linked resource
    $identifierref = $item['identifierref'];
   
    // is the linked resource a SCO? if not, skip this item
    if (strtolower($resourceData[$identifierref]['scormtype']) != 'sco') { 
	
		$sql = "INSERT INTO `items` (
				`item_id` ,
				`organizations_id` ,
				`parent_id` ,
				`title_bg` ,
				`title_uk` ,
				`invisible` ,
				`parameters` ,
				`masteryscore` ,
				`metadata` ,
				`metadata_link` ,
				`time_limi_action` ,
				`data_from_lms` ,
				`sequencing` ,
				`presentation` ,
				`author` ,
				`date`
				)
				VALUES (
				NULL, '{$organizations_id}', '0', '{$title_bg}', '{$item['title']}', '{$item['invisible']}', '-', '{$item['masteryscore']}','{$metadata_node_value}', '{$metadata_link}', '{$item['time_limi_action']}', '{$item['datafromlms']}', '{$structure}', '', '{$identifier}', NOW( )
				)";
				
				$result = mysql_query($sql,$link);
				$parent_id = mysql_insert_id($link);
				
				//echo $sql."----000---{$parent_id}<br />";
	
	}else{
	
		$sql = "INSERT INTO `items` (
				`item_id` ,
				`organizations_id` ,
				`parent_id` ,
				`title_bg` ,
				`title_uk` ,
				`invisible` ,
				`parameters` ,
				`masteryscore` ,
				`metadata` ,
				`metadata_link` ,
				`time_limi_action` ,
				`data_from_lms` ,
				`sequencing` ,
				`presentation` ,
				`author` ,
				`date`
				)
				VALUES (
				NULL, '{$organizations_id}', '{$parent_id}', '{$title_bg}', '{$item['title']}', '{$item['invisible']}', '-','{$item['masteryscore']}', '{$metadata_node_value}', '{$metadata_link}', '{$item['time_limi_action']}', '{$item['datafromlms']}', '{$structure}', '{$identifierref}', '{$identifier}', NOW( )
				)";
				
				$result = mysql_query($sql,$link);
				$item_id = mysql_insert_id($link);
				
				//echo $sql."----1111--<br />";
				
				
		$sql = "INSERT INTO `resources` (
			`resource_id` ,
			`title_bg` ,
			`title_uk` ,
			`type` ,
			`link_bg` ,
			`link_uk` ,
			`metadata` ,
			`metadata_link` ,
			`author` ,
			`date`
			)
			VALUES (
			NULL, '', '', '{$resourceData[$identifierref]['type']}', '{$resourceData[$identifierref]['href']}', '', '{$metadata_node_value}', '{$metadata_link}', '{$identifierref}', NOW( )
			)";
			
			$result = mysql_query($sql,$link);
			$resource_id = mysql_insert_id($link);
			
			//echo $sql."---2222---<br />";
			
			$sql="INSERT INTO `items_resources` (
				`items_resources_id` ,
				`resource_id` ,
				`item_id` ,
				`author` ,
				`date`
				)
				VALUES (
				NULL , '{$resource_id}', '{$item_id}', '{$identifierref}', NOW( )
				)";
				
			$result = mysql_query($sql,$link);

	
		// save data that we want to the output array
		$SCOdata[$identifier]['title'] = $item['title'];
		$SCOdata[$identifier]['masteryscore'] = $item['masteryscore'];
		$SCOdata[$identifier]['datafromlms'] = $item['datafromlms'];
		$SCOdata[$identifier]['href'] = $resourceData[$identifierref]['href'];
		$SCOdata[$identifier]['files'] = $resourceData[$identifierref]['files'];
		
	}

  }
  return $SCOdata;

}

// ------------------------------------------------------------------------------------

// recursive function used to resolve the dependencies (see above)
function resolveIMSManifestDependencies($identifier) {

  global $resourceData;

  $files = $resourceData[$identifier]['files'];

  $dependencies = $resourceData[$identifier]['dependencies'];
  if (is_array($dependencies)) {
    foreach ($dependencies as $d => $dependencyidentifier) {
      if (is_array($files)) { 
        $files = array_merge($files,resolveIMSManifestDependencies($dependencyidentifier));
      }
      else {
        $files = resolveIMSManifestDependencies($dependencyidentifier);
      }
      unset($resourceData[$identifier]['dependencies'][$d]); 
    }
    $files = array_unique($files);
  }

  return $files;

}

?>