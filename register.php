<?php require("php/authentication.php"); ?>
<?php 
	
	if($_SERVER['REQUEST_METHOD'] == "POST"){
		
		$message = "";
		
		/* Form validations */
		if(isset($_POST['name']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password_confirmation'])){
			
			$name = $_POST['name'];
			$username = $_POST['username'];
			$password = $_POST['password'];
			$password_confirmation = $_POST['password_confirmation'];			
			
			require("php/connection.php");
			
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
			} elseif(mysqli_num_rows($result) > 0){
				$errors['username'] = "The username has already been taken";
			}
			
			if(empty($password)){
				$errors['password'] = "The password field is required";
			} elseif(strlen($password) < 6){
				$errors['password'] = "The password must be at least 6 characters";
			} elseif(strlen($password) >= 20){
				$errors['password'] = "The password must not be greater than 20 characters";
			} elseif(preg_match('/[^a-zA-Z0-9]/', $password)){
				$errors['password'] = "The password may only contain letters, and numbers";
			} elseif($password !== $password_confirmation){
				$errors['password'] = "The password confirmation does not match";
			}
			
			/* No errors */
			if(!isset($errors)){
				$stmt = mysqli_prepare($link, "INSERT INTO users(username, password, name) VALUES(?, ?, ?)");
				mysqli_stmt_bind_param($stmt, 'sss', $username, md5($password), $name);
				$result = mysqli_stmt_execute($stmt);
				
				copy("images/default.png", "images/". mysqli_insert_id($link) .".jpg");
				
				$message = '<div class="ui positive message">You have successfully registered.</div>';
				
				unset($name);
				unset($username);
			}
			
		} else {
			$message='<div class="ui negative message">Missing information.</div>';
		}
	}

?>
<?php require("layouts/header.html"); ?>
	
	<?php require("layouts/menu.php"); ?>
	<form method="POST" class="ui bottom attached form segment">
		<?php if(isset($message)) echo $message; ?>
		<div class="<?php echo isset($errors['name']) ? 'error' : ''; ?> field">
			<input type="text" name="name" placeholder="Name" value="<?php if(isset($name)) echo $name; ?>">
			<?php if(isset($errors['name'])): ?>
				<span class="ui error text"><?php echo $errors['name']; ?></span>
			<?php endif; ?>
		</div>
		<div class="<?php echo isset($errors['username']) ? 'error' : ''; ?> field">
			<input type="text" name="username" placeholder="Username" value="<?php if(isset($username)) echo $username; ?>">
			<?php if(isset($errors['username'])): ?>
				<span class="ui error text"><?php echo $errors['username']; ?></span>
			<?php endif; ?>
		</div>
		<div class="<?php echo isset($errors['password']) ? 'error' : ''; ?> field">
			<input type="password" name="password" placeholder="Password">
			<?php if(isset($errors['password'])): ?>
				<span class="ui error text"><?php echo $errors['password']; ?></span>
			<?php endif; ?>
		</div>
		<div class="field">
			<input type="password" name="password_confirmation" placeholder="Re-enter password">
		</div>
		<input type="submit" class="ui orange button" value="Sign Up">
	</form>

<?php require("layouts/footer.html");?>