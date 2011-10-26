<?php
  $depthThisFile = substr_count($_SERVER["PHP_SELF"],DIRECTORY_SEPARATOR);
  $depthProjRoot = substr_count('/~itec120/JavaTea/',DIRECTORY_SEPARATOR);
  $projRootDir = str_repeat('../', $depthThisFile - $depthProjRoot );
  $projRootDir = ($projRootDir ? rtrim($projRootDir,'/') : '.');
  require_once( "$projRootDir/templates/init.php" );
?>

<?php
  $probID = "p0004";
  $functionPurpose = "Given a number and a noun, return a string with that number and noun properly pluralized.\nSo given 4 and \"horse\", the function should return \"4 horses\".\nAssume that all nouns are made plural by ending \'s\' (that is, ignore nouns like \"mouse\" and \"dish\" and \"octopus\").";

  $sig = Array( Array("name"=>"pluralize", "type" => "String") // [0] is the function name/return-type.
              , Array("name"=>"n", "type" => "int")           // 1st parameter
              , Array("name"=>"noun", "type" => "String")     // 2nd parameter
              );
  // Yes, it might be more php-ish to say
  //   $sig = Array( "timesThree" => "double", "x" => "double");
  // but we'll keep things more parallel to the javascript code.

  // The implementation file (.java) should have 
  //  one correct implementation, named $sig[0]['name'], and
  //  for each incorrect implementations that same name prepended
  //  to an element of $buggySolns (with $BUGGY_SUFFIX_DELIMITER in between).
  // 
  // E.g. "foo", and incorrect "foo_bug_A", "foo_bug_willDivBy0", "foo_bug_rare"
  //
  $buggySolns = array('A','B','C');
  $BUGGY_SUFFIX_DELIMITER = '_bug_';
  $equalityTest = array( 'pre' => '',  'mid'=>'.equals(', 'post'=>')' );
  require( "$projRootDir/templates/probs-inc.php" );
?>
