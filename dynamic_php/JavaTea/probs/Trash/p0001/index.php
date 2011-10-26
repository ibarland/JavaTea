<?php
  $depthThisFile = substr_count($_SERVER["PHP_SELF"],DIRECTORY_SEPARATOR);
  $depthProjRoot = substr_count('/~itec120/JavaTea/',DIRECTORY_SEPARATOR);
  $projRootDir = str_repeat('../', $depthThisFile - $depthProjRoot );
  $projRootDir = ($projRootDir ? rtrim($projRootDir,'/') : '.');
  require_once( "$projRootDir/templates/init.php" );
?>

<?php
  $probID = "p0001";
  $functionPurpose = "Triple a number.";

  $sig = Array( Array("name"=>"timesThree", "type" => "double") // [0] is the function name/return-type.
              , Array("name"=>"x", "type" => "double")          // 1st parameter
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
  $equalityTest= array( 'pre'=>'', 'mid'=>"==", 'post'=>'' );  // or, .equals ?
  require( "$projRootDir/templates/probs-inc.php" );
?>
