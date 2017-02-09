<?php require("php/authentication.php"); ?>
<?php

	if($_SERVER['REQUEST_METHOD'] == "POST"){
		
		$message="";
		
		if(isset($_POST['username']) && isset($_POST['password'])){
			
			$username = $_POST['username'];
			$password = $_POST['password'];
			
			require("php/connection.php");
			
			if(empty($username)){
				$errors['username'] = "The username field is empty.";
			}
			
			if(empty($password)){
				$errors['password'] = "The password field is empty.";
			}
			
			/* Check username */
			$stmt = mysqli_prepare($link, "SELECT * FROM users WHERE username = ? AND password = ?");
			mysqli_stmt_bind_param($stmt, 'ss', $username, md5($password));
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);

			if(mysqli_num_rows($result) == 0){
				$errors['username'] = "Invalid credentials";
			}
			
			if(!isset($errors)){
				$user = mysqli_fetch_assoc($result);
				$_SESSION['auth'] = $user['id'];
				$_SESSION['auth_username'] = $user['username'];
				header("Location: home/");
			}
			
		} else {
			$message = '<div class="ui negative message">Missing information.</div>';
		}	
	}


?>
<?php require("layouts/header.html"); ?>
	
	<?php require("layouts/menu.php"); ?>
	<form method="POST" class="ui bottom attached form segment">
		<?php if(isset($message)) echo $message; ?>
		<div class="<?php if(isset($errors['username'])) echo 'error'; ?> field">
			<input type="text" name="username" placeholder="Username" value="<?php if(isset($username)) echo $username; ?>">
			<?php if(isset($errors['username'])): ?>
				<span class="ui error text"><?php echo $errors['username']; ?></span>
			<?php endif; ?>
		</div>
		<div class="<?php if(isset($errors['password'])) echo 'error'; ?> field">
			<input type="password" name="password" placeholder="Password">
			<?php if(isset($errors['password'])): ?>
				<span class="ui error text"><?php echo $errors['password']; ?></span>
			<?php endif; ?>
		</div>
		<input type="submit" class="ui orange button" value="Sign In">
	</form>
			

<?php require("layouts/footer.html");?>