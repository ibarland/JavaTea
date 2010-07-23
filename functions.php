<?php

function gen_salt() {
    $salt = "";
    for($i = 0;$i < 3;$i++) {
        $salt .= chr(rand(35, 126));
    }
    return $salt;
}

function create_user($username, $password, $temail) {
    $salt = gen_salt();
    $encrypted = md5(md5($password).$salt);
    $query = "INSERT INTO Users (username, password, teacher, salt, joined) VALUES ('$username', '$encrypted', '$temail', '$salt', CURDATE())";
    mysql_query($query) or die('Could not create user.');
}

function username_used($username) {
    $query = "SELECT * FROM Users WHERE username='$username'";
    $result = mysql_query($query);
    if(mysql_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function user_login($username, $password) {
    $query = "SELECT * FROM Users WHERE username='$username'";
    $result = mysql_query($query);
    $user = mysql_fetch_array($result);
    $encrypted = md5(md5($password).$user['salt']);

    $query = "SELECT * FROM Users WHERE username='$username' AND password='$encrypted'";
    $result = mysql_query($query);
    if(mysql_num_rows($result) > 0) {
        $user = mysql_fetch_array($result);

        $encrypted_name = md5($user['username']);
    
        $_SESSION['username'] = $username;
        $_SESSION['encrypted_name'] = $encrypted_name;
        return true;
    } else {
        return false;
    }
}

function user_logout() {
    session_unset();
    session_destroy();
}

function is_authed() {
    if(isset($_SESSION['username']) && (md5($_SESSION['username']) == $_SESSION['encrypted_name'])) {
    return true;
    } else {
        return false;
    }
}
?>
