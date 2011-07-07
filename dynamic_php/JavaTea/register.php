<?php
include 'init.php';
include 'templates/header.inc.php';

if(!isset($_POST['submit'])) {
  include 'templates/register.inc.php';
  exit;
  }
else {
  $reg_error = '';
  if(empty($_POST['uname'])) {
    $reg_error .= 'Missing username. ';
    }
  if(empty($_POST['pass1'])) {
    $reg_error .= 'Missing password. ';
    }
  if(empty($_POST['pass2'])) {
    $reg_error .= 'Missing password2. ';
    }
  if(username_used($_POST['uname'])) {
    $reg_error .= 'Username is use already taken. ';
    }
  if($_POST['pass1'] != $_POST['pass2']) {
    $reg_error .= 'Your passwords do not match. ';
    }


  if ($reg_error) {
    include 'templates/register.inc.php';
    exit;
    }

  $temail = get( $_POST, 'temail', $_POST['uname'] );

  create_user($_POST['uname'], $_POST['pass1'], $temail);
  echo "Thank you for signing up, ${_POST['uname']}; <a href='index.php'>click here</a> to go back.";
}
?>
