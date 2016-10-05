    <div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
	    <div class="containter">
		<a class="brand" href="#" style="color:yellow;">pStream</a>
		<div class="nav-collapse collapse">
		    <ul class="nav">
			<li><a href="index.php">Home</a></li>
			<li><a href="about.php">About</a></li>
			<?php
			    if (!isset($_SESSION['id'])){
				echo '<li><a href="register.php">Register</a></li>';
			    }
			?>
		    </ul>
		    <?php
			if (!isset($_SESSION['id'])){
			    echo <<<END
<form class="navbar-form pull-right" action="log.php" method="post" id="login">
    			<input class="span2" type="text" placeholder="Username" name="email" id="email" />
    			<input class="span2" type="password" placeholder="Password" name="pw" id="pw" />
    			<button type="submit" class="btn btn-primary">Sign in</button>
		    </form>
END;
			}
			else{
			    echo <<<END
<ul class="nav pull-right">
    			<li><a href="account.php">$email</a></li>
    			<li><a href="logout.php">Logout</a></li>
		     </ul>
END;
			}
		    ?>
		</div>
	    </div>
	</div>
    </div>
