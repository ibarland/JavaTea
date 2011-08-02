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
  //
  
  // ARG, rucs's php is old version w/o no anonymous functions!
  //$buggySolns = $array_map( function($itm) {return "_bug_$itm";}, array('A','B','C') );
  $buggySolns = array_map( create_function('$itm', 'return "_bug_$itm";'), array('A','B','C') );
  
  array_unshift( $buggySolns, '' );  // Our own code expects the good sol'n to be first.

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
    // An array of test-case x buggy-soln (whether or not the test-case passed).
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
  
  
  //echo array2DToHTMLTable($runResults);




  /** Return which bugs weren't caught, as indicated by the $testResults.
   * @param $testResults A 2-D array of booleans: 
   *    $testResults[t][b] iff test #t passed (buggy) implementation#b.
   *    NOTE: column 0 is *not* considered a buggy implementation, and 0 won't be returned.
   * @return A list of those implementations which passed all tests.
   */
  function bugsMissed( $testResults ) {
    $misses = array();
    foreach (get($testResults,0,array()) as $bugID => $dummy) {
      if ($bugID !=0  &&  count(findInColumn($testResults,$bugID,false,false)) == 0) {
        $misses[] = $bugID; 
        }
      }
    return $misses;
    }


  $testsWereValid = (count(findInColumn($runResults,0,false,false)) > 0);
  $bugsThatPassed = bugsMissed($runResults);

  


?>



<html>
 <head><title>JavaTea</title>
    <!--  hacker-detected : yay, we unmasked a hacker!  (test-case failed)
          hacker-undetected : oh no -- a hacker passed *all* tests, incl. this one!
          hacker-inconclusive : we did detect this hacker, but not via this particular test.
    -->
   <style type="text/css"> 
     .tests  { padding:0px; border:1px blue; }
     .hacker-undetected, .input-invalid-type { color:#ff0000; }
     .hacker-detected { color:#008800; }
     .hacker-inconclusive, .input-valid-type { color:#000000; }
     .javaTea-error { color:#ff0000; blink:true}
     #insertTD { vertical-align:bottom; }
     .description { font-size:150% }
   </style>

   <script type="text/javascript" src="<?php echo ROOT_DIR; ?>/lib/functions-util.js" ></script>
   <script type="text/javascript">



    function verifyInput() {
      for (i in tests) {
        var testIEmpties = 0;
        for (j in tests[i]) {
          if (tests[i][j]) ++testIEmpties;
          }
        if (testIEmpties==tests[i].length) { /* remove test[i] */ }
        else if (testIEmpties > 0) {
          /* Turn input cell error-red */
          }
        }
      return true;
      }

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
      //if (!existingTests && !asSignature) javateaWarn("<?php echo(__FILE__);?>","anotherCase","Adding test cases before table-of-tests is defined.");
      var caseNum = (existingTests ? existingTests.length-1 : 0); // *** HACK The bugIconRow
      var tstCase = document.createElement('tr');
      // Provide an id (for the entire row, not just one of the input box), 
      // in case we want to delete row later:
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




    function insertBugIconsRow( node, bugsMissed ) {
      var theRow = document.createElement('tr');
      <?php $numCellsForSig = 3*count($sig)-1-(count($sig)>1 ? 1 : 0)+2+1+1; ?>
      for (var i=0;  i < <?php echo $numCellsForSig; ?>;  ++i) {
        theRow.appendChild( td("") );
        }
      for (var i=0;  i < numBuggyImplementations;  ++i) {
        theIcon = document.createElement('img');
        theIcon.setAttribute('src', bugIcons[i % bugIcons.length]);
        theIcon.setAttribute('width', BUG_ICON_WIDTH );
        theIcon.setAttribute('class', 'bug-icon' );
        theRow.appendChild( td(theIcon) );
        }
      document.getElementById(node).appendChild( theRow );
      }

   
    function resultsStyle( testPassed, bugMissed ) {
      if (testPassed) {
        return bugMissed ? 'hacker-undetected' : 'hacker-inconclusive';
        }
      else {
        if (bugMissed) { javaTeaError('resultsStyle', 'bugMissed, yet !testPassed ?!' ); }
        return bugMissed ? 'javatea-error' : 'hacker-detected';
        }
      }

    /** Add a row to the table of test-cases, filling it with `data` (expressions).
     * If `results` provided, then also add columns of the T/F results of the test-cases.
     * @param node The xml 'id' attr of a 'table' element, to add this row to.
     * @param data The expressions (Strings) to insert into the row.
     *        Presumably an array (well, properties) of arg-index => exprs (strings).
     *        If a non-object, then this will be a signature (no input boxes or exprs).  
     * @param results (boolean[]) For each buggy-implementation, did it pass?
     * @param bugsMissed A list of the buggy-implementations (column-numbers) that passed.
     */
    function insertCase( node, data, results, bugsMissed ) {
      if (results && !data) { javaTeaError('insertCase', 'results but no expressions?!'); }
      // The test cases:
      var latestTestCase = anotherCaseAsTr( data );
      // Now, the test results:
      for (var i in results) {
        if (i==0 || !results.hasOwnProperty(i)) continue;
        var tdClass = resultsStyle(results[i], bugsMissed.hasAPropWithValue(i));
        latestTestCase.appendChild( td( (results[i] ? "pass" : "fail"), tdClass ) );
        }
      document.getElementById(node).appendChild( latestTestCase );
      var firstArgInputBox = document.getElementById( latestTestCase.getAttribute("id") + "[1]" );
      if (firstArgInputBox) firstArgInputBox.focus();
      }


  var BUG_ICON_WIDTH = '40px';
  var bugIcons = [
     'free-clipart-pictures.net/insect_clipart_fly.gif'
    ,'freeclipartnow.com/ladybug-abstracted.jpg'
    ,'free-clipart-pictures.net/insect_clipart_spider.gif'
    ,'free-clipart-pictures.net/insect_clipart_snail.gif'
    ,'free-clipart-pictures.net/insect_clipart_dragonfly.gif'
    ,'freeclipartnow.com/amber-wing-dragonfly-female.jpg'
    ,'freeclipartnow.com/lady-bug.jpg'
                  ].map( function(itm) {return "<?php echo $projRootDir;?>/images/"+itm;} );
  </script>
 </head>


 <body>
<?php
  include( $projRootDir . 'templates/header-inc.php' );
?>
    <p class='description'>
    <code><table><tr id="sig" class='description'/></table>
    </code>
    <br/>
      <code>timesThree</code> takes in any number, and returns three times its value.
    </p>

  <form action="<?php echo basename(__FILE__) ?>" method="post">
    <input type="hidden" name="probId" id="probId" value="p0001"/>

    <table border="0"><tr><td> <!-- Ugh, a hack to suppress any linebreak between codeblock, button -->
    <code>
      <table align="center" class="tests" id="tests"></table>  
      <!-- Pls. keep `tests` body entirely empty of whitespace, kthx. -->
    </code>
    </td><td id="insertTD">
    <input type="button" value="add another test" id="insert" onclick="insertCase('tests',{})" /><br/>
    </td></tr></table>

    <input type="submit" name="submit" value="Test the hackers' code!" onclick="verifyInput()" />
  </form>  

  <script type="text/javascript">
     // javascript: outside version is an actual array; contents are objs w/ (two) properties.
     //   { 0 : {name:"timesThree", type:"double"}, 1 : {name:"x", type:"double"} }
     //
    <?php
      echo valToJavascriptVar( "signature", $sig ), "\n";
      echo valToJavascriptVar( "tests", get($_POST,'tests',Array())), "\n";
      echo valToJavascriptVar( "runResults", $runResults ), "\n";
      echo valToJavascriptVar( "bugsMissed", $bugsThatPassed ), "\n";
      echo valToJavascriptVar( "numBugsMissed", count($bugsThatPassed) ), "\n";
      echo valToJavascriptVar( "numTests", count($runResults) ), "\n";
      echo valToJavascriptVar( "numBuggyImplementations", count(get($runResults,0,array('dummy')))-1), "\n";
     ?>
    insertCase('sig', true );
    if (numTests>0) insertBugIconsRow('tests',bugsMissed);
    for (var i=0;  i < numTests;  ++i) {
      insertCase('tests', tests[i], runResults[i], bugsMissed );
      }
    if (numTests == 0) insertCase('tests', {});  // An initial blank row.
  </script>


  <p id='resultsPar'>  <!-- TODO: make 3 marked-up paragraphs, and set two of them to invis. -->
  </p>

  <script>
  var msg = "JavaTea Error: message not yet initialized.";
  if (numTests>0) {
    msg = (numBugsMissed==0) ? "Congratulations -- no" : "Uh-oh -- "+numBugsMissed;
    msg += " buggy implementation" + (numBugsMissed==1 ? "" : "s") + " managed to pass";
    msg += " every single one of your test cases!"
    if (numBugsMissed > 3) {
      msg += "  If we were counting on your tests to catch bugs, we'd have released the buggy versions and then have a lot of unhappy customers!";
      }
    }
  else {
    msg  = "Please create test-cases for this function: the inputs, and the desired result.\n";
    msg += "  Create (just) enough test-cases so that you'll detect *any* bugs in other people's code.";
    msg += "  If any buggy implementations manage to pass all your tests undetected,";
    msg += " then your tests are incomplete!";
    }
  document.getElementById('resultsPar').appendChild( document.createTextNode(msg) );
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
