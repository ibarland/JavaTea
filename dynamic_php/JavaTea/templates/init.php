<?php
ini_set('display_errors',true); 
ini_set('display_startup_errors',true); 
//ini_set('log_errors', true);
//ini_set('error_log', dirname(__FILE__) . '/err.txt');
error_reporting (E_ALL | E_STRICT); 

if (!isset($projRootDir)) {
  $depthThisFile = substr_count($_SERVER["PHP_SELF"],DIRECTORY_SEPARATOR);
  $depthProjRoot = substr_count('/~itec120/JavaTea/',DIRECTORY_SEPARATOR);
  $projRootDir = str_repeat('../', $depthThisFile - $depthProjRoot );
  $projRootDir = rtrim($projRootDir,'/');
  }

define('ROOT_DIR', '/~itec120/JavaTea');

require_once(  "$projRootDir/lib/functions-util.php" );
require_once(  "$projRootDir/lib/functions-db.php" );


ini_set('date.timezone','America/New_York');

$maxLengths = array();
$maxLengths['username'] = 30;
$maxLengths['password'] = 64;  // N.B. in database we store sha256(..), which is char(64) (a hex numeral)
$maxLengths['temail'] = $maxLengths['username'];
$maxLengths['salt'] = 8;
$maxLengths['arg'] = 8;


  // started Hanging? See session_write_close() ?
  session_set_cookie_params( 120/*mins*/*60, "/~itec120/JavaTea", ".radford.edu", true, true );
  my_session_start('JavaTeaSessionID');
  session_commit();  // *** SHOULD CHANGE FOR LOGIN PAGE


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


require( "$projRootDir/db-info.php" );  // get password.
if (!isset($OFFLINE_TESTING_MODE)) {
  mysql_connect($db_host, $db_user, $db_pass) or die("Could not connect to db $db_host");
  mysql_select_db($db_schema) or die("Could not connect to schema $db_userselect.");
  }
  
?>
