<?php
session_start();

$db_host = 'localhost';
$db_user = 'javatea';
$db_pass = '2DwjexbZfvAGm3Zf';
$db_tbl = 'javatea';

mysql_connect($db_host, $db_user, $db_pass) or die('Could not connect');
mysql_select_db($db_tbl) or die('Could not select table.');

srand();

include 'functions.php';
?>
