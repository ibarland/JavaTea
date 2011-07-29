<?php
  $depthThisFile = substr_count($_SERVER["PHP_SELF"],DIRECTORY_SEPARATOR);
  $depthProjRoot = substr_count('/~itec120/JavaTea/',DIRECTORY_SEPARATOR);
  $projRootDir = str_repeat('../', $depthThisFile - $depthProjRoot );
  require_once( $projRootDir . 'templates/init.php' );

  my_session_destroy();
  header("Location: index.php");
?>
