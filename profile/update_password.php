<?php require("../php/guest.php"); ?>
<?php

	if($_SERVER['REQUEST_METHOD'] == "POST"){
		
		$message = "";
		
		if(isset($_POST['password']) && isset($_POST['new_password']) && isset($_POST['password_confirmation'])){
			
			$password = $_POST['password'];
			$new_password = $_POST['new_password'];
			$password_confirmation = $_POST['password_confirmation'];
			
			require("../php/connection.php");
			
			/*  Query for password*/
			$stmt = mysqli_prepare($link, "SELECT password FROM users WHERE id = ?");
			mysqli_stmt_bind_param($stmt, 'd', $_SESSION['auth']);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			$user = mysqli_fetch_assoc($result);
			
			if(empty($password)){
				$errors['password'] = "The password field is required";
			} elseif(md5($password) != $user['password']){
				$errors['password'] = "The password is incorrect";
			}
			
			if(empty($new_password)){
				$errors['new_password'] = "The new password field is required";
			} elseif(preg_match('/[^a-zA-Z0-9]/', $new_password)){
				$errors['new_password'] = "The password may only contain letters, and numbers";
			} elseif($new_password != $password_confirmation){
				$errors['new_password'] = "The password confirmation does not match";
			}
			
			if(!isset($errors)){
				
				$stmt = mysqli_prepare($link, "UPDATE users SET password = ? WHERE id = ?");
				mysqli_stmt_bind_param($stmt, 'sd', md5($new_password), $_SESSION['auth']);
				mysqli_stmt_execute($stmt);
				
				$message = '<div class="ui positive message">You have successfully changed your password.</div>';
				
			}
			
		} else {
			$message='<div class="ui negative message">Missing information.</div>';
		}
		
	}


?>
<?php require("../layouts/header.html"); ?>
	
	<?php require("../layouts/menu.php"); ?>
	
	<div class="ui attached segment">
		<div class="ui centered small header">Update Password</div>
	</div>
	<form method="POST" class="ui bottom attached form segment">
		<?php if(isset($message)) echo $message; ?>
		<div class="<?php if(isset($errors['password'])) echo "error"; ?> field">
			<input type="password" name="password" placeholder="Password">
			<?php if(isset($errors['password'])): ?>
				<span class="ui error text"><?php echo $errors['password']; ?></span>
			<?php endif; ?>
		</div>
		<div class="<?php if(isset($errors['new_password'])) echo "error"; ?> field">
			<input type="password" name="new_password" placeholder="New password">
			<?php if(isset($errors['new_password'])): ?>
				<span class="ui error text"><?php echo $errors['new_password']; ?></span>
			<?php endif; ?>
		</div>
		<div class="field">
			<input type="password" name="password_confirmation" placeholder="Re-enter password">
		</div>
		<input type="submit" class="ui orange button" value="Update">
		<a class="ui button" href="../profile?u=<?php echo $_SESSION['auth_username']; ?>">Cancel</a
	</form>

<?php require("../layouts/footer.html"); ?>