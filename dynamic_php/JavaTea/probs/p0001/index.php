<?php
require_once( '../../templates/init.php' );
?>

<?php
  $equalityTest="==";  // or, .equals ?


  $sig = Array( Array("name"=>"timesThree", "type" => "double") // [0] is the function name/return-type.
              , Array("name"=>"x", "type" => "double")          // 1st parameter
              );
  // Yes, it might be more php-ish to say
  //   $sig = Array( "timesThree" => "double", "x" => "double");
  // but we'll keep things more parallel to the javascript code.

  // javascript: outside version is an actual array; contents are objs w/ (two) properties.
  //   { 0 : {name:"timesThree", type:"double"}, 1 : {name:"x", type:"double"} }
  //
  echo enscript( "var signature = " . toJsString($sig) . ';' );
  echo enscript( "var tests = " . toJsString(get($_POST,'tests',Array())) . ';' );

?>



<html>
 <head><title>JavaTea</title>
   <style type="text/css"> 
     .tests : { padding:0px; border:1px blue; }
   </style>

   <script type="text/javascript" src="../../lib/functions-util.js" ></script>
   <script type="text/javascript">

    /** Return a td tag with the given body.
     * @param bod (string or node): The body of the td tag.
     *    If a String, it'll be wrapped in a createTextNode.
     * @return a td node with the given body.
     */
    function td(bod) { return enTag( 'td', false, bod ); }


    /** Return an `input` tag for taking test-case values.
     * @param Which argument# this is (0 for return type, or 1..$signature.length).
     * @return an `input` tag for taking test-case values.
     */
   function argInputBox(testNum, argNum, content) {
      var newInput = document.createElement('input');
      newInput.setAttribute('type','text');
      newInput.setAttribute('name','tests['+testNum+']['+argNum+']');  //e.g.tests[17][2].
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
  function anotherCase( data, caseNum ) {
      var asSignature = (typeof(data) !== "object");
      var existingTests = document.getElementById('tests').children;
      if (!existingTests && !asSignature) javateaWarn("<?php echo(__FILE__);?>","anotherCase","Adding test cases before table-of-tests is defined.");
      if (!caseNum) caseNum = (existingTests ? existingTests.length : 0);
      var rowId = 'tests['+caseNum+']';  // N.B. unrelated to the *form*'s input name=test attrs.
      var tstCase = document.createElement('tr');
      tstCase.setAttribute('id', rowId );  // In case we want to delete this row later.
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

    function insertCase( node, data ) {
      document.getElementById(node).appendChild( anotherCase( data ) );
      }
  </script>
 </head>

<?php
  include( '../../templates/header-inc.php' );
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
    insertCase('sig', true );
    for (var i=0;  i in tests;  ++i)  insertCase('tests', tests[i] );
    if (! (0 in tests)) insertCase('tests', {});  // An initial blank row.
  </script>



<hr/>
<?php

      echo "<pre>\n";
      $progName = $sig[0];
      echo $progName['name'];

/*
      echo $sig[0]['name'];
echo <<<"END_JAVA_PROG"

  public static void main( String[] args ) {
    test( , )
    }
  }
END_JAVA_PROG;
*/
      echo "</pre>\n";
  

      /*    echo("The data:");
if ( isset($_POST['submit']) ) {
  echo "<pre>\n";
  print_r($_POST['tests']); 
  echo "</pre>\n";
  }
      */

?>


  </body>
</html>

