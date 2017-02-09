<?php require("../php/guest.php"); ?>
<?php
		
	if($_SERVER['REQUEST_METHOD'] == "POST"){
		
		$message = "";
		if(isset($_FILES['picture']['tmp_name'])){
			
			$picture = $_FILES['picture']['tmp_name'];
			
			if(empty($picture)){
				$errors['picture'] = "The picture field is required";
			} elseif(getimagesize($picture) === false){
				$errors['picture'] = "The file is not an image";
			}
			
			if(!isset($errors)){
				
				$destination = "../images/". $_SESSION['auth'] .".jpg";
				move_uploaded_file($picture, $destination);
				$message = '<div class="ui positive message">You have successfully updated your picture</div>';
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
	<form method="POST" class="ui bottom attached form segment" enctype="multipart/form-data">
		<?php if(isset($message)) echo $message; ?>
		<div class="<?php if(isset($errors['picture'])) echo "error"; ?> field">
			<input type="file" name="picture" accept="image/*">
			<?php if(isset($errors['picture'])): ?>
				<span class="ui error text"><?php echo $errors['picture']; ?></span>
			<?php endif; ?>
		</div>
		
		<input type="submit" class="ui orange button" value="Update">
		<a class="ui button" href="../profile?u=<?php echo $_SESSION['auth_username']; ?>">Cancel</a
	</form>

<?php require("../layouts/footer.html"); ?>