<?php
  $depthThisFile = substr_count($_SERVER["PHP_SELF"],DIRECTORY_SEPARATOR);
  $depthProjRoot = substr_count('/~itec120/JavaTea/',DIRECTORY_SEPARATOR);
  $projRootDir = str_repeat('../', $depthThisFile - $depthProjRoot );
  require_once( $projRootDir . 'templates/init.php' );
?>

<?php
  $equalityTest="==";  // or, .equals ?


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
  $BUGGY_SUFFIX_DELIMITER = "_bug_";
  $buggySolns = Array('A','B','C');

  // Now, modify $buggySolns for internal use: map-prepend the delimiter, and include one non-buggy-call.
  foreach ($buggySolns as $i => $suffix) {
    $buggySolns[$i] = $BUGGY_SUFFIX_DELIMITER . $suffix;
    }
  array_unshift( $buggySolns, '' );  // Our own code expects the good sol'n to be first.

  // javascript: outside version is an actual array; contents are objs w/ (two) properties.
  //   { 0 : {name:"timesThree", type:"double"}, 1 : {name:"x", type:"double"} }
  //
  echo enscript( "var signature = " . toJsString($sig) . ';' );
  echo enscript( "var tests = " . toJsString(get($_POST,'tests',Array())) . ';' );


  function javaTeaErr( $msg ) {
    echo "<span class='fatal'>JavaTea Error: $msg.</span><br />\n";
    }
  function reportAnyCompileErrors( $compileStderr ) {
    if ($compileStderr) {
      echo "<span class='fatal'>Error in compiling file:</span><br />\n";
      echo "<pre>$compileStderr</pre><br />\n";
      }
    }


  // THIS IS PHP, NOT JAVASCRIPT!
  /** Return a string of Java source code, calling a function.
   * @param $signature The signature of the function being called, as per global $sig.
   * @param $data  The expressions to use for each argument (as strings of Java source code).
   * @param $suffix A suffix to add to the function's name.
   */
  function javaFuncCall( $signature, $data, $suffix = '') {
    $className = ucfirst( $signature[0]['name'] );
    $result = "";
    $result .=  $className . "." . $signature[0]['name'] . $suffix;
    $result .= "(";
    foreach ($signature as $i => $param) {
    // $result .= "/* i is $i " . (is_string($i) ? "(string)": "(int)") . "*/";
      if ($i == 0) { continue; }
      if ($i != 1) { $result .= ", "; }
      $result .= $data[$i];
      }
    $result .= ")";
    return $result;
    }

  function testcaseAsString( $sig, $data, $suffix, $comment ) {
      //      print_r( $data );
      global $equalityTest;
      $result = javaFuncCall( $sig, $data, $suffix );
      $result .= $equalityTest;
      $result .= $data[0] . ";";
      return $result . "  // " . $comment;
      }

  /********************/
  //  Begin the creating/running of the Java tests
  $probName = $sig[0]['name'];
  $className = ucfirst($probName);
  $tests = get($_POST, "tests", Array());
  $numTests = count($tests);
  $numBuggySolns = count($buggySolns);


ob_start();
echo <<<END_JAVA_SEGMENT
class Test${className} {

  public static boolean[][] runTests() {
    // An array of test-case x buggy-soln (whether or not the test-case passee).
    boolean[][] results = new boolean[$numTests][$numBuggySolns];

END_JAVA_SEGMENT;

  echo "    // parallel arrays: one for each argument (and arg0 for the result).\n";
  foreach ($sig as $argNum => $paramSig) {
    echo "    ${paramSig['type']}[] arg$argNum = new ${paramSig['type']}[$numTests];\n";
    }

  echo "    // Eval each expression, caching results in arg_i.\n";
  $evaldArgLookupCode = array();  // java-source-as-string: code to just look up an array element.
  foreach ($tests as $i => $testI) {
    foreach ($sig as $argNum => $paramSig) {
      echo "    arg${argNum}[$i] = ({$tests[$i][$argNum]});\n";
      $evaldArgLookupCode[$i][$argNum]  = "arg${argNum}[$i]";
      }
    echo "\n";
    }

  foreach ($tests as $i => $testI) {
    foreach ($buggySolns as $buggySolnIdx => $buggySoln) {
      echo "    results[$i][$buggySolnIdx] = ";
      echo testCaseAsString($sig, $evaldArgLookupCode[$i], $buggySoln, "test #$i" ), "\n";
      }
    echo "\n";
   }

echo <<<END_JAVA_SEGMENT
    return results;
    }


  public static void main( String[] args ) {
    System.out.println( java.util.Arrays.deepToString( runTests() ) );
    }

  }

END_JAVA_SEGMENT;

  $prog = ob_get_clean();
  $JRE_HOME = "/home/itec120/dynamic_php/JavaTea/lib/java";

  $testClassName = "Test" . $className;
  $javaSourceFileName = $testClassName . ".java";
  $javaClassFileName = $testClassName . ".class";
  if (file_exists($javaClassFileName)) { unlink( $javaClassFileName ); }
  fwrite( fopen( $javaSourceFileName, 'w' ), $prog );
  if (!file_exists($javaSourceFileName)) { javaTeaErr( 'Source file not created' ); }
  $compileResultsPipe = my_exec( "$JRE_HOME/bin/javac $javaSourceFileName" );
  reportAnyCompileErrors( $compileResultsPipe["stderr"] );
  if (!file_exists($javaClassFileName)) { javaTeaErr( 'Class file not created' ); }

  $runResultsPipes = my_exec( "$JRE_HOME/bin/java -Djava.security.manager $testClassName" );
  $runResultsStr0 = $runResultsPipes["stdout"];
  if ($runResultsPipes["stderr"]) {
    echo "<span class='fatal'>Error compiling file:</span>\n";
    echo $runResultsPipes["stderr"];
    }
  // see php escapeshellarg(), escapeshellcmd()
  // Hack: parse the string as 2-D array of bools.
  // A better solution would have the Java output a string in php syntax.
  // But this will do, since we don't have any strings,etc embedded in the array.
  $runResultsStr1 = str_replace( '[', 'array(', $runResultsStr0 );
  $runResultsStr2 = str_replace( ']', ')', $runResultsStr1 );
  eval( '$runResults = ' . $runResultsStr2 . ';' );  // Parse string to array.
  
  function resultToPF($t) {
    $report = ($t ? "pass" : "fail"); 
    return $report; "<td class='test-$report'>$report</td>";
    }
  $runResultsPF = array2D_map( "resultToPF", $runResults );
  
  //echo array2DToHTMLTable($runResultsPF);


?>



<html>
 <head><title>JavaTea</title>
   <style type="text/css"> 
     .tests : { padding:0px; border:1px blue; }
errCase : { color
   </style>

   <script type="text/javascript" src="<?php echo ROOT_DIR; ?>/lib/functions-util.js" ></script>
   <script type="text/javascript">

    /** Return a td tag with the given body.
     * @param bod (string or node): The body of the td tag.
     *    If a String, it'll be wrapped in a createTextNode.
     * @return a td node with the given body.
     */
    var td = function(bod,class) { 
      return enTag( 'td',  (class ? {'class':class} : {}),  bod ); 
      }.defaults(/*class=*/null);


    /** Return an `input` tag for taking test-case values.
     * @param Which argument# this is (0 for return type, or 1..$signature.length).
     * @return an `input` tag for taking test-case values.
     */
    function argInputBox(testNum, argNum, content) {
      var newInput = document.createElement('input');
      newInput.setAttribute('type','text');
      newInput.setAttribute('name','tests['+testNum+']['+argNum+']');  //e.g.tests[17][2].
      newInput.setAttribute('id',  'tests['+testNum+']['+argNum+']');  //e.g.tests[17][2].
      //newInput.setAttribute('width',...Must-get-maxLengths-from-php-into-the-js... $maxLengths['argNum']);
      //newInput.setAttribute('onblur','checkType(this,$sig[argNum])');  // TODO: write checkType, client-side???
      newInput.setAttribute('value',content);
      return newInput;
      }

    /** Return html for either a signature, or input-boxes for test cases.
     * @param data An object of the form { 0 => "retVal", 1 => "arg1", ... }
     *       to use as values, or a non-object to represent "make this a signature, not a test case"
     * @return html for either a signature, or input-boxes for test cases.
     * Uses `signature[]` from surrounding scope.
     */
  function anotherCaseAsTr( data ) {
      var asSignature = (typeof(data) !== "object");
      var existingTests = document.getElementById('tests').children;
      if (!existingTests && !asSignature) javateaWarn("<?php echo(__FILE__);?>","anotherCase","Adding test cases before table-of-tests is defined.");
      var caseNum = (existingTests ? existingTests.length : 0);
      var tstCase = document.createElement('tr');
      // Provide an id (for the entire row, not an input box), in case we want to delete row later:
      if (!asSignature) { tstCase.setAttribute('id', 'tests['+caseNum+']' ); } 
      tstCase.appendChild( td( asSignature ? signature[0].type : "" ) );
      tstCase.appendChild( td(signature[0].name) );
      tstCase.appendChild( td("(") );
      for (var i=1;  i in signature;  ++i) {
        if (i != 1) tstCase.appendChild( td(", ") );
        tstCase.appendChild( td( asSignature ? signature[i].type : "" ) );
        tstCase.appendChild( asSignature ? td(signature[i].name) : argInputBox(caseNum,i,data[i] || "") );
        }
      tstCase.appendChild( td(")") );
      tstCase.appendChild( td( asSignature ? "" : "<?php echo "$equalityTest"; ?>" ) );
      tstCase.appendChild( asSignature ? td("") : argInputBox(caseNum,0,data[0] || "") );
      //tstCase.appendChild( asSignature ? td("") : delRowButton() );
      return tstCase;
      }


   

    function insertCase( node, data, resultStrs ) {
      var latestTestCase = anotherCaseAsTr( data );
      for (var i in resultStrs) {
        latestTestCase.appendChild( td( resultStrs[i], 'test-'+resultStrs[i] ) );
        }
      document.getElementById(node).appendChild( latestTestCase );
      var firstArgInputBox = document.getElementById( latestTestCase.getAttribute("id") + "[1]" );
      if (firstArgInputBox) firstArgInputBox.focus();
      }
  </script>
 </head>

<?php
  include( $projRootDir . 'templates/header-inc.php' );
?>

    <p><code><table><tr id="sig"/></table>
    </table></code> <br/>
      <code>timesThree</code> takes in any double and returns three times its value.
   </p>

  <form action="<?php echo basename(__FILE__) ?>" method="post">
    <input type="hidden" name="probId" id="probId" value="p0001"/>

    <code>
      <table align="center" class="tests" id="tests"></table>  
      <!-- Pls. keep `tests` body entirely empty of whitespace, kthx. -->
    </code>
    <input type="button" value="Add Another Test" onclick="insertCase('tests',{})" /><br/>

    <input type="submit" name="submit" value="Submit" />
  </form>  

  <script type="text/javascript">
    <?php echo valToJavascriptVar( "runResults", $runResultsPF ), "\n"; ?>
    insertCase('sig', true );
    for (var i=0;  i in tests;  ++i) {
      insertCase('tests', tests[i], runResults[i] );
      }
    if (! (0 in tests)) insertCase('tests', {});  // An initial blank row.
  </script>



<hr/>

  </body>



  <script type="text/javascript">

/*

Test #3: Bad spec ( f(17,23) = 43 (desired output), not 443 as provided)


timesThree_bad_v1's mistake was caught by test #3.
timesThree_bad_v2 has a bug, yet it passed all provided tests!


Test #1: Succeeded (actual and desired result is 43)
Test #2: Failed    (actual and desired result is 43)


*/



/*
  Links on parsing Java code:
    http://stackoverflow.com/questions/5993769/java-parser-written-in-javascript
    http://en.wikipedia.org/wiki/Comparison_of_JavaScript-based_source_code_editors

  Suggestions:
  - ANTLR;
  - Try a syntax-colorer instead of full parser?
 */ 



    /*
    - A .js AST library:  http://substack.net/posts/eed898
    - .js: use eval() or eval.call(  aNSArray, anExpr) ; if the Expr includes "*var* x = ...",
       that'll keep x local to ...that namesapce?  That expr?  The enclosing function???
       Another approach: all variables are actually object-attributes.
    - javax code to eval javascript:
        http://stackoverflow.com/questions/3422673/java-evaluate-string-to-math-expression
     */
</script>
</html>
