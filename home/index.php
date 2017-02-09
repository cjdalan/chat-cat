<?php require("../php/guest.php"); ?>
<?php require("../layouts/header.html"); ?>
	
	<?php require("../layouts/menu.php"); ?>
	<div class="ui attached segment">
		<div class="ui centered small header">Welcome to ChatCat</div>
	</div>
	<div class="ui attached segment" name="shoutbox">
		
		<?php
			
			require("../php/connection.php");
			/* Get shouts */
			$result = mysqli_query($link, "SELECT * FROM (SELECT * FROM shouts ORDER BY created_at ASC LIMIT 50) AS shouts JOIN users ON shouts.user_id = users.id ORDER BY created_at ASC");
			$count = mysqli_num_rows($result);
			
			if($count == 0){
				echo '<div class="ui message">No shouts.</div>';
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
		
	</div>
	<div class="ui bottom attached secondary segment">
		<div class="ui icon input">
			<i class="ui orange send icon"></i>
			<input type="text" name="shout" placeholder="Shout your heart out!">
		</div>
	</div>
	
	<style>
		[name="shoutbox"]{
			height: 350px;
			overflow: auto;
		}
		
		.ui.icon.input{
			width: 100%;
		}
	</style>
	
	<script>
		
		$('[name="shoutbox"]').scrollTop($('[name="shoutbox"]')[0].scrollHeight);
		
		/* Create shout */
		$('[name="shout"]').keyup(function(e){
			
			var shout = $.trim($('[name="shout"]').val());
			if(e.keyCode == 13 && shout){
				
				/* Disable shout */
				$('[name="shout"]').attr("disabled", "disabled");
				
				$.ajax({
					url: '../php/create_shout.php',
					method: 'POST',
					data: {
						'shout': shout
					},
					success: function(data){
						$('[name="shout"]').val("");
					}
				});
				
				/* Enable shout */
				setTimeout(function(){
					$('[name="shout"]').removeAttr("disabled");
				}, 1000);
			
			}
		
		});
		
		/* Refresh shouts */
		var prevData = "";
		
		setInterval(function(){
			$('[name="shoutbox"]').load('../php/get_shout.php', function(data){
				if(data != prevData){
						$('[name="shoutbox"]').scrollTop($('[name="shoutbox"]')[0].scrollHeight);
				}
				prevData = data;
			});
		}, 500);
		
	</script>

<?php require("../layouts/footer.html"); ?>