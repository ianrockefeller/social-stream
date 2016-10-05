<!DOCTYPE html> 
<html>
<head>
<title>Stream-- Register</title>
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
<script src="bootstrap/js/bootstrap.js"></script>
<script src="jquery.js"></script>
<link href="style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    body {
	padding-top: 60px;
	padding-bottom: 40px;
    }
</style>
</head>
<body>
<?php
  include 'navbar.php'; 
?>
<div id="mainContainer">
<br/><br/>
<div style="margin:auto;width:40%;">
<h2>Register</h2>
<form name="register" action="reg.php" method="post">
<table style="border:none;">
  <tr>
     <td>Email: </td>
     <td><input type="text" name="user" /></td>
  </tr>
  <tr>
    <td>Password: </td>
    <td><input type="password" name="pw"  /></td>
  </tr>
  <tr>
      <td></td>
      <td><input type="submit" value="Submit" name="registerButton" /></td>
  </tr>
</table>
</form>
</div>
</div>
</body>
</html>
