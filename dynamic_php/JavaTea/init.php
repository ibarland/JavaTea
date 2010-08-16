<?php
session_start();

$db_host = 'picard.radford.edu:1521';
$db_user = 'codertea';
$db_pass = '1tst2tor';
$db_tbl  = 'foo';

//  We need to use oracle libraries, not mysql.
//mysql_connect($db_host, $db_user, $db_pass) or die('Could not connect to db');
//mysql_select_db($db_tbl) or die('Could not select table.');

srand();

include 'functions.php';
?>
