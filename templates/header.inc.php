<?php
$link = '';
$name = '';
if(is_authed()) {
    $link = 'logout.php';
    $name = 'Logout';
} else {
    $link = 'login.php';
    $name = 'Login';
}
?>
<html>
 <head><title>JavaTea</title>
 </head>
 <body>
  <div align="center">
   <a href="/JavaTea">Home</a>&nbsp;&nbsp;&nbsp;
   <a href="FAQ.php">FAQ</a>&nbsp;&nbsp;&nbsp;
   <a href="<?php echo $link; ?>"><?php echo $name; ?></a>
  </div>
  <br/>
  <br/>
  <br/>
  <br/>
