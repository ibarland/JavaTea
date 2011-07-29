<?php
$link = 'uninitialized';
$name = 'uninitialized';
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
  </div>
  <br/>
  <br/>
  <br/>
