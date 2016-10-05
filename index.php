<!DOCTYPE html>
<?php session_start();
$id=$_SESSION['id'];
$email=$_SESSION['email'];
//session_write_close();
?>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pStream</title>    	
    <script type="text/javascript" src="jquery.js"></script>
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link type="text/css" href="style.css" rel="stylesheet" />
    <link rel="shortcut icon" href="images/title_icon_proto2.png">
    <script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
    <style type="text/css">
	body {
	    padding-top: 60px;
	    padding-bottom: 40px;
	}
    </style>

    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">	
</head>
<body>
<?php 
  require "includes/functions.php";
  include "navbar.php";//static nav bar
  $con=dbCon();
?>
<div id="mainContainer">
<br/>
<br/>

<?php 
if(isset($_SESSION['id']) && isset($_SESSION['email'])) {
	getFeed($con,$id);
} else {
	echo '<h1 style="text-align:center;">Yo! Welcome to pStream! Please register or login to get started.</h1>';
}
?>

<?php mysqli_close($con);?>
</div>
</body>
</html>
