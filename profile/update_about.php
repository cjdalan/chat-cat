<?php require("../php/guest.php"); ?>
<?php
		
	require("../php/connection.php");

	$stmt = mysqli_prepare($link, "SELECT * FROM users WHERE id = ?");
	mysqli_stmt_bind_param($stmt, 'd', $_SESSION['auth']);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	$user = mysqli_fetch_assoc($result);
	
	if($_SERVER['REQUEST_METHOD'] == "POST"){
		
		$message="";
		
		if(isset($_POST['name']) && isset($_POST['username'])){
			
			$name = $_POST['name'];
			$username = $_POST['username'];
			
			if(empty($name)){
				$errors['name'] = "The name field is required";
			} elseif(preg_match('/[^a-zA-Z ]/', $name)){
				$errors['name'] = "The name field contains invalid characters";
			}
			
			/* Query username */
			$stmt = mysqli_prepare($link, "SELECT * FROM users WHERE username = ?");
			mysqli_stmt_bind_param($stmt, 's', $username);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			
			if(empty($username)){
				$errors['username'] = "The username field is required";
			} elseif(strlen($username) < 3){
				$errors['username'] = "The username must be at least 3 characters";
			} elseif(strlen($username) >= 20){
				$errors['username'] = "The username may not be greater than 20 characters";
			} elseif(preg_match('/[^a-zA-Z0-9_-]/', $username)) {
				$errors['username'] = "The username may only contain letters, numbers, and dashes";
			} elseif($username == $user['username']) {
				$errors['username'] = "The username is the same";
			} elseif(mysqli_num_rows($result) > 0){
				$errors['username'] = "The username has already been taken";
			}
			
			if(!isset($errors)){
				
				$stmt = mysqli_prepare($link, "UPDATE users SET name = ?, username = ? WHERE id = ?");
				mysqli_stmt_bind_param($stmt, 'ssd', $name, $username, $user['id']);
				mysqli_stmt_execute($stmt);
				
				$_SESSION['auth_username'] = $username;
				
				$message='<div class="ui positive message">You have successfully updated your profile</div>';
			}

		} else {
			$message = '<div class="ui negative message">Missing information</div>';
		}
		
	}
	
?>
<?php require("../layouts/header.html"); ?>
	
	<?php require("../layouts/menu.php"); ?>
	
	<div class="ui attached segment">
		<div class="ui centered small header">Update Profile</div>
	</div>
	<form method="POST" class="ui bottom attached form segment">
		<?php if(isset($message)) echo $message; ?>
		<div class="<?php if(isset($errors['name'])) echo 'error'; ?> field">
			<input type="text" name="name" placeholder="Name" value="<?php echo isset($name) ? $name : $user['name']; ?>">
			<?php if(isset($errors['name'])): ?>
				<span class="ui error text"><?php echo $errors['name']; ?></span>
			<?php endif; ?>
		</div>
		<div class="<?php if(isset($errors['username'])) echo 'error'; ?> field">
			<input type="text" name="username" placeholder="Username" value="<?php echo isset($username) ? $username : $user['username']; ?>">
			<?php if(isset($errors['username'])): ?>
				<span class="ui error text"><?php echo $errors['username']; ?></span>
			<?php endif; ?>
		</div>
		<input type="submit" class="ui orange button" value="Update">
		<a class="ui button" href="../profile?u=<?php echo $_SESSION['auth_username']; ?>">Cancel</a
	</form>

<?php require("../layouts/footer.html"); ?>