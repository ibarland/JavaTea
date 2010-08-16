<?php
include 'init.php';
include 'templates/header.inc.php';

if(!isset($_POST['submit'])) {
    include 'templates/register.inc.php';
    exit;
} else {
    if(empty($_POST['uname']) || empty($_POST['pass1']) || empty($_POST['pass2'])) {
        $reg_error = 'One or more fields missing';
        include 'templates/register.inc.php';
        exit;
    }
    else if($_POST['pass1'] != $_POST['pass2']) {
        $reg_error = 'Your passwords do not match';
        include 'templates/register.inc.php';
        exit;
    }
    
    else if(username_used($_POST['uname'])) {
        $reg_error = 'Username is use already';
        include 'templates/register.inc.php';
        exit;
    }
    
    $temail = $_POST['uname'];    
    if(!empty($_POST['temail'])) {
        $temail = $_POST['temail'];
    }

    create_user($_POST['uname'], $_POST['pass1'], $temail);
    echo 'Thank you for signing up, <a href="index.php">click here</a> to go back.';
}
?>
