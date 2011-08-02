<?php
  $depthThisFile = substr_count($_SERVER["PHP_SELF"],DIRECTORY_SEPARATOR);
  $depthProjRoot = substr_count('/~itec120/JavaTea/',DIRECTORY_SEPARATOR);
  $projRootDir = str_repeat('../', $depthThisFile - $depthProjRoot );
  $projRootDir = rtrim($projRootDir,'/');
  require_once( "$projRootDir/templates/init.php" );
?>

<?php
  $probID = "p0002";
  $functionPurpose = "Return a string just like <code>s</code>, but with all the letters reversed.";

  $sig = Array( Array("name"=>"reverseString", "type" => "String") // [0] is the function name/return-type.
              , Array("name"=>"s", "type" => "String")          // 1st parameter
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
  $equalityTest="==";  // or, .equals ?
  require( "$projRootDir/probs/probs-inc.php" );
?>
