<?php

include 'functions.php';

if(is_authed()) {
    header("Location: index.php");
}

include 'templates/header.inc.php';

if(!isset($_POST['submit'])) {
    include 'templates/login.inc.php';
    exit;
} else {
    $result = user_login($_POST['uname'], $_POST['pass']);

    if($result) {
        echo 'Thank you for logging in, <a href="index.php">click her</a> to go back.';
    } else {
        $login_error = 'Incorrect username or password';
        include 'templates/login.inc.php';
    }
}
?>
