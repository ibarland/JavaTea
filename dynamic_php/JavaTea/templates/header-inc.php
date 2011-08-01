<?php
$link = 'uninitialized';
$name = 'login';
/*
if (is_authed()) {
  $link = ROOT_DIR . '/logout.php';
  $name = 'Logout ' . $_SESSION['username'];
  }
else {
  $link = ROOT_DIR . '/login.php';
  $name = 'Login';
  }
*/
?>
  <div align="center">
   <a href="<?php echo ROOT_DIR; ?>">Home</a>&nbsp;&nbsp;&nbsp;
   <a href="<?php echo ROOT_DIR; ?>/FAQ.php">FAQ</a>&nbsp;&nbsp;&nbsp;
   <a href="<?php echo $link; ?>"><?php echo $name; ?></a>
   <img src="<?php echo $projRootDir; ?>/images/javatealogo_small.png" height="100px" style="float:right;" />
  </div>
  <br/>
  <br/>
  <br/>
