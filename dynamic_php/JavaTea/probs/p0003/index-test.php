<?php

$_POST = array(  
  tests => array(0 => array('true',  '"abba"') 
                ,1 => array('false', '"abbx"')
                ,2 => array('true',  '"aba"')
                )
  );



$_SERVER = array(
  "PHP_SELF" => "/~itec120/JavaTea/probs/p0001/index-test.php"
  );
define('DIRECTORY_SEPARATOR', '/');



$OFFLINE_TESTING_MODE = true;  // Leave entirely undefined, for false.

require('index.php');

?>
