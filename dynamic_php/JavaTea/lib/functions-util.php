<?php

/** A test-harness function: if actual and desired aren't identical,
 * print a message to that effect (else just print ".").
 * @param $actual
 */
function test($actual,$desired) {
  if ($actual===$desired)
    print ".";
  else
    print "Test failed: Got:\n$actual but wanted:\n$desired\n";
  }

/** A function-version of the && operator, for use as a higher-order function
 * (e.g. for array_reduce).  Warning: remember that functions never short-circuit!
 * @param $a The first value to compare.
 * @param $b The second value to compare.
 * @return $a&&$b (but without short-circuiting).
 */
function andFunc($a,$b) { return $a && $b; }

/** A function-version of the || operator, for use as a higher-order function
 * (e.g. for array_reduce).  Warning: remember that functions never short-circuit!
 * @param $a The first value to compare.
 * @param $b The second value to compare.
 * @return $a||$b (but without short-circuiting).
 */
function orFunc($a,$b)  { return $a || $b; }

/** A function-version of the ! operator, for use as a higher-order function
 * (e.g. for array_reduce).
 * @param $a The value to negate.
 * @return !$a
 */
function notFunc($a,$b)  { return !$a; }



/** Map f to each entry in a 2-d array.  (Order of evaluation is not specified.)
 * @param $f A one-arg function, which will be given each entry in a 2-D array.
 * @param $arr2d The 2-D array to process.
 * @return An array with the same size of $arr2d, whose entries are $f applied
 *         to the corresponding entry in $arr2d.
 * Example:  array2D_map( sqrt, array(array(1,4,9), array(4,16,36)) )
 *         =                    array(array(1,2,3), array(2, 4, 6))
 */
function array2D_map( $f, $arr2d ) {
  // Arrg, php.radford.edu not yet running php 5.3, so we can't just say:
  // $rowHandler = function($row) use ($f) { return array_map($f,$row); };
  // return array_map( $rowHandler, $arr2d );
  $result = array();
  foreach ( $arr2d as $rowIdx => $row ) {
    $result[$rowIdx] = array();
    foreach ( $row as $colIdx => $entry ) {
      $result[$rowIdx][$colIdx] = $f($entry);
      }
    }
  return $result;
  }

/** get: a safe version of array-lookup.
 * @param arr The array to look up $idx in.
 * @param idx The index to look up in $arr.
 * @dflt  The default value to return, if $arr[$idx] doesn't exist or is null/false/0/empty.
 */
function get($arr, $idx, $dflt) {
  return (empty($arr[$idx]))  ?  $dflt  :  $arr[$idx];
  }
  /* Design rationale, on why we use $dflt if $arr[$idx] exists and is false:
   * In php, we are often using this on $_POST; empty text-box inputs will exist but be ''.
   * Note that check-boxes will never exist but be ''; if unchecked there will be nothing in $_POST.
   */




/** Return an HTML-table with the results of a query.
 * @param $results  A query-result object (list of rows).
 * @return an HTML-table with the results of a query.
 */
function SQLTableToHTML( $table ) {
  ob_start();
  echo "<table border='1'>\n"; 
  $firstTime = true;
  while ($row = mysql_fetch_array($table,MYSQL_ASSOC)) {
    if ($firstTime) echo arrayToHTMLRow($row,true);
    $firstTime = false;
    echo arrayToHTMLRow($row,false);
    }
  echo "</table>\n";
  return ob_get_clean();
  }
  
/** Return an HTML-table-row (<tr>) with the contents of an array.
 *  Used by SQLTableToHTML.
 * @param $arr  An associative array.
 * @param $asHeader  Return a a row  with <th>'s and array-keys? (vs <tr>'s and array-values)
 * @return an HTML-table-row (<tr>) with the contents of an array.
 */
function arrayToHTMLRow( $arr, $asHeader = false ) {
  $indent="  ";
  $cellTag = ($asHeader  ?  "th"  :  "td");
  $open  = "<"  . $cellTag . ">";
  $close = "</" . $cellTag . ">";
  ob_start();
  echo $indent."<tr>\n";
  foreach ($arr AS $col => $val) {
    echo  $indent.$indent. $open. htmlspecialchars(($asHeader ? $col : $val)). $close. "\n";
    }
  echo $indent."</tr>\n";
  return ob_get_clean();
  }


function array2DToHTMLTable( $arr2D, $colHeaders = null) {
  $soFar = "<table border='1'>\n";
  if ($colHeaders) {
    $soFar .= arrayToHTMLRow( $colHeaders, true );
    }
  foreach ($arr2D as $oneRow) {
    $soFar .= arrayToHTMLRow( $oneRow, false );
    }
  $soFar .= "</table>\n";
  return $soFar;
  }

  /** Return a *string* (not a DOM node -- this is server-side) of html.
   * @param $tagName The tag, e.g. `p`.
   * @param $attrs An array of attribute/value pairs, e.g. `{ "align" => "center" }
   * @param $bod The string to use as the body of the tag.  Must be proper html.
   * @return a *string* of html for the given tag.
   */
  function enTag($tagName,$attrs,$bod) {
    $result = "";
    $result .= "<" . $tagName;
    if ($attrs) {
      foreach ($attrs as $attr => $val) {
        $result .= (' ' . $attr . '="' . $val . '"');
        }
      }
    $result .= ">\n";
    if ($bod) $result .= $bod."\n";  // Avoid \n if $bod empty.
    $result .= "</" . $tagName . ">";
    return $result;
    }

  /** Return the provided javascript code wrapped in a 'script' tag.
   * @param $jsCode A string of javascript code.
   * @return $jsCode wrapped in a 'script' tag (a string).
   */
  function enscript($jsCode) {
    return enTag("script", array("type" => "text/javascript"), $jsCode);
    }


  /* The resulting js code makes an object with properties; 
   * iterate over that js object with "for (var i in ..obj..) { ...obj[i]... }
   * and/or use Object.keys(obj).length, and beware sparse arrays.
   */
  function valToJavascriptVar($jsVarName,$phpVal) {
    $res = ""; //result
    $res .= "  var $jsVarName = ";
    $res .= toJsString($phpVal);
    $res .= ";";
    return $res . "\n";
    }


  function quoteIfString($val) { return (is_string($val)) ? "'$val'" : $val; }

  function toJsString( $phpVal) {
    switch( gettype($phpVal) ) {
      case "boolean":
       // If we just sprintf('%s',false) we get the empty string (sigh)
       return sprintf('%s', ($phpVal ? "true" : "false") ); //

      case "integer":
      case "double": //includes float
        return sprintf('%s', $phpVal);
      case "NULL":
         return '""';
      case "string":
        return sprintf('"%s"', addslashes($phpVal));
      case "array":
        return arrayToJsString($phpVal);

      case "object":
      case "resource":
      case "unknown type":
        $errMsg = sprintf( 'toJsString: converting %s not (yet) supported (value: %s).', gettype($phpVal), $phpVal );
        throw new ErrorException( $errMsg );
      default:
        $errMsg = sprintf( 'toJsString: gettype(%s) returns unexpected value %s.', $phpVal, gettype($phpVal) );
        throw new ErrorException( $errMsg );
      }
    }

  /** Return a *string* representing js code to construct an array value for $phpArr.
   */
  function arrayToJsString($phpArr) {
    $res = "";
    $res = "{";
    $beenHereBefore = false;
    foreach ($phpArr as $key => $val) {
      if ($beenHereBefore) $res .= ", ";
      $beenHereBefore = true;
      $res .= $key . ":" . toJsString($val);
      }
    $res .= "}";
    return $res;
    /*  Note that we return (say) {key1:val1,...} but this literal isn't allowed as 
     *  a top-level expression.  If you need to do so, return [{key1:val1,...}][0] (sigh).
     */
    }

  /** Just for internal debugging: Evaluate a string and print the results. */
  function debug( $arrExpr ) {
    ob_start();
    echo "<tt>";
    if (is_string($arrExpr)) { echo "$arrExpr = "; }
    eval( '$rslt = ' . $arrExpr . ";" );
    //$contents = (is_string($arrExpr) ? eval( "$rslt = " . $arrExpr . ";" ) : $arrExpr );
    print_r( $rslt );
    echo ".\n</tt>";
    return ob_get_flush();
    }




  function my_exec($cmd, $input='')  {
    /* Credit to kexianin@diyism.com : http://www.php.net/manual/en/function.system.php#94929 */
    $proc=proc_open($cmd, array(array('pipe','r'),array('pipe','w'),array('pipe','w')), $pipes);
    fwrite($pipes[0], $input); fclose($pipes[0]); 
    $stdout=stream_get_contents($pipes[1]);fclose($pipes[1]); 
    $stderr=stream_get_contents($pipes[2]);fclose($pipes[2]); 
    $rtn=proc_close($proc); 
    return array('stdout'=>$stdout, 
                 'stderr'=>$stderr, 
                 'return'=>$rtn,
                 'command'=>$cmd,
                 'input'=>$input
                ); 
    } 

  /** Return the sha-256 hash of text.
   * @param msg (string) The message to hash.
   * @param return the sha-256 hash of $msg.  Same as hash('sha256',$msg).
   * @see hash, sha1.
   */
  function sha256($msg) { return hash('sha256',$msg); }


  /** For a given column of an array, return the row-indices that contain [or, do not contain]
   *  a given element.
   * @param $arr2D The array to search.
   * @param $colIdx The column index to search.  (mixed type)
   * @param $target The value to look for.
   * @param $negate Negate the search?  If set, find all items *not* equal to $target. (default false)
   * @return An array of row-indices rs such that ($arr2D[rs[i]][$colIdx] == $target) == !$negate.
   */
  function findInColumn( $arr2D, $colIdx, $target, $negate ) {
    if (!isset($negate)) $negate = false;
    $whenToIncrement = !$negate;  // (we are incidentally coercing to a boolean)
    $matches = array();
    foreach ($arr2D as $rowNum => $row) {
      if ( isset($row[$colIdx]) && ($row[$colIdx]==$target) == $whenToIncrement)
        $matches[] = $rowNum;
      }
    return $matches;
    }


?>
