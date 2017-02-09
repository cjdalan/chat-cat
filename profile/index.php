<?php require("../php/guest.php"); ?>
<?php require("../layouts/header.html"); ?>
	
	<?php require("../layouts/menu.php"); ?>
	
	<div class="ui bottom attached segment">
		<?php
			
			if(isset($_GET['u'])){
			
				require("../php/connection.php");
				
				/* Get user info */
				$stmt = mysqli_prepare($link, "SELECT * FROM users WHERE username = ?");
				mysqli_stmt_bind_param($stmt, 's', $_GET['u']);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				
				if(mysqli_num_rows($result) != 0){
					
					$user = mysqli_fetch_assoc($result);
					echo '<div class="ui center aligned header">
							<img src="../images/'. $user['id'] .'.jpg" class="ui bordered small image">
							<div class="sub header">
								<p>'. $user['name'] .'</p>
								@'. $user['username'] .'
							</div>
						  </div>';
				
					if($user['id'] == $_SESSION['auth']){
						echo '<div class="ui three item labeled icon secondary menu">
								<a class="item" href="update_about.php">
									<i class="ui edit icon"></i>
									Update Profile
								</a>
								<a class="item" href="update_password.php">
									<i class="ui lock icon"></i>
									Update Password
								</a>
								<a class="item" href="update_picture.php">
									<i class="ui photo icon"></i>
									Update Picture
								</a>
						
							  </div>';
					}
					
				} else {
					echo '<div class="ui message">User not found.</div>';
				}
			} else {
				echo '<div class="ui message">Missing information.</div>';
			}
		?>
	</div>

<?php require("../layouts/footer.html"); ?>