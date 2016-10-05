<div id="navBar">
  <ul class="links">
    <li><a href="#" style="color:yellow">pStream</a></li>
    <li><a href="index.php">Home</a></li>
    <li><a href="about.php">About</a></li>
  <?php if(!isset($_SESSION['id'])) {
	echo "<li><a href='register.php'>Register</a></li>";
    include 'loginbar.php';
  } else {
	include 'userbar.php';
  }?>
  
  </ul>
</div>


