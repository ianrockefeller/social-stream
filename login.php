<!DOCTYPE html>
<html>
<head>
<title>Stream-- Register</title>
<script type="text/javascript" src="jquery.js"></script>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="mainContainer">
<?php
  include 'navbar.php';//top static nav bar
?>
<br/><br/>
<div style="margin:auto;width:40%;">
<h2>Login</h2>
<form method="post" action="log.php" id="loginform">
<table style="border:none;">
  <tr>
     <td>Email: </td>
     <td><input type="text" name="email" id="email" /></td>
     <td><div id="emailerr"></div></td>
  </tr>
  <tr>
    <td>Password: </td>
    <td><input type="password" name="pw" id="pw" /></td>
    <td><div id="pwerr"></div></td>
  </tr>
  <tr>
      <td></td>
      <td><input type="submit" value="Submit" /></td>
  </tr>
  <tr><td></td>
    <td><div id="loginResult"></div></td>
  </tr>
</table>
</form>
</div>
</div>
</body>
</html>
