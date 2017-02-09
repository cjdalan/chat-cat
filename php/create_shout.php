<?php require("guest.php"); ?>
<?php

	$shout = htmlentities($_POST['shout']);
	$user_id = $_SESSION['auth'];

	require("connection.php");
	
	$stmt = mysqli_prepare($link, "INSERT INTO shouts (user_id, message) VALUES( ?, ?)");
	mysqli_stmt_bind_param($stmt, 'is', $user_id, $shout);
	mysqli_stmt_execute($stmt);

?>