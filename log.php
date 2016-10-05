<?php 
  include 'includes/functions.php';
  if($_POST) {
  $con=dbCon();
  if($con) {
    $b=login($con,$_POST['email'],$_POST['pw']);
	if($b) {
		header('Location: index.php');
	} else {
		header('Location: register.php');
	}
  }
 }
?>
