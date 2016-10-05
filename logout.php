<?php 
session_start();
session_destroy();
//unset($_SESSION['email']);
//unset($_SESSION['id']);
//unset($_SESSION['']);
//here

header('Location: index.php');
?>
