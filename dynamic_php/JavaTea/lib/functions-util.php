<?php

function test($actual,$desired) {
  if ($actual===$desired)
    print ".";
  else
    print "Test failed: Got:\n$actual but wanted:\n$desired\n";
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
function arrayToHTMLRow( $arr, $asHeader ) {
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


  /** Return a *string* (not a DOM node -- this is server-side) of html.
   * @param $tagName The tag, e.g. `p`.
   * @param $attrs An array of attribute/value pairs, e.g. `{ "align" => "center" }
   * @param $bod The string to use as the body of the tag.  Must be proper html.
   * @return a *string* of html for the given tag.
   */
  function entag($tagName,$attrs,$bod) {
    $result = "";
    $result .= "<" . $tagName;
    foreach ($attrs as $attr => $val) {
      $result .= (' ' . $attr . '="' . $val . '"');
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
  function arrayToJavascriptVar($jsArrName,$phpArr) {
    $res = ""; //result
    $res .= "  var $jsArrName = ";
    $res .= arrayToJavascript($phpArr);
    }


  function quoteIfString($val) { return (is_string($val)) ? "'$val'" : $val; }

  function toJsString( $phpVal) {
    switch( gettype($phpVal) ) {
      case "boolean":
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



?>