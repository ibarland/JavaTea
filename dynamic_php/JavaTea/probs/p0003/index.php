<?php
  $depthThisFile = substr_count($_SERVER["PHP_SELF"],DIRECTORY_SEPARATOR);
  $depthProjRoot = substr_count('/~itec120/JavaTea/',DIRECTORY_SEPARATOR);
  $projRootDir = str_repeat('../', $depthThisFile - $depthProjRoot );
  $projRootDir = rtrim($projRootDir,'/');
  require_once( "$projRootDir/templates/init.php" );
?>

<?php
  $probID = "p0003";
  $functionPurpose = "Tell whether a string is a palindrome: that is, whether it reads exactly the same when reversed letter-for-letter.";
  $sig = Array( Array("name"=>"isPalindrome", "type" => "boolean") // [0] is the function name/return-type.
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
  $buggySolns = array('A','B','C','D','E');
  $BUGGY_SUFFIX_DELIMITER = '_bug_';
  $equalityTest= array( 'pre'=>'', 'mid'=>"==", 'post'=>'' );  // or, .equals ?
  require( "$projRootDir/probs/probs-inc.php" );
?>
