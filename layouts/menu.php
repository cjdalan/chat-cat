<?php if(!isset($_SESSION['auth'])): ?>

<div class="ui two item top attached tabular menu">
	<a class="<?php if(strpos($_SERVER['PHP_SELF'], "/index.php"))  echo 'active'; ?> item" href="index.php">
		Sign In
	</a>
	<a class="<?php if(strpos($_SERVER['PHP_SELF'], "/register.php")) echo 'active'; ?> item" href="register.php">
		Sign Up
	</a>
</div>

<?php else: ?>

<div class="ui three item icon top attached tabular menu">

	<a class="<?php if(strpos($_SERVER['PHP_SELF'], "/home"))  echo 'active'; ?> item" href="../home/">
		<i class="ui home icon"></i>
	</a>

	<a class="<?php if(strpos($_SERVER['PHP_SELF'], "/profile")) echo 'active'; ?> item" href="../profile?u=<?php echo $_SESSION['auth_username']; ?>">
		<i class="ui user icon"></i>
	</a>

	<a class="item" href="../php/signout.php">
		<i class="ui sign out icon"></i>
	</a>
</div>

<?php endif; ?>
	
