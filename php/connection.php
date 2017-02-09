<?php
	
	$database['host'] = "localhost";
	$database['username'] = "root";
	$database['password'] = "";
	$database['database'] = "chatcat";

	$link = mysqli_connect(
		$database['host'], 
		$database['username'], 
		$database['password'],
		$database['database']
	);
	
?>