<?php

	session_start();
	ob_start();

	if(isset($_SESSION['auth']))
	{
		header("Location: home");
	}
	
?>