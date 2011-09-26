<?php

   $output = shell_exec('ipconfig');
	preg_match_all('/IPv4.Address.*:.(([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3}))/i', $output, $ips);
	$local_ip = array();
	foreach($ips[1] as $ip){
		if(is_private_ip($ip)){
			$local_ip[] = $ip;
		}
	}
	print_r($local_ip);
?>