<!DOCTYPE html>
<?php session_start();
    $id=$_SESSION['id'];
    $email=$_SESSION['email'];
    if(!isset($_SESSION['id'])) {
 	header('Location: register.php');
    }
    require_once "includes/functions.php";
    $con=dbCon(); 
?>

<html>
<head>
    <title>Stream-- Account</title>
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <script src="jquery.js"></script>
    <script src="bootstrap/js/bootstrap.js"></script>
    <link rel="shortcut icon" href="images/title_icon_proto2.png" />
    <link href="style.css" rel="stylesheet" type="text/css" />
    <style>
	body {
    	    padding-top: 60px;
    	    padding-bottom: 40px;
	}

	#page a {
	    color:#000000;
	}
    </style>
</head>
<body>
    <?php
  	require_once "navbar.php"; 
    ?>
    <div id="mainContainer">
    <br/><br/>
	<div style="margin:auto;width:40%;" id="page">
	    <h1>Account Information</h1>
	    <br/><br/>
	    <h3>Twitter</h3>
	    <a href="setupTwitter.php"><img src="images/twitter_sign_in.png" /></a>

	    <h3>Facebook</h3>
	    <a href="setupFacebook.php"><img src="images/facebook_sign_in.png" /></a>

	    <h3>Reddit</h3>
	    <a href="pStream/reddit/index.php"><img src="images/reddit_sign_in2.png" /></a>

	    <h3>RSS</h3>
	    <form method="post">
		<table style="border:none">
		    <tr>
			<td>RSS URL: </td>
			<td><input type="text" name="rss"></td>
		    </tr>
		    <tr>
			<td><button class="btn btn-primary" type="submit"  name="rssButton">Submit</button></td>
		    </tr>
		    <td>
			<tr>
			</tr>
		    </td>
		</table>
	    </form>
	    <?php 
	    	if(!empty($_POST['rssButton'])) {
    	    	//check entered data first...
    	   	 //then insert
		    if($_POST['rss']!="") {
		    	insert_rss($con,$id,$_POST['rss']);
		    	echo 'inserted';
		    } else {
		    	echo 'no link submitted';
		    }
  	    	}
	    ?>
	</div>
    </div>
</body>
</html>
