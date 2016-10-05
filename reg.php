<?php
require_once 'includes/functions.php';

if($_POST) {
	$con= dbCon();
	$str="email being used please try again";
	if($con) {
		if($result=insert_user($con,$_POST['user'],$_POST['pw'])) {
			$b = login($con, $_POST['user'], $_POST['pw']);
			header('Location: account.php');
		} else {
			header('Location: index.php?message='.$str);
		}
	}
}
?>
