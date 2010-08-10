<?php
$link = '';
$name = '';
if(is_authed()) {
    $link = '/JavaTea/logout.php';
    $name = 'Logout';
} else {
    $link = '/JavaTea/login.php';
    $name = 'Login';
}
?>
 <body>
  <div align="center">
   <a href="/JavaTea">Home</a>&nbsp;&nbsp;&nbsp;
   <a href="/JavaTea/FAQ.php">FAQ</a>&nbsp;&nbsp;&nbsp;
   <a href="<?php echo $link; ?>"><?php echo $name; ?></a>
  </div>
  <br/>
  <br/>
  <br/>
