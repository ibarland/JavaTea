<?php
$host="localhost";
$uname="javatea";
$password="2DwjexbZfvAGm3Zf";
$db_name="javatea";
$tbl_name="Users";

mysql_connect("$host", "$uname", "$password") or die("cannot connect");
mysql_select_db("$db_name") or die("cannot select db");

$login_name=$_POST['uname'];
$login_pass=$_POST['pass'];

$login_name=stripslashes($login_name);
$login_name=mysql_real_escape_string($login_name);
$login_pass=md5($login_pass);

$query="SELECT * FROM $tbl_name WHERE username='$login_user' AND password='$login_pass'";
$result=mysql_query($query);

$count=mysql_num_rows($result);

if($count == 1) {
    session_register("login_name");
    session_register("login_pass");
    header("location:index.php");
} else {
  echo "Wrong username or password";
}
?>
