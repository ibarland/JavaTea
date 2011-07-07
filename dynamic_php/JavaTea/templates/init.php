<?php
ini_set('display_errors',true); 
ini_set('display_startup_errors',true); 
//ini_set('log_errors', true);
//ini_set('error_log', dirname(__FILE__) . '/err.txt');
error_reporting (E_ALL | E_STRICT); 


ini_set('date.timezone','America/New_York');
define('ROOT_DIR', '/~itec120/JavaTea');

$maxLengths = array();
$maxLengths['username'] = 30;
$maxLengths['password'] = 30;  // N.B. in database we store md5(..), which is char(32) (a hex numeral)
$maxLengths['temail'] = $maxLengths['username'];
$maxLengths['salt'] = 8;
$maxLengths['arg'] = 8;


session_start();


/****
// Check for time-out:
if (is_authed() AND (time() - $_SESSION['mostRecentActivity'] > $session_timeout)) {
  user_logout();
  echo "<p class='additional-info'>${_SESSION['email']}, your session has timed out due to inactivity</p>";
  }

echo "<p align='center' class='additional-info'/>";
if (isset($_SESSION['email'])) {
  $_SESSION['mostRecentActivity'] = time(); // re-set the activity timer.
  echo "Hi, ", $_SESSION['user'], ".";
  }
else {
  echo "You are not logged in.";
  }
echo "</p>\n";
****/


  
$db_host = 'localhost'; // run the mysql instance on the php server.
$db_user = 'itec120';
$db_pass = 'cdrtee';
$db_schema = $db_user;

$session_timeout = 30*60;
  
mysql_connect($db_host, $db_user, $db_pass) or die("Could not connect to db $db_host");
mysql_select_db($db_schema) or die("Could not connect to schema $db_userselect.");
  
//srand();
  
//FIX: These should be relative to this fie
require_once(  dirname(__FILE__).'/'.'../lib/functions-util.php' );
require_once(  dirname(__FILE__).'/'.'../lib/functions-db.php' );
?>
