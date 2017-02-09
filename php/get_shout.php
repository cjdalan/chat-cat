<?php require("guest.php"); ?>
<?php

	require("connection.php");

	$result = mysqli_query($link, "SELECT * FROM (SELECT * FROM shouts ORDER BY created_at ASC LIMIT 50) AS shouts JOIN users ON shouts.user_id = users.id ORDER BY created_at ASC");
	$count = mysqli_num_rows($result);
	
	if($count == 0){
		echo '<div class="ui message">No shouts</div>';
	} else {
		
		echo '<div class="ui feed">';
		while($shout = mysqli_fetch_assoc($result)){
			
			echo '<div class="event">
					<div class="label">
						<img src="../images/'. $shout['user_id'] .'.jpg" class="ui avatar image">
					</div>
					<div class="content">
						<div class="summary">';
			
			if($shout['user_id'] == $_SESSION['auth'])
				echo '<div class="ui orange label">'. $shout['message'] .'</div>';
			else 
				echo '<div class="ui label">'. $shout['message'] .'</div>';
				
			echo	'</div>
					 <div class="meta">
						<a class="username" href="../profile?u='. $shout['username'] .'">@'. $shout['username'] .'</a>
						<a class="time">'. date("h:i a", strtotime($shout['created_at'])) .'</a>
					 </div>
					</div>
				  </div>';
			
		}
		
		echo '</div>';
	
	}


?>