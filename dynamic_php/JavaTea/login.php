<?php

include_once( 'init.php' );

if(is_authed()) {
    header("Location: index.php");
}
?>

<html><head><title>JavaTea</title></head>
<?php
include 'templates/header.inc.php';

if (!isset($_POST['submit'])) {
  include('templates/login.inc.php');
  exit;
} else {
  $result = user_login($_POST['uname'], $_POST['pass']);

  if ($result) {
    echo "Thank you for logging in, ", $_POST['uname'], "; <a href='index.php'>click here</a> to go back.";
    unset($login_error);
    }
  else {
    $login_error = 'Incorrect username or password';
    include 'templates/login.inc.php';
    }
}
?>
