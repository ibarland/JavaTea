<?php
  /* Generate a JavaTea page.
   * PRE-CONDITIONS:  The following must be defined:
   * $sig, $functionPurpose, $equalityTest, $buggySolns, $BUGGY_SUFFIX_DELIMITER, 
   *  and $projRootDir, $probID
   * (and, $_POST, containing tests[][] ).
   */

 
  $time0 = time();
  $debugProgressTimer = '(sync at ' . time() . ")\n";
  $debugProgressTimer .= (time()-$time0) . " - probs-inc.php, point A\n";
//   recordDiagnosticTime(__FILE__);


  // ARG, rucs's php is old version w/o no anonymous functions!
  // $buggySolns = $array_map( function($itm) {return "_bug_$itm";}, array('A','B','C') );
  $buggySolns = array_map( create_function('$itm', "return \"${BUGGY_SUFFIX_DELIMITER}\$itm\";"), $buggySolns );
  
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

  // *** Not called ?!?   
  // *** Wait-- is php not case-sense?; is that why it still worked w/ the wrong-case call?
  function testCaseAsString( $codeToLookupActualResult, $codeToLookupDesiredResult ) {
      //      print_r( $data );
      global $equalityTest;
      $result = "";
      $result .= $equalityTest['pre'];
      $result .= $codeToLookupActualResult;
      $result .= $equalityTest['mid'];
      $result .= $codeToLookupDesiredResult;
      $result .= $equalityTest['post'];
      return $result;
      }

  $debugProgressTimer .= (time()-$time0) . " - probs-inc.php, point B\n";

  /********************/
  //  Begin the creating/running of the Java tests
  $probName = $sig[0]['name'];
  $className = ucfirst($probName);
  $tests = array2D_map( create_function('$s','return stripslashes($s);'), 
                        get($_POST, "tests", Array()) );  // stripslashes to undo magic quotes
  $tests = array2D_map( create_function('$s','return stripslashes($s);'), $tests );  // *** ?!@?
  // (a) Why can't I just pass stripslashes directly?
  // (b) why did the server[?] addslashes *twice*??
  $numTests = count($tests);
  $numBuggySolns = count($buggySolns);


ob_start();
echo <<<END_JAVA_SEGMENT
class Test${className} {

  public static boolean[][] runTests() {
    // An array of actual-results of calling each test: test-case x buggy-soln 
    {$sig[0]['type']}[][] funcResults = new {$sig[0]['type']}[$numTests][$numBuggySolns];
    // An array of whether or not the test-case passed: test-case x buggy-soln 
    boolean[][] testResults = new boolean[$numTests][$numBuggySolns];

END_JAVA_SEGMENT;

  echo "    // parallel arrays: one for each argument (and arg0 for the result).\n";
  foreach ($sig as $argNum => $paramSig) {
    echo "    {$paramSig['type']}[] arg$argNum = new ${paramSig['type']}[$numTests];\n";
    }

  echo "    // Eval each expression, caching results in arg_i.\n";
  $codeToLookupEvaldArg = array();  // java-source-as-string: code to just look up an array element.
  foreach ($tests as $i => $testI) {
    foreach ($sig as $argNum => $paramSig) {
      $codeToLookupEvaldArg[$i][$argNum]  = "arg${argNum}[$i]";
      echo "    {$codeToLookupEvaldArg[$i][$argNum]} = (".($tests[$i][$argNum]).");\n"; // *** addslashes here, but only quote '\', not any '"' etc.
      }
    echo "\n";
    }

  // Now, [generate java code to] compare results with expected results:
  foreach ($tests as $i => $testI) {
    foreach ($buggySolns as $buggySolnIdx => $buggySoln) {
      // generate code to: Call the function, and store results in funcResults[][]
      echo "    funcResults[$i][$buggySolnIdx] = ";
      echo javaFuncCall($sig, $codeToLookupEvaldArg[$i], $buggySoln);
      echo ";\n";

      // generate code to: Check whether the actual results matched the desired result.
      echo "    testResults[$i][$buggySolnIdx] = ";
      echo testCaseAsString( "funcResults[$i][$buggySolnIdx]", $codeToLookupEvaldArg[$i][0] );
      echo ";\n";
      }
    echo "\n";
   }

echo <<<END_JAVA_SEGMENT
    return testResults;
    }


  public static void main( String[] args ) {
    System.out.println( java.util.Arrays.deepToString( runTests() ) );
    }

  }

END_JAVA_SEGMENT;

  $prog = ob_get_clean();
  $debugProgressTimer .= (time()-$time0) . " - probs-inc.php, point C\n";
  //$JRE_HOME = "/home/itec120/dynamic_php/JavaTea/lib/java";   *** delete once next line tested
  $JRE_HOME = "$projRootDir/lib/java";

  $testClassName = "Test" . $className;
  $javaClassFileName = $testClassName . ".class";
  $javaTestingDir = "dir" + session_id();
  $javaSourceFileName = /*$javaTestingDir . DIRECTORY_SEPARATOR .*/ $testClassName . ".java";  // *** fix this once we can look up php api for mkdir etc
  $debugProgressTimer .= (time()-$time0) . " - probs-inc.php, point D\n";
  if (file_exists($javaClassFileName)) { unlink( $javaClassFileName ); }
  $debugProgressTimer .= (time()-$time0) . " - probs-inc.php, point E\n";
  fwrite( fopen( $javaSourceFileName, 'w' ), $prog );
  $debugProgressTimer .= (time()-$time0) . " - probs-inc.php, point F\n";
  if (!file_exists($javaSourceFileName)) { javaTeaErr( 'Source file not created' ); }
  $compileResultsPipe = my_exec( "$JRE_HOME/bin/javac $javaSourceFileName" );
  $debugProgressTimer .= (time()-$time0) . " - probs-inc.php, point G\n";
  reportAnyCompileErrors( $compileResultsPipe["stderr"] );
  if (!file_exists($javaClassFileName)) { javaTeaErr( 'Class file not created' ); }
  $debugProgressTimer .= (time()-$time0) . " - probs-inc.php, point H\n";

  $runResultsPipes = my_exec( "$JRE_HOME/bin/java -Djava.security.manager $testClassName" );
  $debugProgressTimer .= (time()-$time0) . " - probs-inc.php, point I\n";
  $runResultsStr0 = $runResultsPipes["stdout"];
  if ($runResultsPipes["stderr"]) {
    echo "<span class='fatal'>Error compiling file:</span>\n";
    echo $runResultsPipes["stderr"];
    }
  $debugProgressTimer .= (time()-$time0) . " - probs-inc.php, point J\n";
  my_exec( "rm -f '$javaSourceFileName'" );
  my_exec( "rm -f '$javaClassFileName'" );
  $debugProgressTimer .= (time()-$time0) . " - probs-inc.php, point K\n";
  // see php escapeshellarg(), escapeshellcmd()
  // Hack: parse the string as 2-D array of bools.
  // A better solution would have the Java output a string in php syntax.
  // But this will do, since we don't have any strings,etc embedded in the array.
  $runResultsStr1 = str_replace( '[', 'array(', $runResultsStr0 );
  $runResultsStr2 = str_replace( ']', ')', $runResultsStr1 );
  eval( '$runResults = ' . $runResultsStr2 . ';' );  // Parse string to array.
  $debugProgressTimer .= (time()-$time0) . " - probs-inc.php, point L\n";
  
  
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


  $debugProgressTimer .= (time()-$time0) . " - probs-inc.php, point L\n";
  $testsWereValid = (count(findInColumn($runResults,0,false,false)) > 0);
  $bugsThatPassed = bugsMissed($runResults);
  $debugProgressTimer .= (time()-$time0) . " - probs-inc.php, point M\n";

  


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
    var td = function(bod,clss) { 
      return enTag( 'td',  (clss ? {'class':clss} : {}),  bod ); 
      }.defaults(/*clss=*/null);


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
      var caseNum = (existingTests ? existingTests.length-(numTests?1:0) : 0); // *** HACK The bugIconRow
      var tstCase = document.createElement('tr');
      // Provide an id (for the entire row, not just one of the input box), 
      // in case we want to delete row later:
      if (!asSignature) { tstCase.setAttribute('id', 'tests['+caseNum+']' ); } 
      tstCase.appendChild( td( asSignature ? signature[0].type 
                                           : "<?php echo "${equalityTest['pre']}"; ?>" ) );
      tstCase.appendChild( td(signature[0].name) );
      tstCase.appendChild( td("(") );
      for (var i=1;  i in signature;  ++i) {
        if (i != 1) tstCase.appendChild( td(", ") );
        tstCase.appendChild( td( asSignature ? signature[i].type : "" ) );
        tstCase.appendChild( asSignature ? td(signature[i].name) : argInputBox(caseNum,i,data[i] || "") );
        }
      tstCase.appendChild( td(")") );
      tstCase.appendChild( td( asSignature ? "" : "<?php echo "${equalityTest['mid']}"; ?>" ) );
      tstCase.appendChild( asSignature ? td("") : argInputBox(caseNum,0,data[0] || "") );
      tstCase.appendChild( td( asSignature ? "" : "<?php echo "${equalityTest['post']}"; ?>" ) );
      //tstCase.appendChild( asSignature ? td("") : delRowButton() );
      return tstCase;
      }




    function insertBugIconsRow( node, bugsMissed ) {
      var theRow = document.createElement('tr');
      <?php $numCellsForSig = 3*count($sig)-1-(count($sig)>1 ? 1 : 0)+2+1+1+1; ?>  // *** should really make a dummy row, and count domNode.children ?
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
    ,'free-clipart-pictures.net/butterfly_clipart_pink.gif'
    ,'freeclipartnow.com/preying-mantis-close.jpg'
    ,'free-clipart-pictures.net/butterfly_clipart_gray.gif'
                  ].map( function(itm) {return "<?php echo $projRootDir;?>/images/"+itm;} );
  </script>
 </head>


 <body>
<?php
    $debugProgressTimer .= (time()-$time0) . " - probs-inc.php, point N\n";
  include( "$projRootDir/templates/header-inc.php" );
  $debugProgressTimer .= (time()-$time0) . " - probs-inc.php, point O\n";
?>
    <p class='description'>
    <code><table><tr id="sig" class='description'/></table>
    </code>
    <br/>
      Purpose: <?php echo nl2br($functionPurpose); ?>
    </p>

  <form action="<?php echo "$projRootDir/probs/$probID/index.php" ?>" method="post">
    <input type="hidden" name="probId" id="probId" value="<?php echo "$probID";?>" />

    <table border="0"><tr><td> <!-- Ugh, a hack to suppress any linebreak between codeblock, button -->
    <code>
      <table align="center" class="tests" id="tests"></table>  
      <!-- Pls. keep `tests` body entirely empty of whitespace, kthx. -->
    </code>
    </td><td id="insertTD">
    <input type="button" value="add another test" id="insert" onclick="insertCase('tests',{})" /><br/>
    </td></tr></table>

    <input type="submit" name="submit" value="Test buggy code" onclick="verifyInput()" />
  </form>  

  <script type="text/javascript">
     // javascript: outside version is an actual array; contents are objs w/ (two) properties.
     //   { 0 : {name:"timesThree", type:"double"}, 1 : {name:"x", type:"double"} }
     //
    <?php
      echo valToJavascriptVar( "signature", $sig );
      echo valToJavascriptVar( "tests", $tests ), "\n"; //get($_POST,'tests',Array()));
      echo valToJavascriptVar( "runResults", $runResults );
      echo valToJavascriptVar( "bugsMissed", $bugsThatPassed );
      echo valToJavascriptVar( "numBugsMissed", count($bugsThatPassed) );
      echo valToJavascriptVar( "numTests", count($runResults) );
      echo valToJavascriptVar( "numBuggyImplementations", count(get($runResults,0,array('dummy')))-1);
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

<?php
    $debugProgressTimer .= (time()-$time0) . " - probs-inc.php, point P\n";
    //echo "<pre>" . "Debug results: " . $debugProgressTimer . "</pre>";
?>

    <pre><?/*php echo($diagnostics['timings']);*/ ?> </pre>
  </body>
</html>
